@extends("layouts.sidebar")

@section("title", "Create Property")

@section("sidebar-content")
    <div class="mx-auto max-w-2xl">
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">Create
                New Property</h1>
            <p class="mt-2 text-sm text-slate-600">Add a new property to the system with room configurations</p>
        </div>

        <div class="rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
            <form action="{{ route("properties.store") }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Property Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700">Property Name</label>
                    <input type="text" id="name" name="name" value="{{ old("name") }}"
                        class="@error("name") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 placeholder-slate-500 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                        placeholder="Enter property name" required>
                    @error("name")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address Fields -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-semibold text-slate-700">Property Address</label>
                        <span class="text-xs text-slate-500">Coordinates will be calculated automatically</span>
                    </div>

                    <!-- Street Address -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                        <div class="sm:col-span-3">
                            <label for="street" class="mb-1 block text-sm font-medium text-slate-700">Street
                                Address</label>
                            <input type="text" id="street" name="street"
                                class="@error("street") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 placeholder-slate-400 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                                placeholder="e.g., 123 Main Street" value="{{ old("street") }}">
                            @error("street")
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="house_number" class="mb-1 block text-sm font-medium text-slate-700">House #</label>
                            <input type="text" id="house_number" name="house_number"
                                class="@error("house_number") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 placeholder-slate-400 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                                placeholder="123" value="{{ old("house_number") }}">
                            @error("house_number")
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- City and State -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="city" class="mb-1 block text-sm font-medium text-slate-700">City</label>
                            <input type="text" id="city" name="city"
                                class="@error("city") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 placeholder-slate-400 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                                placeholder="e.g., New York" value="{{ old("city") }}">
                            @error("city")
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="state"
                                class="mb-1 block text-sm font-medium text-slate-700">State/Province</label>
                            <input type="text" id="state" name="state"
                                class="@error("state") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 placeholder-slate-400 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                                placeholder="e.g., New York" value="{{ old("state") }}">
                            @error("state")
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Postcode and Country -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="postcode" class="mb-1 block text-sm font-medium text-slate-700">Postal Code</label>
                            <input type="text" id="postcode" name="postcode"
                                class="@error("postcode") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 placeholder-slate-400 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                                placeholder="e.g., 10001" value="{{ old("postcode") }}">
                            @error("postcode")
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="country" class="mb-1 block text-sm font-medium text-slate-700">Country</label>
                            <input type="text" id="country" name="country"
                                class="@error("country") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 placeholder-slate-400 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                                placeholder="e.g., USA" value="{{ old("country") }}">
                            @error("country")
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Hidden field for full address -->
                    <input type="hidden" id="address" name="address" value="{{ old("address") }}">
                </div>

                <!-- Real-time Geocoding Preview -->
                <div id="geocode-preview" class="hidden rounded-lg border border-green-200 bg-green-50 p-4">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-semibold text-green-800">Location Found!</span>
                    </div>
                    <div class="mt-2 flex items-center gap-2 text-sm">
                        <span class="font-medium text-slate-700">Coordinates:</span>
                        <span id="preview-coords" class="font-mono text-sm font-bold text-slate-900"></span>
                    </div>
                </div>

                <!-- Loading indicator -->
                <div id="geocode-loading" class="hidden items-center gap-2 text-sm text-slate-600">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span>Looking up address...</span>
                </div>

                <!-- Error message -->
                <div id="geocode-error" class="hidden rounded-lg border border-red-200 bg-red-50 p-3">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-red-700">Could not find this address. Please check and try again.</span>
                    </div>
                </div>

                <!-- Header Image Upload -->
                <div class="space-y-2">
                    <label for="header_image" class="block text-sm font-semibold text-slate-700">Property Header
                        Image</label>
                    <input type="file" id="header_image" name="header_image" accept="image/*"
                        class="@error("header_image") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20">
                    <p class="text-sm text-slate-500">Upload a header image for the property (optional)</p>
                    @error("header_image")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- General Errors -->
                @if ($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                <ul class="mt-2 list-inside list-disc text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route("properties.index") }}"
                        class="rounded-lg border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-8 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create Property
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let geocodeTimeout;
        const streetInput = document.getElementById('street');
        const houseNumberInput = document.getElementById('house_number');
        const cityInput = document.getElementById('city');
        const stateInput = document.getElementById('state');
        const postcodeInput = document.getElementById('postcode');
        const countryInput = document.getElementById('country');
        const hiddenAddressInput = document.getElementById('address');
        const previewDiv = document.getElementById('geocode-preview');
        const loadingDiv = document.getElementById('geocode-loading');
        const errorDiv = document.getElementById('geocode-error');

        // Debounce function to avoid too many API calls
        function debounce(func, wait) {
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(geocodeTimeout);
                    func(...args);
                };
                clearTimeout(geocodeTimeout);
                geocodeTimeout = setTimeout(later, wait);
            };
        }

        // Function to build full address from individual fields (excluding house number for better geocoding)
        function buildFullAddress() {
            const parts = [
                streetInput.value.trim(),
                cityInput.value.trim(),
                stateInput.value.trim(),
                postcodeInput.value.trim(),
                countryInput.value.trim()
            ].filter(part => part.length > 0);

            return parts.join(', ');
        }

        // Function to build complete address including house number for storage
        function buildCompleteAddress() {
            const parts = [
                houseNumberInput.value.trim(),
                streetInput.value.trim(),
                cityInput.value.trim(),
                stateInput.value.trim(),
                postcodeInput.value.trim(),
                countryInput.value.trim()
            ].filter(part => part.length > 0);

            return parts.join(', ');
        }

        // Function to geocode address in real-time
        const geocodeAddress = debounce(async function() {
            const address = buildFullAddress();

            if (!address || address.trim().length < 5) {
                hideAllPreviews();
                hiddenAddressInput.value = '';
                return;
            }

            showLoading();

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1&addressdetails=1`, {
                        headers: {
                            'Accept': 'application/json',
                        }
                    });

                const results = await response.json();

                if (results && results.length > 0) {
                    const result = results[0];
                    const addressData = result.address || {};

                    displayPreview(result, addressData);

                    // Update the hidden address field with complete address including house number
                    hiddenAddressInput.value = buildCompleteAddress();
                } else {
                    showError();
                    hiddenAddressInput.value = '';
                }
            } catch (error) {
                console.error('Geocoding error:', error);
                showError();
                hiddenAddressInput.value = '';
            }
        }, 800); // Wait 800ms after user stops typing

        function showLoading() {
            previewDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');
            loadingDiv.classList.remove('hidden');
            loadingDiv.classList.add('flex');
        }

        function hideAllPreviews() {
            previewDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');
            loadingDiv.classList.add('hidden');
            loadingDiv.classList.remove('flex');
        }

        function showError() {
            previewDiv.classList.add('hidden');
            loadingDiv.classList.add('hidden');
            loadingDiv.classList.remove('flex');
            errorDiv.classList.remove('hidden');
        }

        function displayPreview(result, addressData) {
            // Display coordinates only
            const lat = parseFloat(result.lat).toFixed(6);
            const lon = parseFloat(result.lon).toFixed(6);
            document.getElementById('preview-coords').textContent = `${lat}, ${lon}`;

            // Show preview
            previewDiv.classList.remove('hidden');
            loadingDiv.classList.add('hidden');
            loadingDiv.classList.remove('flex');
            errorDiv.classList.add('hidden');
        }

        // Listen for all address input changes
        [streetInput, houseNumberInput, cityInput, stateInput, postcodeInput, countryInput].forEach(input => {
            input.addEventListener('input', geocodeAddress);
        });
    </script>
@endsection
