<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) ($request->get('per_page') ?? 10);
        $properties = Property::with('rooms')->paginate($perPage);
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
