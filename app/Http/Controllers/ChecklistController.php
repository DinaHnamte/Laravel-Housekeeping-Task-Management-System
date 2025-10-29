<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Property;
use App\Models\Room;
use App\Models\RoomPhoto;
use App\Models\User;
use App\Services\GpsVerificationService;
use App\Services\ImageProcessingService;
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
     * Start a checklist (Housekeeper workflow) - requires GPS verification
     */
    public function start(Checklist $checklist, Request $request): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Verify the housekeeper is assigned to this checklist
        if ($user->hasRole('Housekeeper') && $checklist->user_id !== $user->id) {
            abort(403, 'You are not assigned to this checklist.');
        }

        $property = $checklist->property;

        // Check GPS verification requirement
        $gpsVerificationRequired = $property->latitude && $property->longitude;
        $gpsVerified = $checklist->gps_verified_at !== null;

        // If GPS verification is required but not done, show verification page
        if ($gpsVerificationRequired && !$gpsVerified) {
            return view('checklists.verify-gps', compact('checklist', 'property'));
        }

        // Get all rooms and tasks for this property
        $property->load(['rooms.tasks']);

        // Load checklist tasks and workflow stage
        $checklist->load(['tasks', 'roomPhotos']);

        $rooms = $property->rooms;

        return view('checklists.start', compact('checklist', 'property', 'rooms'));
    }

    /**
     * Verify GPS location before accessing checklist
     */
    public function verifyGps(Request $request, Checklist $checklist): RedirectResponse
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $property = $checklist->property;
        $gpsService = new GpsVerificationService();

        $verification = $gpsService->verifyLocation(
            $property,
            $validated['latitude'],
            $validated['longitude']
        );

        if ($verification['verified']) {
            // Save verified GPS coordinates
            $checklist->update([
                'verified_latitude' => $validated['latitude'],
                'verified_longitude' => $validated['longitude'],
                'gps_verified_at' => now(),
                'status' => 'in_progress',
                'start_time' => now(),
            ]);

            return redirect()
                ->route('checklists.start', $checklist)
                ->with('success', $verification['message']);
        }

        return redirect()
            ->back()
            ->withErrors(['gps' => $verification['message']])
            ->withInput();
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

        // Verify GPS if required
        $property = $checklist->property;
        if ($property->latitude && $property->longitude && !$checklist->gps_verified_at) {
            abort(403, 'Please verify your GPS location first.');
        }

        // Verify the room belongs to the checklist's property
        if ($room->property_id !== $checklist->property_id) {
            abort(404, 'Room not found for this property.');
        }

        // Ensure workflow is at room checklist stage
        if ($checklist->workflow_stage === 'photo_upload' || $checklist->workflow_stage === 'summary' || $checklist->workflow_stage === 'completed') {
            // Allow viewing but might redirect based on stage
        }

        // Get all tasks for this room
        $tasks = $room->tasks;

        // Load checklist with tasks and pivot data
        $checklist->load(['tasks', 'roomPhotos' => function($query) use ($room) {
            $query->where('room_id', $room->id);
        }]);

        // Get existing photos for this room (up to 8)
        $existingPhotos = $checklist->roomPhotos->where('room_id', $room->id)->sortBy('photo_number');

        return view('checklists.room-tasks', compact('checklist', 'room', 'tasks', 'existingPhotos'));
    }

    /**
     * Store task completion data (Room Checklist Stage)
     */
    public function storeTasks(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        $validated = $request->validate([
            'checklist_id' => 'required|exists:checklists,id',
            'room_id' => 'required|exists:rooms,id',
            'tasks' => 'required|array',
            'tasks.*.task_id' => 'required|exists:tasks,id',
            'tasks.*.completed' => 'nullable|boolean',
            'tasks.*.notes' => 'nullable|string|max:1000',
            'tasks.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tasks.*.latitude' => 'nullable|numeric|between:-90,90',
            'tasks.*.longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $checklist = Checklist::findOrFail($validated['checklist_id']);
        $room = Room::findOrFail($validated['room_id']);

        // Verify the housekeeper is assigned to this checklist
        if ($user->hasRole('Housekeeper') && $checklist->user_id !== $user->id) {
            abort(403, 'You are not assigned to this checklist.');
        }

        $imageService = new ImageProcessingService();
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

                // Handle image upload for completed tasks with timestamp overlay
                if ($request->hasFile("tasks.{$taskId}.photo")) {
                    $file = $request->file("tasks.{$taskId}.photo");
                    try {
                        $imagePath = $imageService->processAndStore($file, 'checklist-images');
                        $pivotData['photo'] = $imagePath;
                    } catch (\Exception $e) {
                        // Fallback to simple storage if image processing fails
                        $imagePath = $file->store('checklist-images', 'public');
                        $pivotData['photo'] = $imagePath;
                    }
                }
            } else {
                $pivotData['completed'] = false;
                $pivotData['completed_at'] = null;
            }

            // Update the pivot table
            $checklist->tasks()->updateExistingPivot($taskId, $pivotData);
        }

        // Update workflow stage if all tasks in room are completed
        // This will be handled by checking all rooms when moving to next stage

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
            ->route('checklists.room', ['checklist' => $checklist->id, 'room' => $room->id])
            ->with('success', $message);
    }

    /**
     * Upload photos for a room (Photo Upload Stage - 8 photos per room)
     */
    public function uploadRoomPhotos(Request $request, Checklist $checklist, Room $room)
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        if ($user->hasRole('Housekeeper') && $checklist->user_id !== $user->id) {
            abort(403, 'You are not assigned to this checklist.');
        }

        $validated = $request->validate([
            'photos' => 'required|array|max:8',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $imageService = new ImageProcessingService();
        $capturedAt = now();

        // Get existing photo count for this room
        $existingCount = RoomPhoto::where('checklist_id', $checklist->id)
            ->where('room_id', $room->id)
            ->count();

        if ($existingCount + count($validated['photos']) > 8) {
            return redirect()
                ->back()
                ->withErrors(['photos' => 'Maximum 8 photos allowed per room.']);
        }

        foreach ($validated['photos'] as $index => $photo) {
            try {
                $imagePath = $imageService->processAndStore($photo, 'room-photos', $capturedAt->format('Y-m-d H:i:s'));

                RoomPhoto::create([
                    'checklist_id' => $checklist->id,
                    'room_id' => $room->id,
                    'photo_path' => $imagePath,
                    'original_filename' => $photo->getClientOriginalName(),
                    'latitude' => $validated['latitude'] ?? null,
                    'longitude' => $validated['longitude'] ?? null,
                    'captured_at' => $capturedAt,
                    'photo_number' => $existingCount + $index + 1,
                ]);
            } catch (\Exception $e) {
                // Fallback to simple storage
                $imagePath = $photo->store('room-photos', 'public');
                RoomPhoto::create([
                    'checklist_id' => $checklist->id,
                    'room_id' => $room->id,
                    'photo_path' => $imagePath,
                    'original_filename' => $photo->getClientOriginalName(),
                    'latitude' => $validated['latitude'] ?? null,
                    'longitude' => $validated['longitude'] ?? null,
                    'captured_at' => $capturedAt,
                    'photo_number' => $existingCount + $index + 1,
                ]);
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Photos uploaded successfully!');
    }

    /**
     * Move to next workflow stage
     */
    public function nextStage(Request $request, Checklist $checklist)
    {
        $validated = $request->validate([
            'stage' => 'required|in:inventory_checklist,photo_upload,summary,completed',
        ]);

        $currentStage = $checklist->workflow_stage;

        // Validate stage transitions
        $allowedTransitions = [
            'room_checklist' => ['inventory_checklist'],
            'inventory_checklist' => ['photo_upload'],
            'photo_upload' => ['summary'],
            'summary' => ['completed'],
        ];

        if (!isset($allowedTransitions[$currentStage]) || !in_array($validated['stage'], $allowedTransitions[$currentStage])) {
            return redirect()
                ->back()
                ->withErrors(['stage' => 'Invalid workflow stage transition.']);
        }

        $checklist->update([
            'workflow_stage' => $validated['stage'],
            'status' => $validated['stage'] === 'completed' ? 'completed' : 'in_progress',
            'end_time' => $validated['stage'] === 'completed' ? now() : null,
        ]);

        $stageNames = [
            'inventory_checklist' => 'Inventory Checklist',
            'photo_upload' => 'Photo Upload',
            'summary' => 'Summary',
            'completed' => 'Completed',
        ];

        return redirect()
            ->back()
            ->with('success', 'Moved to ' . ($stageNames[$validated['stage']] ?? $validated['stage']) . ' stage.');
    }

    /**
     * Show summary/review page
     */
    public function summary(Checklist $checklist): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        if ($user->hasRole('Housekeeper') && $checklist->user_id !== $user->id) {
            abort(403, 'You are not assigned to this checklist.');
        }

        $checklist->load([
            'property.rooms',
            'tasks.room',
            'roomPhotos.room',
            'user'
        ]);

        return view('checklists.summary', compact('checklist'));
    }
}

