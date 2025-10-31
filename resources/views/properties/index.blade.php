@extends("layouts.sidebar")

@section("title", "Property Management")

@section("sidebar-content")
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1
                    class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-2xl font-bold text-transparent sm:text-3xl">
                    Property Management</h1>
                <p class="mt-1 text-xs text-slate-600 sm:mt-2 sm:text-sm">Manage properties and their room configurations</p>
            </div>
            <a href="{{ route("properties.create") }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-700 px-4 py-2.5 text-sm text-white shadow-lg transition-all duration-200 hover:bg-slate-800 sm:px-6 sm:py-3 sm:font-semibold">
                <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="hidden sm:inline">Add New Property</span>
                <span class="sm:hidden">Add Property</span>
            </a>
        </div>

        <!-- Properties Grid -->
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
            @forelse ($properties as $property)
                <div
                    class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">

                    <!-- Property Header Image -->
                    @if ($property->headerImage)
                        <div class="h-32 overflow-hidden sm:h-48">
                            <img src="{{ asset("storage/" . $property->headerImage->uri) }}" alt="{{ $property->name }}"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </div>
                    @endif

                    <!-- Property Content -->
                    <div class="p-4 sm:p-6">
                        <!-- Property Header -->
                        <div class="mb-3 sm:mb-4">
                            <h3 class="truncate text-lg font-semibold text-slate-900 sm:text-xl">
                                {{ $property->name ?? "Unnamed Property" }}</h3>
                            <p class="text-xs text-slate-500 sm:text-sm">Property Details</p>
                            @if (auth()->user()->hasRole("Admin") && $property->user)
                                <p class="text-xs text-slate-400">Owner: {{ $property->user->name }}</p>
                            @endif
                        </div>

                        <!-- Property Stats -->
                        <div class="mb-4 space-y-2 sm:mb-6">
                            @if ($property->address)
                                <div class="flex items-start gap-2">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-500 sm:h-5 sm:w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span
                                        class="text-xs text-slate-600 sm:text-sm">{{ Str::limit($property->address, 60) }}</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-start gap-4">
                                <div class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span
                                        class="text-xs font-semibold text-slate-900">{{ isset($property->rooms) ? $property->rooms->count() : "0" }}
                                        Rooms</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:space-x-2">
                            <a href="{{ route("properties.show", $property) }}"
                                class="flex-1 rounded-lg bg-slate-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-slate-700">
                                View Details
                            </a>
                            <a href="{{ route("properties.edit", $property) }}"
                                class="flex-1 rounded-lg bg-slate-100 px-3 py-2 text-center text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200">
                                Edit
                            </a>
                            <a href="{{ route("properties.rooms.index", $property) }}"
                                class="flex-1 rounded-lg bg-slate-800 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-slate-900">
                                Manage Rooms
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="py-8 text-center sm:py-12">
                        <svg class="mx-auto h-10 w-10 text-slate-400 sm:h-12 sm:w-12" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No properties</h3>
                        <p class="mt-1 text-xs text-slate-500 sm:text-sm">Get started by creating a new property.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($properties->hasPages())
            <div class="flex justify-center">
                <div class="rounded-xl bg-white/80 p-3 shadow-lg shadow-slate-200/50 backdrop-blur-sm sm:p-4">
                    {{ $properties->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
