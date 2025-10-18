<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Checklist;
use App\Models\Property;
use App\Models\Room;
use App\Models\Task;
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
            $checklists = Checklist::with(['property', 'room', 'task', 'user'])
                ->latest()
                ->paginate(10);
        } elseif ($user->hasRole('Owner')) {
            $checklists = Checklist::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['property', 'room', 'task', 'user'])
                ->latest()
                ->paginate(10);
        } else {
            // Housekeeper - only their checklists
            $checklists = Checklist::where('user_id', $user->id)
                ->with(['property', 'room', 'task'])
                ->latest()
                ->paginate(10);
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

        if ($user->hasRole('Housekeeper')) {
            // Housekeepers can only create checklists for their assignments
            $assignments = Assignment::where('user_id', $user->id)
                ->where('status', 'pending')
                ->with('property.rooms.tasks')
                ->get();
        } else {
            // Admin and Owner can create checklists for any property
            $assignments = Assignment::with('property.rooms.tasks')->get();
        }

        return view('checklists.create', compact('assignments'));
    }

    /**
     * Start a checklist for a specific assignment (Housekeeper workflow)
     */
    public function startFromAssignment(Assignment $assignment): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Verify the housekeeper is assigned to this assignment
        if ($user->hasRole('Housekeeper') && $assignment->user_id !== $user->id) {
            abort(403, 'You are not assigned to this property.');
        }

        // Get all rooms and tasks for this property
        $property = $assignment->property;
        $rooms = $property->rooms()->with('tasks')->get();

        return view('checklists.housekeeper-start', compact('assignment', 'property', 'rooms'));
    }

    /**
     * Show room-specific tasks for housekeeper to complete
     */
    public function showRoomTasks(Assignment $assignment, Room $room): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Verify the housekeeper is assigned to this assignment
        if ($user->hasRole('Housekeeper') && $assignment->user_id !== $user->id) {
            abort(403, 'You are not assigned to this property.');
        }

        // Verify the room belongs to the assignment's property
        if ($room->property_id !== $assignment->property_id) {
            abort(404, 'Room not found for this property.');
        }

        // Get all tasks for this room
        $tasks = $room->tasks;

        // Get existing checklists for this room and assignment
        $existingChecklists = Checklist::where('property_id', $assignment->property_id)
            ->where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('task_id');

        return view('checklists.room-tasks', compact('assignment', 'room', 'tasks', 'existingChecklists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'tasks' => 'required|array',
            'tasks.*.task_id' => 'required|exists:tasks,id',
            'tasks.*.completed' => 'nullable|boolean',
            'tasks.*.notes' => 'nullable|string|max:1000',
            'tasks.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tasks.*.latitude' => 'nullable|numeric|between:-90,90',
            'tasks.*.longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $assignment = Assignment::findOrFail($validated['assignment_id']);

        // Verify the housekeeper is assigned to this assignment
        if ($user->hasRole('Housekeeper') && $assignment->user_id !== $user->id) {
            abort(403, 'You are not assigned to this property.');
        }

        $completedTasks = 0;
        $totalTasks = count($validated['tasks']);

        foreach ($validated['tasks'] as $taskData) {
            $task = Task::findOrFail($taskData['task_id']);
            $isCompleted = isset($taskData['completed']) && $taskData['completed'];

            // Check if checklist already exists for this task
            $existingChecklist = Checklist::where('task_id', $task->id)
                ->where('property_id', $assignment->property_id)
                ->where('user_id', $user->id)
                ->first();

            $checklistData = [
                'task_id' => $task->id,
                'property_id' => $assignment->property_id,
                'room_id' => $task->room_id,
                'user_id' => $user->id,
                'notes' => $taskData['notes'] ?? null,
                'latitude' => $taskData['latitude'] ?? null,
                'longitude' => $taskData['longitude'] ?? null,
            ];

            if ($isCompleted) {
                $checklistData['checked_off'] = true;
                $checklistData['time_date_stamp_start'] = $existingChecklist ? $existingChecklist->time_date_stamp_start : now();
                $checklistData['time_date_stamp_end'] = now();
                $completedTasks++;

                // Handle image upload for completed tasks
                if ($request->hasFile("tasks.{$task->id}.photo")) {
                    $imagePath = $request->file("tasks.{$task->id}.photo")->store('checklist-images', 'public');
                    $checklistData['image_link'] = $imagePath;
                } elseif ($existingChecklist && $existingChecklist->image_link) {
                    $checklistData['image_link'] = $existingChecklist->image_link;
                }
            } else {
                $checklistData['checked_off'] = false;
                $checklistData['time_date_stamp_start'] = $existingChecklist ? $existingChecklist->time_date_stamp_start : now();
                $checklistData['time_date_stamp_end'] = null;
            }

            if ($existingChecklist) {
                $existingChecklist->update($checklistData);
            } else {
                Checklist::create($checklistData);
            }
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
            ->route('checklists.start', $assignment)
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Checklist $checklist): View
    {
        $checklist->load(['property', 'room', 'task', 'user']);
        return view('checklists.show', compact('checklist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checklist $checklist): View
    {
        $checklist->load(['property', 'room', 'task', 'user']);
        return view('checklists.edit', compact('checklist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Checklist $checklist): RedirectResponse
    {
        $validated = $request->validate([
            'checked_off' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $updateData = [
            'notes' => $validated['notes'] ?? $checklist->notes,
            'latitude' => $validated['latitude'] ?? $checklist->latitude,
            'longitude' => $validated['longitude'] ?? $checklist->longitude,
        ];

        // Handle checkbox completion
        if ($request->has('checked_off')) {
            $updateData['checked_off'] = true;
            $updateData['time_date_stamp_end'] = now();
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($checklist->image_link) {
                Storage::disk('public')->delete($checklist->image_link);
            }

            $imagePath = $request->file('image')->store('checklist-images', 'public');
            $updateData['image_link'] = $imagePath;
        }

        $checklist->update($updateData);

        return redirect()
            ->route('checklists.index')
            ->with('success', 'Checklist updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checklist $checklist): RedirectResponse
    {
        // Delete associated image if exists
        if ($checklist->image_link) {
            Storage::disk('public')->delete($checklist->image_link);
        }

        $checklist->delete();

        return redirect()
            ->route('checklists.index')
            ->with('success', 'Checklist deleted successfully!');
    }
}
