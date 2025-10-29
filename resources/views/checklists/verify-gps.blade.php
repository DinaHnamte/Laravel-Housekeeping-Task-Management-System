@extends("layouts.sidebar")

@section("title", "Verify Location")

@section("sidebar-content")
    <div class="mx-auto max-w-2xl">
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                Verify Your Location
            </h1>
            <p class="mt-2 text-sm text-slate-600">
                Please verify your GPS location to access the checklist for {{ $property->name }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
            @if ($errors->has("gps"))
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                    <p class="text-sm text-red-800">{{ $errors->first("gps") }}</p>
                </div>
            @endif

            <form action="{{ route("checklists.verify-gps", $checklist) }}" method="POST" id="gpsVerificationForm">
                @csrf

                <div class="mb-6">
                    <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 p-4">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> Your location must be within {{ $property->gps_radius_meters ?? 50 }}
                            meters of the property to proceed.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="mb-2 block text-sm font-semibold text-slate-700">
                                Latitude
                            </label>
                            <input type="number" step="any" id="latitude" name="latitude"
                                value="{{ old("latitude") }}"
                                class="@error("latitude") border-red-500 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 placeholder-slate-500 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                                placeholder="Auto-detected" required>
                            @error("latitude")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="mb-2 block text-sm font-semibold text-slate-700">
                                Longitude
                            </label>
                            <input type="number" step="any" id="longitude" name="longitude"
                                value="{{ old("longitude") }}"
                                class="@error("longitude") border-red-500 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 placeholder-slate-500 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                                placeholder="Auto-detected" required>
                            @error("longitude")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="button" id="getLocationBtn"
                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-all duration-200 hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Get My Location
                    </button>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route("checklists.index") }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-all duration-200 hover:bg-slate-50">
                        Cancel
                    </a>

                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-2 text-sm font-semibold text-white transition-all duration-200 hover:bg-green-700">
                        Verify Location
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const getLocationBtn = document.getElementById('getLocationBtn');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            // Auto-detect location on page load
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    latitudeInput.value = position.coords.latitude;
                    longitudeInput.value = position.coords.longitude;
                }, function(error) {
                    console.error('Error getting location:', error);
                });
            }

            // Manual location detection button
            getLocationBtn.addEventListener('click', function() {
                if (navigator.geolocation) {
                    getLocationBtn.disabled = true;
                    getLocationBtn.innerHTML =
                        '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Getting Location...';

                    navigator.geolocation.getCurrentPosition(function(position) {
                        latitudeInput.value = position.coords.latitude;
                        longitudeInput.value = position.coords.longitude;
                        getLocationBtn.disabled = false;
                        getLocationBtn.innerHTML =
                            '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> Get My Location';
                    }, function(error) {
                        alert(
                            'Unable to retrieve your location. Please enter coordinates manually.');
                        getLocationBtn.disabled = false;
                        getLocationBtn.innerHTML =
                            '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> Get My Location';
                    });
                } else {
                    alert('Geolocation is not supported by your browser.');
                }
            });
        });
    </script>
@endsection
