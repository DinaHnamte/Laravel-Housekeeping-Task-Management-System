<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HousekeeperController extends Controller
{
    /**
     * Show housekeeper dashboard with tasks and progress
     */
    public function dashboard(): View
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('Housekeeper')) {
            abort(403, 'Access denied. Housekeeper role required.');
        }

        // Get housekeeper's assignments
        $assignments = Assignment::where('user_id', $user->id)
            ->with('property')
            ->orderBy('assignment_date', 'desc')
            ->get();

        // Get today's assignments
        $todayAssignments = $assignments->where('assignment_date', today());

        // Get pending assignments
        $pendingAssignments = $assignments->where('status', 'pending');

        // Get completed assignments
        $completedAssignments = $assignments->where('status', 'completed');

        // Get housekeeper's checklist progress
        $checklists = Checklist::where('user_id', $user->id)
            ->with(['property', 'room', 'task'])
            ->latest()
            ->limit(10)
            ->get();

        // Calculate progress statistics
        $totalTasks = 0;
        $completedTasks = 0;

        foreach ($assignments as $assignment) {
            $property = $assignment->property;
            $rooms = $property->rooms()->with('tasks')->get();

            foreach ($rooms as $room) {
                $totalTasks += $room->tasks->count();

                // Count completed tasks for this room
                $completedTasks += Checklist::where('user_id', $user->id)
                    ->where('property_id', $assignment->property_id)
                    ->where('room_id', $room->id)
                    ->where('checked_off', true)
                    ->count();
            }
        }

        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        return view('housekeeper.dashboard', compact(
            'assignments',
            'todayAssignments',
            'pendingAssignments',
            'completedAssignments',
            'checklists',
            'totalTasks',
            'completedTasks',
            'progressPercentage'
        ));
    }

    /**
     * Show housekeeper's tasks for a specific assignment
     */
    public function showTasks(Assignment $assignment): View
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('Housekeeper')) {
            abort(403, 'Access denied. Housekeeper role required.');
        }

        // Verify the housekeeper is assigned to this assignment
        if ($assignment->user_id !== $user->id) {
            abort(403, 'You are not assigned to this property.');
        }

        // Get all rooms and tasks for this property
        $property = $assignment->property;
        $rooms = $property->rooms()->with('tasks')->get();

        // Get existing checklists for this assignment
        $existingChecklists = Checklist::where('user_id', $user->id)
            ->where('property_id', $assignment->property_id)
            ->get()
            ->groupBy('room_id');

        return view('housekeeper.tasks', compact('assignment', 'property', 'rooms', 'existingChecklists'));
    }
}
