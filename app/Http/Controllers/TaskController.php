<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Property $property, Room $room): View
    {
        $tasks = $room->tasks()->paginate(10);
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
        ]);

        $validated['property_id'] = $property->id;
        $validated['room_id'] = $room->id;
        $validated['is_default'] = $request->has('is_default');

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
        return view('properties.rooms.tasks.show', compact('property', 'room', 'task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property, Room $room, Task $task): View
    {
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
        ]);

        $validated['is_default'] = $request->has('is_default');

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
        $task->delete();

        return redirect()
            ->route('properties.rooms.tasks.index', [$property, $room])
            ->with('success', 'Task deleted successfully!');
    }
}
