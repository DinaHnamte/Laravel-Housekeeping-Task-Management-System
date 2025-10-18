<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RoomController extends Controller
{
        /**
     * Display a listing of users.
     */
    public function index(Property $property, Request $request): View
    {
        $perPage = (int) ($request->get('per_page') ?? 10); // Cast to int; use get() for safety
        $rooms = $property->rooms()->paginate($perPage);
        $properties = Property::all();
        return view('properties.rooms.index', compact('rooms', 'property', 'properties'));
    }

    public function create(Property $property): View
    {
        $properties = Property::all();
        return view('properties.rooms.create', compact('property', 'properties'));
    }

    /**
     * Store a newly created room in database.
     */
    public function store(Property $property, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);

        $property->rooms()->create($validated);

        return redirect()
            ->route('properties.rooms.index', $property)
            ->with('success', 'Room created successfully!');
    }

    public function show(Property $property, Room $room): View
    {
        return view('properties.rooms.show', compact('property','room'));
    }

    public function edit(Property $property, Room $room): View
    {
        $properties = Property::all();
        return view('properties.rooms.edit', compact('room', 'property', 'properties'));
    }

    public function update(Property $property, Room $room, Request $request)
    {
        $room->update($request->except('role'));

        return redirect()->route('properties.rooms.index', $property)->with('success', 'Room updated successfully!');
    }
}
