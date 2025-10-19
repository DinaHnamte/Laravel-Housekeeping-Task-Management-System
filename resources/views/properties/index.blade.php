@extends("layouts.sidebar")

@section("title", "Property Management")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Property Management</h1>
                <p class="mt-2 text-sm text-slate-600">Manage properties and their room configurations</p>
            </div>
            <a href="{{ route("properties.create") }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-6 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Property
            </a>
        </div>

        <!-- Properties Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($properties as $property)
                <div
                    class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <!-- Property Header -->
                    <div class="mb-4">
                        <h3 class="truncate text-xl font-semibold text-slate-900">
                            {{ $property->name ?? "Unnamed Property" }}</h3>
                        <p class="text-sm text-slate-500">Property Details</p>
                        @if (auth()->user()->hasRole("Admin") && $property->user)
                            <p class="text-xs text-slate-400">Owner: {{ $property->user->name }}</p>
                        @endif
                    </div>

                    <!-- Property Stats -->
                    <div class="mb-6 grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="mx-auto mb-2 flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100">
                                <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-slate-900">{{ $property->beds ?? "0" }}</div>
                            <div class="text-xs text-slate-500">Beds</div>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto mb-2 flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100">
                                <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10.5 3L12 2l1.5 1H21v6H3V3h7.5z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-slate-900">{{ $property->baths ?? "0" }}</div>
                            <div class="text-xs text-slate-500">Baths</div>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto mb-2 flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100">
                                <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-slate-900">
                                {{ isset($property->rooms) ? $property->rooms->count() : "0" }}</div>
                            <div class="text-xs text-slate-500">Rooms</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route("properties.edit", $property) }}"
                            class="flex-1 rounded-lg bg-slate-100 px-3 py-2 text-center text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200">
                            Edit
                        </a>
                        <a href="{{ route("properties.show", $property) }}"
                            class="flex-1 rounded-lg bg-slate-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-slate-700">
                            View
                        </a>
                        <a href="{{ route("properties.rooms.index", $property) }}"
                            class="flex-1 rounded-lg bg-slate-800 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-slate-900">
                            Rooms
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No properties</h3>
                        <p class="mt-1 text-sm text-slate-500">Get started by creating a new property.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($properties->hasPages())
            <div class="flex justify-center">
                <div class="rounded-xl bg-white/80 p-4 shadow-lg shadow-slate-200/50 backdrop-blur-sm">
                    {{ $properties->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
