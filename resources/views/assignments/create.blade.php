@extends("layouts.sidebar")

@section("title", "Create Assignment")

@section("sidebar-content")
    <div class="mx-auto max-w-2xl">
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                Create New Assignment
            </h1>
            <p class="mt-2 text-sm text-slate-600">Assign a housekeeper to clean a property on a specific date</p>
        </div>

        <div class="rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
            <form action="{{ route("assignments.store") }}" method="POST" class="space-y-6">
                @csrf

                <!-- Property Selection -->
                <div class="space-y-2">
                    <label for="property_id" class="block text-sm font-semibold text-slate-700">Property</label>
                    <select id="property_id" name="property_id"
                        class="@error("property_id") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                        required>
                        <option value="">Select a property</option>
                        @foreach ($properties as $property)
                            <option value="{{ $property->id }}" {{ old("property_id") == $property->id ? "selected" : "" }}>
                                {{ $property->name }} ({{ $property->beds }} beds, {{ $property->baths }} baths)
                            </option>
                        @endforeach
                    </select>
                    @error("property_id")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Housekeeper Selection -->
                <div class="space-y-2">
                    <label for="user_id" class="block text-sm font-semibold text-slate-700">Housekeeper</label>
                    <select id="user_id" name="user_id"
                        class="@error("user_id") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                        required>
                        <option value="">Select a housekeeper</option>
                        @foreach ($housekeepers as $housekeeper)
                            <option value="{{ $housekeeper->id }}"
                                {{ old("user_id") == $housekeeper->id ? "selected" : "" }}>
                                {{ $housekeeper->name }} ({{ $housekeeper->email }})
                            </option>
                        @endforeach
                    </select>
                    @error("user_id")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assignment Date -->
                <div class="space-y-2">
                    <label for="assignment_date" class="block text-sm font-semibold text-slate-700">Assignment Date</label>
                    <input type="date" id="assignment_date" name="assignment_date" value="{{ old("assignment_date") }}"
                        class="@error("assignment_date") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                        min="{{ date("Y-m-d") }}" required>
                    @error("assignment_date")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Range -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="start_time" class="block text-sm font-semibold text-slate-700">Start Time</label>
                        <input type="time" id="start_time" name="start_time" value="{{ old("start_time") }}"
                            class="@error("start_time") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20">
                        @error("start_time")
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="end_time" class="block text-sm font-semibold text-slate-700">End Time</label>
                        <input type="time" id="end_time" name="end_time" value="{{ old("end_time") }}"
                            class="@error("end_time") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20">
                        @error("end_time")
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="space-y-2">
                    <label for="notes" class="block text-sm font-semibold text-slate-700">Notes</label>
                    <textarea id="notes" name="notes" rows="3"
                        class="@error("notes") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 placeholder-slate-500 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                        placeholder="Add any special instructions or notes for this assignment">{{ old("notes") }}</textarea>
                    @error("notes")
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
                    <a href="{{ route("assignments.index") }}"
                        class="rounded-lg border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-8 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
