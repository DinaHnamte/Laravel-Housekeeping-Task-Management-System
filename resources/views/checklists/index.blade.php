@extends("layouts.sidebar")

@section("title", "Checklists")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Checklists
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    @if (auth()->user()->hasRole("Admin"))
                        View all cleaning assignments and progress
                    @elseif(auth()->user()->hasRole("Owner"))
                        View your property cleaning assignments and progress
                    @else
                        View your assigned cleaning tasks
                    @endif
                </p>
            </div>
        </div>

        <!-- Checklists Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($checklists as $checklist)
                <div
                    class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <!-- Checklist Header -->
                    <div class="mb-4">
                        <h3 class="truncate text-lg font-semibold text-slate-900">
                            {{ $checklist->property->name ?? "Unknown Property" }}</h3>
                        <p class="text-sm text-slate-500">
                            @if ($checklist->user)
                                Assigned to {{ $checklist->user->name }}
                            @else
                                No housekeeper assigned
                            @endif
                        </p>
                    </div>

                    <!-- Checklist Details -->
                    <div class="mb-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Date:</span>
                            <span
                                class="text-sm font-medium text-slate-900">{{ $checklist->assignment_date->format("M d, Y") }}</span>
                        </div>
                        @if ($checklist->tasks->count() > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">Tasks:</span>
                                <span class="text-sm font-medium text-slate-900">{{ $checklist->tasks->count() }}
                                    assigned</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Status:</span>
                            <span
                                class="@if ($checklist->status === "completed") bg-green-100 text-green-800
                                @elseif($checklist->status === "in_progress") bg-blue-100 text-blue-800
                                @elseif($checklist->status === "pending") bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                {{ ucfirst($checklist->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route("checklists.show", $checklist) }}"
                            class="flex-1 rounded-lg bg-slate-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-slate-700">
                            View
                        </a>

                        @if (auth()->user()->hasRole("Housekeeper") && $checklist->status === "pending")
                            <a href="{{ route("checklists.start", $checklist) }}"
                                class="flex-1 rounded-lg bg-green-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-green-700">
                                Start Checklist
                            </a>
                        @endif

                        @if (auth()->user()->hasRole("Admin") || auth()->user()->hasRole("Owner"))
                            <a href="{{ route("checklists.edit", $checklist) }}"
                                class="flex-1 rounded-lg bg-slate-100 px-3 py-2 text-center text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200">
                                Edit
                            </a>
                            <form action="{{ route("checklists.destroy", $checklist) }}" method="POST" class="flex-1">
                                @csrf
                                @method("DELETE")
                                <button type="submit"
                                    class="w-full rounded-lg bg-red-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-red-700"
                                    onclick="return confirm('Are you sure you want to delete this checklist?')">
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
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No checklists</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            @if (auth()->user()->hasRole("Housekeeper"))
                                You don't have any checklists yet.
                            @else
                                Get started by creating a new checklist.
                            @endif
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($checklists->hasPages())
            <div class="flex justify-center">
                <div class="rounded-xl bg-white/80 p-4 shadow-lg shadow-slate-200/50 backdrop-blur-sm">
                    {{ $checklists->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
