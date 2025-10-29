<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Property;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Get role-based statistics
        $stats = $this->getUserStats($user);

        // Get recent activities based on
        $recentActivities = $this->getRecentActivities($user);

        return view('dashboard', compact('stats', 'recentActivities'));
    }

    private function getUserStats($user)
    {
        if ($user->hasRole('Admin')) {
            return [
                'total_properties' => Property::count(),
                'total_users' => User::count(),
                'total_checklists' => Checklist::count(),
                'completed_tasks' => Checklist::whereHas('tasks', function($q) {
                    $q->where('checklist_tasks.completed', true);
                })->count(),
                'pending_checklists' => Checklist::where('status', 'pending')->count(),
            ];
        } elseif ($user->hasRole('Owner')) {
            return [
                'my_properties' => Property::where('user_id', $user->id)->count(),
                'my_checklists' => Checklist::whereHas('property', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->count(),
                'completed_cleanings' => Checklist::whereHas('property', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->whereHas('tasks', function($q) {
                    $q->where('checklist_tasks.completed', true);
                })->count(),
                'housekeepers' => User::role('Housekeeper')->count(),
            ];
        } elseif ($user->hasRole('Housekeeper')) {
            return [
                'my_checklists' => Checklist::where('user_id', $user->id)->count(),
                'completed_tasks' => Checklist::where('user_id', $user->id)->whereHas('tasks', function($q) {
                    $q->where('checklist_tasks.completed', true);
                })->count(),
                'pending_tasks' => Checklist::where('user_id', $user->id)->where('status', 'pending')->count(),
                'total_tasks' => Task::count(),
            ];
        }

        return [];
    }

    private function getRecentActivities($user)
    {
        if ($user->hasRole('Admin')) {
            return [
                'recent_properties' => Property::latest()->take(5)->get(),
                'recent_checklists' => Checklist::with(['property', 'user'])->latest()->take(5)->get(),
                'recent_completed_tasks' => Checklist::with(['property', 'user', 'tasks' => function($q) {
                    $q->where('checklist_tasks.completed', true);
                }])->latest()->take(5)->get(),
            ];
        } elseif ($user->hasRole('Owner')) {
            return [
                'my_recent_properties' => Property::where('user_id', $user->id)->latest()->take(5)->get(),
                'recent_checklists' => Checklist::whereHas('property', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->with(['property', 'user'])->latest()->take(5)->get(),
            ];
        } elseif ($user->hasRole('Housekeeper')) {
            return [
                'my_checklists' => Checklist::where('user_id', $user->id)->with('property')->latest()->take(5)->get(),
                'recent_completed_tasks' => Checklist::where('user_id', $user->id)->with(['property', 'tasks' => function($q) {
                    $q->where('checklist_tasks.completed', true);
                }])->latest()->take(5)->get(),
            ];
        }

        return [];
    }
}
