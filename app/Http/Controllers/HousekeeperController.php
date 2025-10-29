<?php

namespace App\Http\Controllers;

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

        // Get housekeeper's checklists (assignments)
        $assignments = Checklist::where('user_id', $user->id)
            ->with(['property', 'tasks'])
            ->orderBy('assignment_date', 'desc')
            ->get();

        // Get today's assignments
        $todayAssignments = $assignments->filter(function($checklist) {
            return $checklist->assignment_date && $checklist->assignment_date->isToday();
        });

        // Get pending assignments (upcoming, not yet started)
        $pendingAssignments = $assignments->filter(function($checklist) {
            return $checklist->status === 'pending' || ($checklist->assignment_date && $checklist->assignment_date->isFuture());
        });

        // Get completed assignments
        $completedChecklists = $assignments->where('status', 'completed');

        // Get housekeeper's checklist progress
        $totalTasks = 0;
        $completedTasks = 0;

        foreach ($assignments as $checklist) {
            $checklist->load('tasks');
            $totalTasks += $checklist->tasks->count();
            $completedTasks += $checklist->completedTasks()->count();
        }

        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        // For recent progress table, we'll get checklist tasks with their room info
        $recentChecklists = Checklist::where('user_id', $user->id)
            ->with(['property', 'tasks.room'])
            ->orderBy('assignment_date', 'desc')
            ->limit(10)
            ->get();

        // Set checklists variable for backward compatibility
        $checklists = $recentChecklists;

        return view('housekeeper.dashboard', compact(
            'checklists', // For backward compatibility in view
            'assignments',
            'todayAssignments',
            'pendingAssignments',
            'completedChecklists',
            'totalTasks',
            'completedTasks',
            'progressPercentage',
            'recentChecklists'
        ));
    }

    /**
     * Show housekeeper's tasks for a specific checklist
     */
    public function showTasks(Checklist $checklist): View
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('Housekeeper')) {
            abort(403, 'Access denied. Housekeeper role required.');
        }

        // Verify the housekeeper is assigned to this checklist
        if ($checklist->user_id !== $user->id) {
            abort(403, 'You are not assigned to this property.');
        }

        // Get all rooms and tasks for this property
        $property = $checklist->property;
        $rooms = $property->rooms()->with('tasks')->get();

        // Load checklist with tasks and pivot data
        $checklist->load('tasks');

        return view('housekeeper.tasks', compact('checklist', 'property', 'rooms'));
    }
}
