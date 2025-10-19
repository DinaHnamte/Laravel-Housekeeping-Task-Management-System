@extends("layouts.app")

@section("content_full")
    <div class="flex min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-72 md:flex-col">
            <div
                class="flex flex-grow flex-col overflow-y-auto border-r border-gray-200/50 bg-white/80 shadow-xl backdrop-blur-sm">
                <div class="flex flex-shrink-0 items-center border-b border-gray-100 px-6 py-6">
                    <div class="flex items-center space-x-3">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-purple-600">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h2
                            class="bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-xl font-bold text-transparent">
                            Dashboard</h2>
                    </div>
                </div>
                <div class="mt-6 flex flex-grow flex-col">
                    <nav class="flex-1 space-y-2 px-4 pb-6">
                        <!-- Dashboard -->
                        @if (auth()->user()->hasRole("Housekeeper"))
                            <a href="{{ route("housekeeper.dashboard") }}"
                                class="{{ request()->routeIs("housekeeper.*") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                                </svg>
                                My Dashboard
                            </a>
                        @else
                            <a href="{{ route("properties.index") }}"
                                class="{{ request()->routeIs("properties.index") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                                </svg>
                                Dashboard
                            </a>
                        @endif

                        @if (auth()->user()->hasRole("Admin") || auth()->user()->hasRole("Owner"))
                            <!-- Properties Section -->
                            <div class="space-y-2">
                                <div
                                    class="rounded-lg bg-gray-50/50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                                    Properties
                                </div>
                                <a href="{{ route("properties.index") }}"
                                    class="{{ request()->routeIs("properties.*") && !request()->routeIs("properties.rooms.*") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    All Properties
                                </a>
                                <a href="{{ route("properties.create") }}"
                                    class="{{ request()->routeIs("properties.create") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Property
                                </a>
                            </div>

                            <!-- Rooms Section -->
                            <div class="space-y-2">
                                <div
                                    class="rounded-lg bg-gray-50/50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                                    Rooms
                                </div>
                                @if (isset($property) && $property)
                                    <a href="{{ route("properties.rooms.index", $property) }}"
                                        class="{{ request()->routeIs("properties.rooms.*") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                        </svg>
                                        {{ $property->name }} Rooms
                                    </a>
                                    <a href="{{ route("properties.rooms.create", $property) }}"
                                        class="{{ request()->routeIs("properties.rooms.create") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Add Room
                                    </a>
                                @else
                                    <div class="rounded-xl bg-gray-50/30 px-4 py-3 text-sm text-gray-500">
                                        Select a property to manage rooms
                                    </div>
                                @endif
                            </div>

                            <!-- Users Section -->
                            <div class="space-y-2">
                                <div
                                    class="rounded-lg bg-gray-50/50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                                    Users
                                </div>
                                <a href="{{ route("users.index") }}"
                                    class="{{ request()->routeIs("users.*") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                    All Users
                                </a>
                                <a href="{{ route("users.create") }}"
                                    class="{{ request()->routeIs("users.create") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add User
                                </a>
                            </div>

                            <!-- Role Management Section (Admin only) -->
                            @if (auth()->user()->hasRole("Admin"))
                                <div class="space-y-2">
                                    <div
                                        class="rounded-lg bg-gray-50/50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                                        Role Management
                                    </div>
                                    <a href="{{ route("roles.index") }}"
                                        class="{{ request()->routeIs("roles.*") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Roles
                                    </a>
                                    <a href="{{ route("permissions.index") }}"
                                        class="{{ request()->routeIs("permissions.*") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Permissions
                                    </a>
                                </div>
                            @endif
                        @endif

                        @if (!auth()->user()->hasRole("Housekeeper"))
                            <!-- Assignments Section -->
                            <div class="space-y-2">
                                <div
                                    class="rounded-lg bg-gray-50/50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                                    Assignments
                                </div>
                                <a href="{{ route("assignments.index") }}"
                                    class="{{ request()->routeIs("assignments.*") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    All Assignments
                                </a>
                                <a href="{{ route("assignments.create") }}"
                                    class="{{ request()->routeIs("assignments.create") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Create Assignment
                                </a>
                            </div>

                            <!-- Checklists Section -->
                            <div class="space-y-2">
                                <div
                                    class="rounded-lg bg-gray-50/50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-400">
                                    Checklists
                                </div>
                                <a href="{{ route("checklists.index") }}"
                                    class="{{ request()->routeIs("checklists.*") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    All Checklists
                                </a>
                                <a href="{{ route("checklists.create") }}"
                                    class="{{ request()->routeIs("checklists.create") ? "bg-slate-100 text-slate-900 border-l-4 border-slate-600" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900" }} group flex items-center rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200">
                                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Start Checklist
                                </a>
                            </div>
                        @endif
                    </nav>
                </div>
            </div>
        </div>

        <!-- Mobile sidebar overlay -->
        <div id="mobile-sidebar" class="fixed inset-0 z-40 hidden md:hidden">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" onclick="toggleMobileSidebar()"></div>
            <div class="relative flex w-full max-w-xs flex-1 flex-col bg-white">
                <div class="absolute right-0 top-0 -mr-12 pt-2">
                    <button onclick="toggleMobileSidebar()"
                        class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="h-0 flex-1 overflow-y-auto pb-4 pt-5">
                    <div class="flex flex-shrink-0 items-center px-4">
                        <h2 class="text-lg font-semibold text-gray-900">Navigation</h2>
                    </div>
                    <nav class="mt-5 space-y-1 px-2">
                        <!-- Same navigation items as desktop sidebar -->
                        <a href="{{ route("properties.index") }}"
                            class="{{ request()->routeIs("properties.index") ? "bg-indigo-100 text-indigo-900" : "text-gray-600 hover:bg-gray-50 hover:text-gray-900" }} group flex items-center rounded-md px-2 py-2 text-base font-medium">
                            <svg class="mr-4 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            </svg>
                            Dashboard
                        </a>
                        <!-- Add other navigation items here -->
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main content area -->
        <div class="flex w-0 flex-1 flex-col overflow-hidden">
            <!-- Mobile menu button -->
            <div class="pl-1 pt-1 sm:pl-3 sm:pt-3 md:hidden">
                <button onclick="toggleMobileSidebar()"
                    class="-ml-0.5 -mt-0.5 inline-flex h-12 items-center justify-center rounded-xl text-gray-500 transition-all duration-200 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Page content -->
            <main class="relative flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-blue-50 focus:outline-none">
                <div class="py-8">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
                        @yield("sidebar-content")
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
@endsection
