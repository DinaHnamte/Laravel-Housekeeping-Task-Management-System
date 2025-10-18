@extends("layouts.sidebar")

@section("title", "Cleaning Tasks - " . $property->name)

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    {{ $property->name }} - Cleaning Tasks</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Assignment Date: {{ $assignment->assignment_date->format("M d, Y") }}
                    @if ($assignment->start_time)
                        | Time: {{ $assignment->start_time->format("g:i A") }}
                        @if ($assignment->end_time)
                            - {{ $assignment->end_time->format("g:i A") }}
                        @endif
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
        <!-- Instructions -->
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
                    <p class="mt-1 text-sm text-blue-700">Complete each room's tasks in order. Upload photos for each
                        completed task.</p>
                </div>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($rooms as $room)
                @php
                    $roomChecklists = $existingChecklists->get($room->id, collect());
                    $completedTasks = $roomChecklists->where("checked_off", true)->count();
                    $totalTasks = $room->tasks->count();
                    $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;
                @endphp

                <div
                    class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <!-- Room Header -->
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">{{ $room->name }}</h3>
                            <p class="text-sm text-slate-500">Room Tasks</p>
                        </div>
                        <div class="flex space-x-2">
                            <span
                                class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">
                                {{ $totalTasks }} tasks
                            </span>
                            @if ($completedTasks > 0)
                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                    {{ $completedTasks }} done
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @if ($totalTasks > 0)
                        <div class="mb-4">
                            <div class="mb-1 flex justify-between text-sm text-slate-600">
                                <span>Progress</span>
                                <span>{{ $progressPercentage }}%</span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-slate-200">
                                <div class="h-2 rounded-full bg-green-500 transition-all duration-300"
                                    style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Task List -->
                    <div class="mb-6">
                        <h4 class="mb-2 text-sm font-medium text-slate-900">Tasks:</h4>
                        <ul class="space-y-1">
                            @foreach ($room->tasks as $task)
                                @php
                                    $taskChecklist = $roomChecklists->where("task_id", $task->id)->first();
                                    $isCompleted = $taskChecklist && $taskChecklist->checked_off;
                                @endphp
                                <li class="flex items-center text-sm">
                                    @if ($isCompleted)
                                        <svg class="mr-2 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-green-700">{{ $task->task }}</span>
                                    @else
                                        <svg class="mr-2 h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-slate-600">{{ $task->task }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route("checklists.room", ["assignment" => $assignment->id, "room" => $room->id]) }}"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @if ($completedTasks > 0)
                            Continue {{ $room->name }} Tasks
                        @else
                            Start {{ $room->name }} Tasks
                        @endif
                    </a>
                </div>
            @endforeach
        </div>

        @if ($rooms->count() === 0)
            <div class="py-12 text-center">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-yellow-100">
                    <svg class="h-12 w-12 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-slate-900">No rooms found</h3>
                <p class="mt-2 text-sm text-slate-500">This property doesn't have any rooms with tasks assigned yet.</p>
            </div>
        @endif

        <!-- Footer Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route("housekeeper.dashboard") }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>

            @if ($rooms->count() > 0)
                <div class="text-sm text-slate-500">
                    <svg class="mr-1 inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Estimated time: {{ $rooms->count() * 15 }} minutes
                </div>
            @endif
        </div>
    </div>
@endsection
