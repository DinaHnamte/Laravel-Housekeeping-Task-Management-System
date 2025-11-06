<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use App\Models\Task;
use App\Models\Image;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Property $property, Room $room): View
    {
        $tasks = $room->tasks()->with('image')->paginate(10);
        return view('properties.rooms.tasks.index', compact('property', 'room', 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Property $property, Room $room): View
    {
        return view('properties.rooms.tasks.create', compact('property', 'room'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Property $property, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
            'task_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['property_id'] = $property->id;
        $validated['room_id'] = $room->id;
        $validated['is_default'] = $request->has('is_default');

        // Handle task image upload
        if ($request->hasFile('task_image')) {
            $image = $request->file('task_image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images/tasks', $filename, 'public');

            $imageRecord = Image::create([
                'uri' => $path,
                'name' => $image->getClientOriginalName(),
            ]);

            $validated['image_id'] = $imageRecord->id;
        }

        $room->tasks()->create($validated);

        return redirect()
            ->route('properties.rooms.tasks.index', [$property, $room])
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property, Room $room, Task $task): View
    {
        $task->load('image');
        return view('properties.rooms.tasks.show', compact('property', 'room', 'task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property, Room $room, Task $task): View
    {
        $task->load('image');
        return view('properties.rooms.tasks.edit', compact('property', 'room', 'task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property, Room $room, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
            'task_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['is_default'] = $request->has('is_default');

        // Handle task image upload
        if ($request->hasFile('task_image')) {
            // Delete old image if exists
            if ($task->image_id) {
                $oldImage = Image::find($task->image_id);
                if ($oldImage && Storage::disk('public')->exists($oldImage->uri)) {
                    Storage::disk('public')->delete($oldImage->uri);
                    $oldImage->delete();
                }
            }

            $image = $request->file('task_image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('images/tasks', $filename, 'public');

            $imageRecord = Image::create([
                'uri' => $path,
                'name' => $image->getClientOriginalName(),
            ]);

            $validated['image_id'] = $imageRecord->id;
        }

        $task->update($validated);

        return redirect()
            ->route('properties.rooms.tasks.index', [$property, $room])
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property, Room $room, Task $task): RedirectResponse
    {
        // Check if user is Owner and can only delete tasks from their own properties
        $user = auth()->user();
        if ($user->hasRole('Owner') && $property->user_id !== $user->id) {
            return redirect()
                ->route('properties.rooms.tasks.index', [$property, $room])
                ->with('error', 'You can only delete tasks from your own properties.');
        }

        $task->delete();

        return redirect()
            ->route('properties.rooms.tasks.index', [$property, $room])
            ->with('success', 'Task deleted successfully!');
    }
}
