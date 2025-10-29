@extends("layouts.sidebar")

@section("title", "Checklist Summary")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Checklist Summary</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Property: {{ $checklist->property->name }} |
                    Date: {{ $checklist->assignment_date->format("M d, Y") }} |
                    Status: <span class="font-semibold">{{ ucfirst($checklist->status) }}</span>
                </p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid gap-6 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Tasks</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ $checklist->tasks->count() }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Completed Tasks</p>
                        <p class="mt-1 text-2xl font-bold text-green-600">
                            {{ $checklist->completedTasks()->count() }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Photos Uploaded</p>
                        <p class="mt-1 text-2xl font-bold text-purple-600">
                            {{ $checklist->roomPhotos->count() }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks by Room -->
        <div class="rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50">
            <h2 class="mb-4 text-xl font-semibold text-slate-900">Tasks by Room</h2>
            <div class="space-y-4">
                @foreach ($checklist->property->rooms as $room)
                    @php
                        $roomTasks = $checklist->tasks->filter(function ($task) use ($room) {
                            return $task->room_id === $room->id;
                        });
                        $completedRoomTasks = $roomTasks->filter(function ($task) {
                            return $task->pivot->completed ?? false;
                        });
                    @endphp

                    @if ($roomTasks->count() > 0)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <div class="mb-2 flex items-center justify-between">
                                <h3 class="font-semibold text-slate-900">{{ $room->name }}</h3>
                                <span class="text-sm text-slate-600">
                                    {{ $completedRoomTasks->count() }}/{{ $roomTasks->count() }} completed
                                </span>
                            </div>
                            <div class="space-y-2">
                                @foreach ($roomTasks as $task)
                                    <div class="flex items-center justify-between rounded bg-slate-50 p-2">
                                        <span
                                            class="{{ $task->pivot->completed ?? false ? "text-green-700" : "text-slate-700" }} text-sm">
                                            {{ $task->task }}
                                        </span>
                                        @if ($task->pivot->completed ?? false)
                                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <span class="text-xs text-slate-500">Pending</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Photos -->
        @if ($checklist->roomPhotos->count() > 0)
            <div class="rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50">
                <h2 class="mb-4 text-xl font-semibold text-slate-900">Uploaded Photos</h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    @foreach ($checklist->roomPhotos->groupBy("room_id") as $roomId => $photos)
                        <div>
                            <p class="mb-2 text-sm font-medium text-slate-700">
                                {{ $photos->first()->room->name }} ({{ $photos->count() }})
                            </p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($photos->take(4) as $photo)
                                    <img src="{{ asset("storage/" . $photo->photo_path) }}"
                                        alt="Photo {{ $photo->photo_number }}"
                                        class="h-20 w-full rounded-lg border border-slate-200 object-cover">
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div
            class="flex items-center justify-between rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50">
            <a href="{{ route("checklists.start", $checklist) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-all duration-200 hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Checklist
            </a>

            @if ($checklist->workflow_stage !== "completed")
                <form action="{{ route("checklists.next-stage", $checklist) }}" method="POST">
                    @csrf
                    <input type="hidden" name="stage" value="completed">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-2 text-sm font-semibold text-white transition-all duration-200 hover:bg-green-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Mark as Completed
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
