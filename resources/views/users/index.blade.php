@extends("layouts.sidebar")

@section("title", "User Management")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-3xl font-bold text-transparent">User
                    Management</h1>
                <p class="mt-2 text-sm text-gray-600">Manage system users and their roles</p>
            </div>
            <a href="{{ route("users.create") }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-6 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New User
            </a>
        </div>

        <!-- Users Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($users as $user)
                <div
                    class="group relative overflow-hidden rounded-2xl border border-gray-200/50 bg-white/80 p-6 shadow-lg shadow-gray-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-gray-300/50">
                    <!-- User Avatar -->
                    <div class="mb-4 flex items-center space-x-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-600 text-lg font-bold text-white">
                            {{ strtoupper(substr($user->name ?? "U", 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-lg font-semibold text-gray-900">{{ $user->name ?? "Unknown" }}</h3>
                            <p class="truncate text-sm text-gray-500">{{ $user->email ?? "No email" }}</p>
                        </div>
                    </div>

                    <!-- User Details -->
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="text-sm text-gray-600">{{ $user->phone ?? "No phone" }}</span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div
                                class="@if ($user->role === "Admin") bg-red-100 text-red-800
                                @elseif($user->role === "Owner") bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                {{ $user->role ?? "No role" }}
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex space-x-2">
                        <a href="{{ route("users.edit", $user) }}"
                            class="flex-1 rounded-lg bg-gray-100 px-3 py-2 text-center text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200">
                            Edit
                        </a>
                        <a href="{{ route("users.show", $user) }}"
                            class="flex-1 rounded-lg bg-slate-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-slate-700">
                            View
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No users</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new user.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="flex justify-center">
                <div class="rounded-xl bg-white/80 p-4 shadow-lg shadow-gray-200/50 backdrop-blur-sm">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
