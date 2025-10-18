<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
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

        // Filter assignments based on user role
        if ($user->hasRole('Admin')) {
            $assignments = Assignment::with(['property', 'user'])->paginate(10);
        } elseif ($user->hasRole('Owner')) {
            $assignments = Assignment::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['property', 'user'])->paginate(10);
        } else {
            // Housekeeper - only their assignments
            $assignments = Assignment::where('user_id', $user->id)->with('property')->paginate(10);
        }

        return view('assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $properties = Property::all();
        $housekeepers = User::role('Housekeeper')->get();

        return view('assignments.create', compact('properties', 'housekeepers'));
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
        ]);

        Assignment::create($validated);

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Assignment created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment): View
    {
        $assignment->load(['property', 'user']);
        return view('assignments.show', compact('assignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment): View
    {
        $properties = Property::all();
        $housekeepers = User::role('Housekeeper')->get();

        return view('assignments.edit', compact('assignment', 'properties', 'housekeepers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'user_id' => 'required|exists:users,id',
            'assignment_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $assignment->update($validated);

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Assignment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment): RedirectResponse
    {
        $assignment->delete();

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Assignment deleted successfully!');
    }
}
