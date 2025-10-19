<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage = (int) ($request->get('per_page') ?? 10);

        // Filter properties based on user role
        if ($user->hasRole('Admin')) {
            // Admins can see all properties
            $properties = Property::with('rooms', 'user')->paginate($perPage);
        } elseif ($user->hasRole('Owner')) {
            // Owners can only see their own properties
            $properties = Property::with('rooms')->where('user_id', $user->id)->paginate($perPage);
        } else {
            // Other roles see no properties
            $properties = collect()->paginate($perPage);
        }

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'beds' => 'required|numeric|min:1',
            'baths' => 'required|numeric|min:1',
        ]);

        // Assign property to the current user (Owner)
        $validated['user_id'] = auth()->id();

        Property::create($validated);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Property created successfully!');
    }

    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'beds' => 'required|numeric|min:1',
            'baths' => 'required|numeric|min:1',
        ]);

        $property->update($validated);

        return redirect()
            ->route('properties.index')
            ->with('success', 'Property updated successfully!');
    }

    public function show(Property $property)
    {
        return view('properties.show', compact('property'));
    }
}
