@extends("layouts.sidebar")

@section("title", "Checklist Details")

@section("sidebar-content")
    <div class="mx-auto max-w-4xl">
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                Checklist Details
            </h1>
            <p class="mt-2 text-sm text-slate-600">View assignment information and status</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Checklist Information Card -->
            <div class="lg:col-span-2">
                <div
                    class="rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                    <h2 class="mb-6 text-xl font-semibold text-slate-900">Checklist Information</h2>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Property</span>
                            <span
                                class="text-lg font-medium text-slate-900">{{ $checklist->property->name ?? "Unknown Property" }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Housekeeper</span>
                            <span
                                class="text-lg font-medium text-slate-900">{{ $checklist->user->name ?? "No housekeeper assigned" }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Checklist Date</span>
                            <span
                                class="text-lg font-medium text-slate-900">{{ $checklist->assignment_date->format("M d, Y") }}</span>
                        </div>
                        @if ($checklist->start_time)
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <span class="text-sm font-semibold text-slate-600">Time</span>
                                <span class="text-lg font-medium text-slate-900">
                                    {{ $checklist->start_time->format("g:i A") }}
                                    @if ($checklist->end_time)
                                        - {{ $checklist->end_time->format("g:i A") }}
                                    @endif
                                </span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Status</span>
                            <span
                                class="@if ($checklist->status === "completed") bg-green-100 text-green-800
                                @elseif($checklist->status === "in_progress") bg-blue-100 text-blue-800
                                @elseif($checklist->status === "pending") bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                {{ ucfirst($checklist->status) }}
                            </span>
                        </div>
                        @if ($checklist->notes)
                            <div class="flex items-start justify-between">
                                <span class="text-sm font-semibold text-slate-600">Notes</span>
                                <span
                                    class="max-w-md text-right text-lg font-medium text-slate-900">{{ $checklist->notes }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="lg:col-span-1">
                <div
                    class="rounded-2xl border border-slate-200/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Actions</h3>
                    <div class="space-y-3">
                        @if (auth()->user()->role === "Admin" || auth()->user()->role === "Owner")
                            <a href="{{ route("checklists.edit", $checklist) }}"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-700 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-800">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Checklist
                            </a>
                        @endif
                        <a href="{{ route("checklists.index") }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Checklists
                        </a>
                        @if ($checklist->property)
                            <a href="{{ route("properties.show", $checklist->property) }}"
                                class="flex w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                View Property
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Progress Section -->
        @if ($checklist->tasks->count() > 0)
            <div
                class="mt-6 rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                <h2 class="mb-6 text-xl font-semibold text-slate-900">Task Progress</h2>

                @php
                    $totalTasks = $checklist->tasks->count();
                    $completedTasks = $checklist->completedTasks()->count();
                    $progressPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                @endphp

                <div class="mb-6">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Overall Progress</span>
                        <span class="text-sm font-semibold text-slate-900">{{ round($progressPercentage) }}%</span>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-slate-200">
                        <div class="h-full bg-gradient-to-r from-slate-600 to-slate-700 transition-all duration-500"
                            style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <p class="mt-2 text-sm text-slate-600">
                        {{ $completedTasks }} of {{ $totalTasks }} tasks completed
                    </p>
                </div>

                <!-- Group tasks by room -->
                @php
                    $tasksByRoom = $checklist->tasks->groupBy("room_id");
                @endphp

                @foreach ($tasksByRoom as $roomId => $tasks)
                    @php
                        $room = $tasks->first()->room ?? null;
                        if ($room) {
                            $roomCompletedTasks = $tasks
                                ->filter(function ($task) {
                                    return $task->pivot->completed;
                                })
                                ->count();
                        }
                    @endphp

                    @if ($room)
                        <div class="mb-4 rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-slate-900">{{ $room->name }}</h3>
                                <span class="text-sm font-medium text-slate-600">
                                    {{ $roomCompletedTasks }} / {{ $tasks->count() }} tasks
                                </span>
                            </div>
                            <div class="space-y-2">
                                @foreach ($tasks as $task)
                                    @php
                                        $isCompleted = $task->pivot->completed;
                                    @endphp
                                    <div
                                        class="flex items-center justify-between rounded-lg border border-slate-200 bg-white p-3">
                                        <div class="flex items-center space-x-2">
                                            @if ($isCompleted)
                                                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            @endif
                                            <span
                                                class="@if ($isCompleted) line-through @endif text-sm text-slate-700">{{ $task->task }}</span>
                                        </div>
                                        @if ($isCompleted && $task->pivot->completed_at)
                                            <span class="text-xs text-slate-500">
                                                Completed: {{ $task->pivot->completed_at->format("M d, g:i A") }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div
                class="mt-6 rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                <h2 class="mb-4 text-xl font-semibold text-slate-900">Task Progress</h2>
                <p class="text-sm text-slate-600">No specific tasks were assigned for this property. The housekeeper will
                    complete all available tasks.</p>
            </div>
        @endif
    </div>
@endsection
