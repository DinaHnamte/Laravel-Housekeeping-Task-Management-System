<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display calendar view of checklists/assignments
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        // Get date range for calendar (default: current month)
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Filter checklists based on user role
        if ($user->hasRole('Admin')) {
            $checklists = Checklist::with(['property', 'user'])
                ->whereBetween('assignment_date', [$startDate, $endDate])
                ->get();
        } elseif ($user->hasRole('Owner')) {
            $checklists = Checklist::whereHas('property', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['property', 'user'])
            ->whereBetween('assignment_date', [$startDate, $endDate])
            ->get();
        } else {
            // Housekeeper - only their checklists
            $checklists = Checklist::where('user_id', $user->id)
                ->with(['property'])
                ->whereBetween('assignment_date', [$startDate, $endDate])
                ->get();
        }

        // Group checklists by date
        $checklistsByDate = [];
        foreach ($checklists as $checklist) {
            $dateKey = $checklist->assignment_date->format('Y-m-d');
            if (!isset($checklistsByDate[$dateKey])) {
                $checklistsByDate[$dateKey] = [];
            }
            $checklistsByDate[$dateKey][] = $checklist;
        }

        return view('calendar.index', compact('checklistsByDate', 'year', 'month', 'startDate'));
    }
}

