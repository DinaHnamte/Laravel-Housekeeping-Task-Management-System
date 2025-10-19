@extends("layouts.sidebar")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Role Details: {{ $role->name }}
                </h1>
                <p class="mt-2 text-slate-600">View role information, permissions, and assigned users</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route("roles.edit", $role) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-orange-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-orange-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Role
                </a>
                <a href="{{ route("roles.index") }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Roles
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Role Information -->
            <div class="lg:col-span-2">
                <div
                    class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <div class="p-6">
                        <h2 class="mb-6 text-xl font-semibold text-slate-900">Role Information</h2>

                        <div class="space-y-4">
                            <!-- Role Name -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-500">Role Name</label>
                                <div class="flex items-center space-x-3">
                                    @if ($role->name === "Admin")
                                        <span
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </span>
                                    @elseif ($role->name === "Owner")
                                        <span
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </span>
                                    @elseif ($role->name === "Housekeeper")
                                        <span
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </span>
                                    @endif
                                    <div>
                                        <p class="text-lg font-semibold text-slate-900">{{ $role->name }}</p>
                                        @if (in_array($role->name, ["Admin", "Owner", "Housekeeper"]))
                                            <p class="text-sm text-slate-500">System Role</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Created Date -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-500">Created</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $role->created_at->format("M d, Y g:i A") }}</p>
                            </div>

                            <!-- Last Updated -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-500">Last Updated</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $role->updated_at->format("M d, Y g:i A") }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div
                    class="group relative mt-6 overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <div class="p-6">
                        <h2 class="mb-6 text-xl font-semibold text-slate-900">Permissions
                            ({{ $role->permissions->count() }})</h2>

                        @if ($role->permissions->count() > 0)
                            <div class="space-y-4">
                                @foreach ($role->permissions->groupBy(function ($permission) {
            $parts = explode(":", $permission->name);
            return $parts[0] ?? "Other";
        }) as $category => $permissions)
                                    <div>
                                        <h3 class="mb-2 text-sm font-semibold capitalize text-slate-700">{{ $category }}
                                        </h3>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($permissions as $permission)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900">No permissions assigned</h3>
                                <p class="mt-1 text-sm text-slate-500">This role doesn't have any permissions yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assigned Users -->
            <div class="lg:col-span-1">
                <div
                    class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <div class="p-6">
                        <h2 class="mb-6 text-xl font-semibold text-slate-900">Assigned Users ({{ $users->count() }})</h2>

                        @if ($users->count() > 0)
                            <div class="space-y-3">
                                @foreach ($users as $user)
                                    <div
                                        class="flex items-center space-x-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <div class="flex-shrink-0">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200">
                                                <span
                                                    class="text-sm font-medium text-slate-600">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-medium text-slate-900">{{ $user->name }}</p>
                                            <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900">No users assigned</h3>
                                <p class="mt-1 text-sm text-slate-500">No users have this role assigned.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
