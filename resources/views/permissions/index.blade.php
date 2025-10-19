@extends("layouts.sidebar")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Permission Management
                </h1>
                <p class="mt-2 text-slate-600">Manage system permissions and their assignments</p>
            </div>
            <a href="{{ route("permissions.create") }}"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-blue-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Permission
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if (session("success"))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                <div class="flex items-center">
                    <svg class="mr-3 h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-medium text-green-800">{{ session("success") }}</p>
                </div>
            </div>
        @endif

        @if (session("error"))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                <div class="flex items-center">
                    <svg class="mr-3 h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-medium text-red-800">{{ session("error") }}</p>
                </div>
            </div>
        @endif

        <!-- Permissions by Category -->
        @foreach ($permissions as $category => $categoryPermissions)
            <div
                class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                <div class="p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-xl font-semibold capitalize text-slate-900">{{ $category }} Permissions</h2>
                        <span
                            class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-800">
                            {{ $categoryPermissions->count() }}
                            permission{{ $categoryPermissions->count() !== 1 ? "s" : "" }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($categoryPermissions as $permission)
                            <div
                                class="rounded-lg border border-slate-200 bg-slate-50 p-4 transition-colors duration-200 hover:bg-slate-100">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-slate-900">{{ $permission->name }}</h3>
                                        <p class="mt-1 text-xs text-slate-500">
                                            Assigned to {{ $permission->roles->count() }}
                                            role{{ $permission->roles->count() !== 1 ? "s" : "" }}
                                        </p>
                                        @if ($permission->roles->count() > 0)
                                            <div class="mt-2 flex flex-wrap gap-1">
                                                @foreach ($permission->roles->take(2) as $role)
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                                @if ($permission->roles->count() > 2)
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-slate-200 px-2 py-1 text-xs font-medium text-slate-600">
                                                        +{{ $permission->roles->count() - 2 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex items-center space-x-1">
                                        <a href="{{ route("permissions.show", $permission) }}"
                                            class="inline-flex h-6 w-6 items-center justify-center rounded bg-blue-100 text-blue-600 transition-colors duration-200 hover:bg-blue-200">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route("permissions.edit", $permission) }}"
                                            class="inline-flex h-6 w-6 items-center justify-center rounded bg-slate-100 text-slate-600 transition-colors duration-200 hover:bg-slate-200">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if ($permission->roles->count() === 0)
                                            <form action="{{ route("permissions.destroy", $permission) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit"
                                                    class="inline-flex h-6 w-6 items-center justify-center rounded bg-red-100 text-red-600 transition-colors duration-200 hover:bg-red-200"
                                                    onclick="return confirm('Are you sure you want to delete this permission?')">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        @if ($permissions->isEmpty())
            <div
                class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                <div class="p-6">
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No permissions found</h3>
                        <p class="mt-1 text-sm text-slate-500">Start by creating your first permission.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
