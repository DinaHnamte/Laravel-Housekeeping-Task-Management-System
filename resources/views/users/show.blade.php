@extends("layouts.sidebar")

@section("title", "User Details")

@section("sidebar-content")
    <div class="mx-auto max-w-4xl">
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">User
                Details</h1>
            <p class="mt-2 text-sm text-slate-600">View and manage user information</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- User Information Card -->
            <div class="lg:col-span-2">
                <div
                    class="rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                    <div class="mb-6 flex items-center space-x-4">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-600 text-xl font-bold text-white">
                            {{ strtoupper(substr($user->name ?? "U", 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">{{ $user->name ?? "Unknown User" }}</h2>
                            <p class="text-sm text-slate-500">{{ $user->email ?? "No email" }}</p>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Full Name</span>
                            <span class="text-lg font-medium text-slate-900">{{ $user->name ?? "Not provided" }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Email Address</span>
                            <span class="text-lg font-medium text-slate-900">{{ $user->email ?? "Not provided" }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Phone Number</span>
                            <span class="text-lg font-medium text-slate-900">{{ $user->phone ?? "Not provided" }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-600">User Role</span>
                            <div
                                class="@if ($user->role === "Admin") bg-red-100 text-red-800
                                @elseif($user->role === "Owner") bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                {{ $user->role ?? "No role assigned" }}
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
                        <a href="{{ route("users.edit", $user) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-700 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit User
                        </a>
                        <a href="{{ route("users.index") }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Users
                        </a>
                        @can('delete:users')
                            <form action="{{ route("users.destroy", $user) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-red-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete User
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
