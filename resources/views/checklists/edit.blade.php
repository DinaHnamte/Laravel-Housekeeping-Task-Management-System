@extends("layouts.sidebar")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Edit Checklist
                </h1>
                <p class="mt-2 text-slate-600">Update checklist information and status</p>
            </div>
            <a href="{{ route("checklists.show", $checklist) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Checklist
            </a>
        </div>

        <!-- Form -->
        <div
            class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
            <div class="p-6">
                <form action="{{ route("checklists.update", $checklist) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Property (Read-only) -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Property</label>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                                {{ $checklist->property->name }}
                            </div>
                        </div>

                        <!-- Room (Read-only) -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Room</label>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                                {{ $checklist->room->name }}
                            </div>
                        </div>

                        <!-- Task (Read-only) -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Task</label>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                                {{ $checklist->task->task }}
                            </div>
                        </div>

                        <!-- User (Read-only) -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Assigned To</label>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                                {{ $checklist->user->name }}
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                            <select
                                class="@error("checked_off") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                name="checked_off">
                                <option value="0" {{ !$checklist->checked_off ? "selected" : "" }}>In Progress</option>
                                <option value="1" {{ $checklist->checked_off ? "selected" : "" }}>Completed</option>
                            </select>
                            @error("checked_off")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="time_date_stamp_start" class="mb-2 block text-sm font-medium text-slate-700">
                                Start Time
                            </label>
                            <input type="datetime-local"
                                class="@error("time_date_stamp_start") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                id="time_date_stamp_start" name="time_date_stamp_start"
                                value="{{ $checklist->time_date_stamp_start ? $checklist->time_date_stamp_start->format("Y-m-d\TH:i") : "" }}">
                            @error("time_date_stamp_start")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="time_date_stamp_end" class="mb-2 block text-sm font-medium text-slate-700">
                                End Time
                            </label>
                            <input type="datetime-local"
                                class="@error("time_date_stamp_end") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                id="time_date_stamp_end" name="time_date_stamp_end"
                                value="{{ $checklist->time_date_stamp_end ? $checklist->time_date_stamp_end->format("Y-m-d\TH:i") : "" }}">
                            @error("time_date_stamp_end")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="lg:col-span-2">
                            <label for="notes" class="mb-2 block text-sm font-medium text-slate-700">
                                Notes
                            </label>
                            <textarea
                                class="@error("notes") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                id="notes" name="notes" rows="4" placeholder="Add any additional notes...">{{ old("notes", $checklist->notes) }}</textarea>
                            @error("notes")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- GPS Coordinates -->
                        <div>
                            <label for="latitude" class="mb-2 block text-sm font-medium text-slate-700">
                                Latitude
                            </label>
                            <input type="number" step="any"
                                class="@error("latitude") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                id="latitude" name="latitude" value="{{ old("latitude", $checklist->latitude) }}"
                                placeholder="Auto-detect">
                            @error("latitude")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="mb-2 block text-sm font-medium text-slate-700">
                                Longitude
                            </label>
                            <input type="number" step="any"
                                class="@error("longitude") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                id="longitude" name="longitude" value="{{ old("longitude", $checklist->longitude) }}"
                                placeholder="Auto-detect">
                            @error("longitude")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Photo -->
                        @if ($checklist->image_link)
                            <div class="lg:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-slate-700">Current Photo</label>
                                <div class="relative overflow-hidden rounded-lg border border-slate-200">
                                    <img src="{{ Storage::url($checklist->image_link) }}" alt="Current task photo"
                                        class="h-48 w-full object-cover">
                                </div>
                            </div>
                        @endif

                        <!-- New Photo -->
                        <div class="lg:col-span-2">
                            <label for="photo" class="mb-2 block text-sm font-medium text-slate-700">
                                @if ($checklist->image_link)
                                    Replace Photo
                                @else
                                    Upload Photo
                                @endif
                            </label>
                            <input type="file" accept="image/*" capture="environment"
                                class="@error("photo") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                id="photo" name="photo">
                            <p class="mt-1 text-xs text-slate-500">You can take a photo with your camera or select from
                                gallery</p>
                            @error("photo")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex items-center justify-end space-x-4">
                        <a href="{{ route("checklists.show", $checklist) }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-blue-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update Checklist
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-detect GPS coordinates if not already set
            if (!document.getElementById('latitude').value && !document.getElementById('longitude').value) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                    });
                }
            }
        });
    </script>
@endsection
