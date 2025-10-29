@extends("layouts.sidebar")

@section("title", "My Cleaning Dashboard")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    My Cleaning Dashboard</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Welcome back, <span class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>!
                    <span class="text-slate-500">{{ now()->format("l, F j, Y") }}</span>
                </p>
            </div>
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                <svg class="h-8 w-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>

        <!-- Progress Overview -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                <div class="text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ $completedTasks }}</div>
                    <div class="text-sm text-slate-500">Tasks Completed</div>
                    <div class="mt-2 text-xs text-green-600">Great job!</div>
                </div>
            </div>

            <div
                class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                <div class="text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-orange-100">
                        <svg class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ $totalTasks - $completedTasks }}</div>
                    <div class="text-sm text-slate-500">Tasks Remaining</div>
                    <div class="mt-2 text-xs text-orange-600">Keep going!</div>
                </div>
            </div>

            <div
                class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                <div class="text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ $progressPercentage }}%</div>
                    <div class="text-sm text-slate-500">Overall Progress</div>
                    <div class="mt-2 text-xs text-blue-600">Excellent work!</div>
                </div>
            </div>
        </div>

        <!-- Today's Assignments -->
        @if ($todayAssignments->count() > 0)
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-slate-900">Today's Tasks</h2>
                    <span
                        class="inline-flex items-center rounded-full bg-orange-100 px-3 py-1 text-sm font-medium text-orange-800">
                        {{ $todayAssignments->count() }} assignments
                    </span>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($todayAssignments as $assignment)
                        <div
                            class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                            <div class="mb-4">
                                <h3 class="truncate text-lg font-semibold text-slate-900">{{ $assignment->property->name }}
                                </h3>
                                <p class="text-sm text-slate-500">Today's Assignment</p>
                            </div>

                            <div class="mb-4 flex items-center space-x-2">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-slate-600">
                                    @if ($assignment->start_time)
                                        @if (is_string($assignment->start_time))
                                            {{ \Carbon\Carbon::parse($assignment->start_time)->format("g:i A") }}
                                        @else
                                            {{ $assignment->start_time->format("g:i A") }}
                                        @endif
                                        @if ($assignment->end_time)
                                            -
                                            @if (is_string($assignment->end_time))
                                                {{ \Carbon\Carbon::parse($assignment->end_time)->format("g:i A") }}
                                            @else
                                                {{ $assignment->end_time->format("g:i A") }}
                                            @endif
                                        @endif
                                    @else
                                        No time set
                                    @endif
                                </span>
                            </div>

                            <a href="{{ route("checklists.start", $assignment) }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Start Tasks
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Pending Assignments -->
        @if ($pendingAssignments->count() > 0)
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-slate-900">Upcoming Tasks</h2>
                    <span
                        class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
                        {{ $pendingAssignments->count() }} assignments
                    </span>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($pendingAssignments as $assignment)
                        <div
                            class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                            <div class="mb-4">
                                <h3 class="truncate text-lg font-semibold text-slate-900">{{ $assignment->property->name }}
                                </h3>
                                <p class="text-sm text-slate-500">Upcoming Assignment</p>
                            </div>

                            <div class="mb-4 flex items-center space-x-2">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm text-slate-600">
                                    {{ $assignment->assignment_date ? $assignment->assignment_date->format("M d, Y") : "No date set" }}
                                    @if ($assignment->start_time)
                                        at
                                        @if (is_string($assignment->start_time))
                                            {{ \Carbon\Carbon::parse($assignment->start_time)->format("g:i A") }}
                                        @else
                                            {{ $assignment->start_time->format("g:i A") }}
                                        @endif
                                    @endif
                                </span>
                            </div>

                            <a href="{{ route("checklists.start", $assignment) }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Tasks
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Progress -->
        @if ($recentChecklists && $recentChecklists->count() > 0)
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-slate-900">Recent Progress</h2>

                <div
                    class="overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                        Property</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                        Tasks</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                        Progress</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach ($recentChecklists as $checklist)
                                    <tr class="hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100">
                                                    <svg class="h-4 w-4 text-slate-600" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-slate-900">
                                                        {{ $checklist->property->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                            {{ $checklist->assignment_date ? $checklist->assignment_date->format("M d, Y") : "-" }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-900">
                                            {{ $checklist->tasks->count() }} tasks
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if ($checklist->status === "completed")
                                                <span
                                                    class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Completed
                                                </span>
                                            @elseif ($checklist->status === "in_progress")
                                                <span
                                                    class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    In Progress
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                            @php
                                                $completed = $checklist->completedTasks()->count();
                                                $total = $checklist->tasks->count();
                                                $percent = $total > 0 ? round(($completed / $total) * 100) : 0;
                                            @endphp
                                            {{ $completed }}/{{ $total }} ({{ $percent }}%)
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- No assignments message -->
        @if ($assignments->count() === 0)
            <div class="py-12 text-center">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-100">
                    <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-slate-900">No assignments yet</h3>
                <p class="mt-2 text-sm text-slate-500">You don't have any cleaning assignments at the moment.</p>
                <div class="mt-4">
                    <span
                        class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-600">
                        <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Check back later for new tasks
                    </span>
                </div>
            </div>
        @endif
    </div>
@endsection
