@extends("layouts.sidebar")

@section("title", "Dashboard")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                Welcome back, {{ auth()->user()->name }}!
            </h1>
            <p class="mt-2 text-sm text-slate-600">
                @if (auth()->user()->role === "Admin")
                    Manage the entire housekeeping system
                @elseif(auth()->user()->role === "Owner")
                    Manage your properties and housekeeping staff
                @else
                    Complete your assigned cleaning tasks
                @endif
            </p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $key => $value)
                <div
                    class="rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100">
                                @switch($key)
                                    @case("total_properties")
                                    @case("my_properties")
                                        <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    @break

                                    @case("total_users")
                                    @case("housekeepers")
                                        <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                    @break

                                    @case("total_assignments")
                                    @case("my_assignments")

                                    @case("pending_assignments")
                                        <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @break

                                    @case("completed_checklists")
                                    @case("completed_cleanings")

                                    @case("completed_tasks")
                                        <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @break

                                    @case("pending_tasks")
                                        <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @break

                                    @case("total_tasks")
                                        <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    @break

                                    @default
                                        <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    @endswitch
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-slate-600">
                                {{ ucwords(str_replace("_", " ", $key)) }}
                            </p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $value }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Recent Activities -->
        <div class="grid gap-6 lg:grid-cols-2">
            @if (isset($recentActivities["recent_properties"]) || isset($recentActivities["my_recent_properties"]))
                <div
                    class="rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Recent Properties</h3>
                    <div class="space-y-3">
                        @foreach ($recentActivities["recent_properties"] ?? ($recentActivities["my_recent_properties"] ?? []) as $property)
                            <div class="flex items-center justify-between rounded-lg bg-slate-50 p-3">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $property->name }}</p>
                                    <p class="text-sm text-slate-500">{{ Str::limit($property->address, 50) ?? 'No address' }}</p>
                                </div>
                                <a href="{{ route("properties.show", $property) }}"
                                    class="text-slate-600 hover:text-slate-900">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (isset($recentActivities["recent_assignments"]) || isset($recentActivities["my_assignments"]))
                <div
                    class="rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Recent Checklists</h3>
                    <div class="space-y-3">
                        @foreach ($recentActivities["recent_assignments"] ?? ($recentActivities["my_assignments"] ?? []) as $checklist)
                            <div class="flex items-center justify-between rounded-lg bg-slate-50 p-3">
                                <div>
                                    <p class="font-medium text-slate-900">
                                        {{ $checklist->property->name ?? "Unknown Property" }}</p>
                                    <p class="text-sm text-slate-500">
                                        {{ $checklist->assignment_date->format("M d, Y") }}
                                        @if ($checklist->user)
                                            - {{ $checklist->user->name }}
                                        @endif
                                    </p>
                                </div>
                                <span
                                    class="@if ($checklist->status === "completed") bg-green-100 text-green-800
                                    @elseif($checklist->status === "in_progress") bg-blue-100 text-blue-800
                                    @elseif($checklist->status === "pending") bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                    {{ ucfirst($checklist->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm">
            <h3 class="mb-4 text-lg font-semibold text-slate-900">Quick Actions</h3>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @if (auth()->user()->role === "Admin" || auth()->user()->role === "Owner")
                    <a href="{{ route("properties.create") }}"
                        class="flex items-center rounded-lg border border-slate-200 p-4 transition-colors hover:bg-slate-50">
                        <svg class="mr-3 h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="font-medium text-slate-900">Add Property</span>
                    </a>
                @endif

                @if (auth()->user()->role === "Admin")
                    <a href="{{ route("users.create") }}"
                        class="flex items-center rounded-lg border border-slate-200 p-4 transition-colors hover:bg-slate-50">
                        <svg class="mr-3 h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <span class="font-medium text-slate-900">Add User</span>
                    </a>
                @endif

                @if (auth()->user()->role === "Admin" || auth()->user()->role === "Owner")
                    <a href="{{ route("checklists.create") }}"
                        class="flex items-center rounded-lg border border-slate-200 p-4 transition-colors hover:bg-slate-50">
                        <svg class="mr-3 h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium text-slate-900">Create Checklist</span>
                    </a>
                @endif

                @if (auth()->user()->hasRole("Housekeeper"))
                    <a href="{{ route("checklists.index") }}"
                        class="flex items-center rounded-lg border border-slate-200 p-4 transition-colors hover:bg-slate-50">
                        <svg class="mr-3 h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="font-medium text-slate-900">My Checklists</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
