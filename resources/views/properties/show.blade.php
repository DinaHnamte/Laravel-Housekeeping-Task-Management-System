@extends("layouts.sidebar")

@section("title", "Property Details")

@section("sidebar-content")
    <div class="mx-auto max-w-4xl">
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                Property Details</h1>
            <p class="mt-2 text-sm text-slate-600">View and manage property information</p>
        </div>

        <!-- Property Header Image -->
        @if ($property->headerImage)
            <div class="mb-8 overflow-hidden rounded-2xl shadow-xl">
                <img src="{{ asset("storage/" . $property->headerImage->uri) }}" alt="{{ $property->name }}"
                    class="h-64 w-full object-cover sm:h-96">
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Property Information Card -->
            <div class="lg:col-span-2">
                <div
                    class="rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                    <h2 class="mb-6 text-xl font-semibold text-slate-900">Property Information</h2>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Property Name</span>
                            <span
                                class="text-lg font-medium text-slate-900">{{ $property->name ?? "Unnamed Property" }}</span>
                        </div>

                        <!-- Structured Address Display -->
                        <div class="space-y-3 border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Address</span>
                            <div class="space-y-1 text-sm text-slate-900">
                                @if($property->house_number || $property->street)
                                    <div>
                                        @if($property->house_number){{ $property->house_number }}, @endif
                                        {{ $property->street }}
                                    </div>
                                @endif
                                @if($property->neighborhood)
                                    <div class="text-slate-700">{{ $property->neighborhood }}</div>
                                @endif
                                @if($property->suburb)
                                    <div class="text-slate-700">{{ $property->suburb }}</div>
                                @endif
                                @if($property->city)
                                    <div class="text-slate-700 font-medium">{{ $property->city }}</div>
                                @endif
                                @if($property->state || $property->postcode)
                                    <div class="text-slate-700">
                                        {{ $property->state }}@if($property->postcode) {{ $property->postcode }}@endif
                                    </div>
                                @endif
                                @if($property->country)
                                    <div class="text-slate-600 font-semibold">{{ $property->country }}</div>
                                @endif
                            </div>
                        </div>

                        @if ($property->latitude && $property->longitude)
                            <div class="space-y-3 border-b border-slate-100 pb-4">
                                <span class="text-sm font-semibold text-slate-600">Coordinates</span>
                                <div class="flex items-center justify-between">
                                    <span class="text-base font-medium text-slate-900">
                                        {{ number_format($property->latitude, 6) }}, {{ number_format($property->longitude, 6) }}
                                    </span>
                                    <a href="https://www.openstreetmap.org/?mlat={{ $property->latitude }}&mlon={{ $property->longitude }}&zoom=15" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-200">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        View Map
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-600">Total Rooms</span>
                            <span
                                class="text-lg font-medium text-slate-900">{{ isset($property->rooms) ? $property->rooms->count() : "0" }}</span>
                        </div>
                    </div>
                </div>

                <!-- Map Display -->
                @if ($property->latitude && $property->longitude)
                    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200/50 bg-white/80 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                        <div class="h-64 w-full" id="map"></div>
                    </div>
                @endif
            </div>

            <!-- Actions Card -->
            <div class="lg:col-span-1">
                <div
                    class="rounded-2xl border border-slate-200/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route("properties.edit", $property) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-700 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Property
                        </a>
                        <a href="{{ route("properties.rooms.index", $property) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            View Rooms
                        </a>
                        <a href="{{ route("properties.index") }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to List
                        </a>
                        @can('delete:properties')
                            <form action="{{ route("properties.destroy", $property) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this property? This will also delete all rooms, tasks, and checklists associated with it. This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-red-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Property
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Script -->
        @if ($property->latitude && $property->longitude)
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script>
                var map = L.map('map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                
                L.marker([{{ $property->latitude }}, {{ $property->longitude }}])
                    .addTo(map)
                    .bindPopup('{{ $property->name }}')
                    .openPopup();
            </script>
        @endif
    </div>
@endsection
