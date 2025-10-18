@extends("layouts.sidebar")

@section("title", "Assignments")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Assignments
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    @if (auth()->user()->role === "Admin")
                        Manage all cleaning assignments
                    @elseif(auth()->user()->role === "Owner")
                        Manage your property cleaning assignments
                    @else
                        View your assigned cleaning tasks
                    @endif
                </p>
            </div>
            @if (auth()->user()->role === "Admin" || auth()->user()->role === "Owner")
                <a href="{{ route("assignments.create") }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-6 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create Assignment
                </a>
            @endif
        </div>

        <!-- Assignments Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($assignments as $assignment)
                <div
                    class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <!-- Assignment Header -->
                    <div class="mb-4">
                        <h3 class="truncate text-lg font-semibold text-slate-900">
                            {{ $assignment->property->name ?? "Unknown Property" }}</h3>
                        <p class="text-sm text-slate-500">
                            @if ($assignment->user)
                                Assigned to {{ $assignment->user->name }}
                            @else
                                No housekeeper assigned
                            @endif
                        </p>
                    </div>

                    <!-- Assignment Details -->
                    <div class="mb-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Date:</span>
                            <span
                                class="text-sm font-medium text-slate-900">{{ $assignment->assignment_date->format("M d, Y") }}</span>
                        </div>
                        @if ($assignment->start_time)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">Time:</span>
                                <span class="text-sm font-medium text-slate-900">
                                    {{ $assignment->start_time->format("g:i A") }}
                                    @if ($assignment->end_time)
                                        - {{ $assignment->end_time->format("g:i A") }}
                                    @endif
                                </span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Status:</span>
                            <span
                                class="@if ($assignment->status === "completed") bg-green-100 text-green-800
                                @elseif($assignment->status === "in_progress") bg-blue-100 text-blue-800
                                @elseif($assignment->status === "pending") bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                {{ ucfirst($assignment->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route("assignments.show", $assignment) }}"
                            class="flex-1 rounded-lg bg-slate-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-slate-700">
                            View
                        </a>

                        @if (auth()->user()->role === "Housekeeper" && $assignment->status === "pending")
                            <a href="{{ route("checklists.start", $assignment) }}"
                                class="flex-1 rounded-lg bg-green-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-green-700">
                                Start Checklist
                            </a>
                        @endif

                        @if (auth()->user()->role === "Admin" || auth()->user()->role === "Owner")
                            <a href="{{ route("assignments.edit", $assignment) }}"
                                class="flex-1 rounded-lg bg-slate-100 px-3 py-2 text-center text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200">
                                Edit
                            </a>
                            <form action="{{ route("assignments.destroy", $assignment) }}" method="POST" class="flex-1">
                                @csrf
                                @method("DELETE")
                                <button type="submit"
                                    class="w-full rounded-lg bg-red-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-red-700"
                                    onclick="return confirm('Are you sure you want to delete this assignment?')">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No assignments</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            @if (auth()->user()->role === "Housekeeper")
                                You don't have any assignments yet.
                            @else
                                Get started by creating a new assignment.
                            @endif
                        </p>
                        @if (auth()->user()->role === "Admin" || auth()->user()->role === "Owner")
                            <div class="mt-6">
                                <a href="{{ route("assignments.create") }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Create First Assignment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($assignments->hasPages())
            <div class="flex justify-center">
                <div class="rounded-xl bg-white/80 p-4 shadow-lg shadow-slate-200/50 backdrop-blur-sm">
                    {{ $assignments->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
