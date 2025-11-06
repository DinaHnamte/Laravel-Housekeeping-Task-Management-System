@extends("layouts.sidebar")

@section("title", "Room Management")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    {{ $property->name }} Rooms</h1>
                <p class="mt-2 text-sm text-slate-600">Manage rooms for {{ $property->name }}</p>
            </div>
            <a href="{{ route("properties.rooms.create", $property) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-6 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Room
            </a>
        </div>
        <!-- Property Filter -->
        <div class="flex items-center justify-end">
            <label for="propertyFilter" class="mr-3 text-sm font-medium text-slate-700">Filter by Property:</label>
            <select id="propertyFilter"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                onchange="changeProperty(this.value)">
                <option value="">All Properties</option>
                @foreach ($properties as $prop)
                    <option value="{{ $prop->id }}" {{ $prop->id == $property->id ? "selected" : "" }}>
                        {{ $prop->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Rooms Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($rooms as $room)
                <div
                    class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <!-- Room Header -->
                    <div class="mb-4">
                        <h3 class="truncate text-lg font-semibold text-slate-900">{{ $room->name ?? "Unnamed Room" }}</h3>
                        <p class="text-sm text-slate-500">{{ $room->property?->name ?? "No Property" }}</p>
                    </div>

                    <!-- Room Status -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Default Room:</span>
                            <div
                                class="@if ($room->is_default) bg-green-100 text-green-800
                                @else bg-slate-100 text-slate-800 @endif inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                {{ $room->is_default ? "Yes" : "No" }}
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-2">
                        <div class="flex space-x-2">
                            <a href="{{ route("properties.rooms.tasks.index", [$property, $room]) }}"
                                class="flex-1 rounded-lg bg-blue-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-blue-700">
                                Tasks
                            </a>
                            <a href="{{ route("properties.rooms.edit", [$property, $room]) }}"
                                class="flex-1 rounded-lg bg-slate-100 px-3 py-2 text-center text-sm font-medium text-slate-700 transition-colors hover:bg-slate-200">
                                Edit
                            </a>
                            <a href="{{ route("properties.rooms.show", [$property, $room]) }}"
                                class="flex-1 rounded-lg bg-slate-600 px-3 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-slate-700">
                                View
                            </a>
                        </div>
                        @can('delete:rooms')
                            <form action="{{ route("properties.rooms.destroy", [$property, $room]) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this room? This will also delete all tasks associated with it. This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700">
                                    Delete Room
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">No rooms available</h3>
                        <p class="mt-1 text-sm text-slate-500">Get started by creating a new room.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($rooms->hasPages())
            <div class="flex justify-center">
                <div class="rounded-xl bg-white/80 p-4 shadow-lg shadow-slate-200/50 backdrop-blur-sm">
                    {{ $rooms->links() }}
                </div>
            </div>
        @endif
    </div>

    <script>
        function changeProperty(propertyId) {
            if (propertyId) {
                window.location.href = "{{ route("properties.rooms.index", ":id") }}".replace(':id', propertyId);
            } else {
                // If "All Properties" is selected, go to properties index
                window.location.href = "{{ route("properties.index") }}";
            }
        }
    </script>
@endsection
