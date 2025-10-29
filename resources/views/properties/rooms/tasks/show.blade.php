@extends("layouts.sidebar")

@section("title", "Task Details")

@section("sidebar-content")
    <div class="mx-auto max-w-4xl">
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                Task Details
            </h1>
            <p class="mt-2 text-sm text-slate-600">View and manage task information</p>
        </div>

        <!-- Task Image -->
        @if ($task->image)
            <div class="mb-8 overflow-hidden rounded-2xl shadow-xl">
                <img src="{{ asset("storage/" . $task->image->uri) }}" alt="{{ $task->task }}"
                    class="h-64 w-full object-cover sm:h-96">
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Task Information Card -->
            <div class="lg:col-span-2">
                <div
                    class="rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                    <h2 class="mb-6 text-xl font-semibold text-slate-900">Task Information</h2>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Task Description</span>
                            <span class="text-lg font-medium text-slate-900">{{ $task->task }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Room</span>
                            <span class="text-lg font-medium text-slate-900">{{ $room->name }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Property</span>
                            <span class="text-lg font-medium text-slate-900">{{ $property->name }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-600">Default Task</span>
                            <div
                                class="@if ($task->is_default) bg-green-100 text-green-800
                                @else bg-slate-100 text-slate-800 @endif inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                {{ $task->is_default ? "Yes" : "No" }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="lg:col-span-1">
                <div
                    class="rounded-2xl border border-slate-200/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route("properties.rooms.tasks.edit", [$property, $room, $task]) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-700 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Task
                        </a>
                        <a href="{{ route("properties.rooms.tasks.index", [$property, $room]) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Back to Tasks
                        </a>
                        <a href="{{ route("properties.rooms.show", [$property, $room]) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            </svg>
                            View Room
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
