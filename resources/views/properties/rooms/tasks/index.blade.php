@extends("layouts.sidebar")

@section("title", "Room Tasks")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    {{ $room->name }} Tasks
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    Manage tasks for {{ $room->name }} in {{ $property->name }}
                </p>
            </div>
            <a href="{{ route("properties.rooms.tasks.create", [$property, $room]) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-6 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Task
            </a>
        </div>

        <!-- Tasks Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($tasks as $task)
                <div
                    class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <!-- Task Header -->
                    <div class="mb-4">
                        <h3 class="truncate text-lg font-semibold text-slate-900">{{ $task->task }}</h3>
                        <p class="text-sm text-slate-500">Task Details</p>
                    </div>

                    <!-- Task Status -->
                    <div class="mb-4">
                        <div class="flex items-center space-x-2">
                            @if ($task->is_default)
                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Default Task
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">
                                    Custom Task
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route("properties.rooms.tasks.edit", [$property, $room, $task]) }}"
                            class="flex-1 rounded-lg bg-slate-100 px-3 py-2 text-center text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200">
                            Edit
                        </a>
                        <a href="{{ route("properties.rooms.tasks.show", [$property, $room, $task]) }}"
                            class="flex-1 rounded-lg bg-slate-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-slate-700">
                            View
                        </a>
                        <form action="{{ route("properties.rooms.tasks.destroy", [$property, $room, $task]) }}"
                            method="POST" class="flex-1">
                            @csrf
                            @method("DELETE")
                            <button type="submit"
                                class="w-full rounded-lg bg-red-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-red-700"
                                onclick="return confirm('Are you sure you want to delete this task?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No tasks</h3>
                        <p class="mt-1 text-sm text-slate-500">Get started by creating a new task for this room.</p>
                        <div class="mt-6">
                            <a href="{{ route("properties.rooms.tasks.create", [$property, $room]) }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add First Task
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($tasks->hasPages())
            <div class="flex justify-center">
                <div class="rounded-xl bg-white/80 p-4 shadow-lg shadow-slate-200/50 backdrop-blur-sm">
                    {{ $tasks->links() }}
                </div>
            </div>
        @endif

        <!-- Back Navigation -->
        <div class="flex justify-start">
            <a href="{{ route("properties.rooms.index", $property) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Rooms
            </a>
        </div>
    </div>
@endsection
