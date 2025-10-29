<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Filter checklists based on user role
        if ($user->hasRole('Admin')) {
            $checklists = Checklist::with(['property', 'user', 'tasks'])->paginate(10);
        } elseif ($user->hasRole('Owner')) {
            $checklists = Checklist::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['property', 'user', 'tasks'])->paginate(10);
        } else {
            // Housekeeper - only their checklists
            $checklists = Checklist::where('user_id', $user->id)->with(['property', 'tasks'])->paginate(10);
        }

        return view('checklists.index', compact('checklists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Get properties based on user role
        if ($user->hasRole('Admin')) {
            $properties = Property::all();
        } elseif ($user->hasRole('Owner')) {
            $properties = Property::where('user_id', $user->id)->get();
        } else {
            $properties = collect();
        }

        $housekeepers = User::role('Housekeeper')->get();

        return view('checklists.create', compact('properties', 'housekeepers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'user_id' => 'required|exists:users,id',
            'assignment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:1000',
            'task_ids' => 'nullable|array',
            'task_ids.*' => 'exists:tasks,id',
        ]);

        $checklist = Checklist::create([
            'property_id' => $validated['property_id'],
            'user_id' => $validated['user_id'],
            'assignment_date' => $validated['assignment_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        // Attach selected tasks
        if (!empty($validated['task_ids'])) {
            $checklist->tasks()->attach($validated['task_ids']);
        }

        return redirect()
            ->route('checklists.index')
            ->with('success', 'Checklist created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Checklist $checklist): View
    {
        $checklist->load([
            'property.rooms.tasks',
            'user',
            'tasks.room',
        ]);

        return view('checklists.show', compact('checklist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checklist $checklist): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Get properties based on user role
        if ($user->hasRole('Admin')) {
            $properties = Property::all();
        } elseif ($user->hasRole('Owner')) {
            $properties = Property::where('user_id', $user->id)->get();
        } else {
            $properties = collect();
        }

        $housekeepers = User::role('Housekeeper')->get();

        // Load the property with its rooms and tasks
        $checklist->load(['property.rooms.tasks', 'tasks']);

        return view('checklists.edit', compact('checklist', 'properties', 'housekeepers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Checklist $checklist): RedirectResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'user_id' => 'required|exists:users,id',
            'assignment_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            'task_ids' => 'nullable|array',
            'task_ids.*' => 'exists:tasks,id',
        ]);

        $checklist->update([
            'property_id' => $validated['property_id'],
            'user_id' => $validated['user_id'],
            'assignment_date' => $validated['assignment_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Sync tasks
        if (isset($validated['task_ids'])) {
            $checklist->tasks()->sync($validated['task_ids']);
        }

        return redirect()
            ->route('checklists.index')
            ->with('success', 'Checklist updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checklist $checklist): RedirectResponse
    {
        $checklist->delete();

        return redirect()
            ->route('checklists.index')
            ->with('success', 'Checklist deleted successfully!');
    }

    /**
     * Start a checklist (Housekeeper workflow)
     */
    public function start(Checklist $checklist): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Verify the housekeeper is assigned to this checklist
        if ($user->hasRole('Housekeeper') && $checklist->user_id !== $user->id) {
            abort(403, 'You are not assigned to this checklist.');
        }

        // Get all rooms and tasks for this property
        $property = $checklist->property;

        // Load checklist tasks
        $checklist->load('tasks');

        $rooms = $property->rooms()->with('tasks')->get();

        return view('checklists.start', compact('checklist', 'property', 'rooms'));
    }

    /**
     * Show room-specific tasks for housekeeper to complete
     */
    public function showRoomTasks(Checklist $checklist, Room $room): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Verify the housekeeper is assigned to this checklist
        if ($user->hasRole('Housekeeper') && $checklist->user_id !== $user->id) {
            abort(403, 'You are not assigned to this checklist.');
        }

        // Verify the room belongs to the checklist's property
        if ($room->property_id !== $checklist->property_id) {
            abort(404, 'Room not found for this property.');
        }

        // Get all tasks for this room
        $tasks = $room->tasks;

        // Load checklist with tasks and pivot data
        $checklist->load('tasks');

        return view('checklists.room-tasks', compact('checklist', 'room', 'tasks'));
    }

    /**
     * Store task completion data
     */
    public function storeTasks(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        $validated = $request->validate([
            'checklist_id' => 'required|exists:checklists,id',
            'tasks' => 'required|array',
            'tasks.*.task_id' => 'required|exists:tasks,id',
            'tasks.*.completed' => 'nullable|boolean',
            'tasks.*.notes' => 'nullable|string|max:1000',
            'tasks.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tasks.*.latitude' => 'nullable|numeric|between:-90,90',
            'tasks.*.longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $checklist = Checklist::findOrFail($validated['checklist_id']);

        // Verify the housekeeper is assigned to this checklist
        if ($user->hasRole('Housekeeper') && $checklist->user_id !== $user->id) {
            abort(403, 'You are not assigned to this checklist.');
        }

        $completedTasks = 0;
        $totalTasks = count($validated['tasks']);

        foreach ($validated['tasks'] as $taskData) {
            $taskId = $taskData['task_id'];
            $isCompleted = isset($taskData['completed']) && $taskData['completed'];

            $pivotData = [
                'notes' => $taskData['notes'] ?? null,
                'latitude' => $taskData['latitude'] ?? null,
                'longitude' => $taskData['longitude'] ?? null,
            ];

            if ($isCompleted) {
                $pivotData['completed'] = true;
                $pivotData['completed_at'] = now();
                $completedTasks++;

                // Handle image upload for completed tasks
                if ($request->hasFile("tasks.{$taskId}.photo")) {
                    $imagePath = $request->file("tasks.{$taskId}.photo")->store('checklist-images', 'public');
                    $pivotData['photo'] = $imagePath;
                }
            } else {
                $pivotData['completed'] = false;
                $pivotData['completed_at'] = null;
            }

            // Update the pivot table
            $checklist->tasks()->updateExistingPivot($taskId, $pivotData);
        }

        $message = "Room progress saved! Completed {$completedTasks} of {$totalTasks} tasks.";

        // Handle AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'completed_tasks' => $completedTasks,
                'total_tasks' => $totalTasks
            ]);
        }

        // Handle regular form submissions
        return redirect()
            ->route('checklists.start', $checklist)
            ->with('success', $message);
    }
}
