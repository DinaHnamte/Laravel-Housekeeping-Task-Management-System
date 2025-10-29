@extends("layouts.sidebar")

@section("title", "Start Checklist - " . $property->name)

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Start Checklist - {{ $property->name }}</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Checklist Date:
                    {{ $checklist->assignment_date ? $checklist->assignment_date->format("M d, Y") : "Not set" }}
                    @if ($checklist->workflow_stage)
                        | Stage: <span
                            class="font-semibold capitalize">{{ str_replace("_", " ", $checklist->workflow_stage) }}</span>
                    @endif
                </p>
            </div>
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                <svg class="h-8 w-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>

        <!-- Instructions Alert -->
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
            <div class="flex items-start">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                    <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Instructions</h3>
                    <p class="mt-1 text-sm text-blue-700">Complete each room's tasks in order. Upload photos for each task
                        and mark as complete before moving to the next room.</p>
                </div>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($rooms as $room)
                @php
                    // Filter tasks based on assignment
                    $tasksToShow = $room->tasks;
                    if ($checklist->tasks->count() > 0) {
                        $assignedTaskIds = $checklist->tasks->pluck("id")->toArray();
                        $tasksToShow = $room->tasks->filter(function ($task) use ($assignedTaskIds) {
                            return in_array($task->id, $assignedTaskIds);
                        });
                    }
                @endphp

                @if ($tasksToShow->count() > 0)
                    <div
                        class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100">
                                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $room->name }}</h3>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                {{ $tasksToShow->count() }} {{ $tasksToShow->count() === 1 ? "task" : "tasks" }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <p class="mb-2 text-sm font-medium text-slate-700">Tasks to complete:</p>
                            <ul class="space-y-2">
                                @foreach ($tasksToShow as $task)
                                    <li class="flex items-center space-x-2 text-sm text-slate-600">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $task->task }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route("checklists.room", ["checklist" => $checklist->id, "room" => $room->id]) }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-green-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Start {{ $room->name }} Tasks
                        </a>
                    </div>
                @endif
            @endforeach
        </div>

        @php
            $hasTasksToShow = false;
            foreach ($rooms as $room) {
                if ($checklist->tasks->count() > 0) {
                    $assignedTaskIds = $checklist->tasks->pluck("id")->toArray();
                    $tasksInRoom = $room->tasks->filter(function ($task) use ($assignedTaskIds) {
                        return in_array($task->id, $assignedTaskIds);
                    });
                    if ($tasksInRoom->count() > 0) {
                        $hasTasksToShow = true;
                        break;
                    }
                } else {
                    $hasTasksToShow = true;
                    break;
                }
            }
        @endphp

        @if (!$hasTasksToShow)
            <div class="rounded-xl border border-orange-200 bg-orange-50 p-12 text-center">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-orange-100">
                    <svg class="h-12 w-12 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h5 class="mt-4 text-lg font-semibold text-slate-900">No tasks found</h5>
                <p class="mt-2 text-sm text-slate-600">No tasks are assigned for this property.</p>
            </div>
        @endif

        <!-- Footer Actions -->
        <div
            class="flex items-center justify-between rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50">
            <a href="{{ route("checklists.index") }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-all duration-200 hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Checklists
            </a>

            @if ($rooms->count() > 0)
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Estimated time: {{ $rooms->count() * 15 }} minutes</span>
                </div>
            @endif
        </div>
    </div>
@endsection
