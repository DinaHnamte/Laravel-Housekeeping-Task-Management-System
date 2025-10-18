@extends("layouts.sidebar")

@section("title", "Room Details")

@section("sidebar-content")
    <div class="mx-auto max-w-4xl">
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">Room
                Details</h1>
            <p class="mt-2 text-sm text-slate-600">View and manage room information</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Room Information Card -->
            <div class="lg:col-span-2">
                <div
                    class="rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                    <h2 class="mb-6 text-xl font-semibold text-slate-900">Room Information</h2>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Room Name</span>
                            <span class="text-lg font-medium text-slate-900">{{ $room->name ?? "Unnamed Room" }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <span class="text-sm font-semibold text-slate-600">Property</span>
                            <span
                                class="text-lg font-medium text-slate-900">{{ $room->property->name ?? "No Property" }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-600">Default Room</span>
                            <div
                                class="@if ($room->is_default) bg-green-100 text-green-800
                                @else bg-slate-100 text-slate-800 @endif inline-flex items-center rounded-full px-3 py-1 text-xs font-medium">
                                {{ $room->is_default ? "Yes" : "No" }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="lg:col-span-1">
                <div
                    class="rounded-2xl border border-slate-200/50 bg-white/80 p-6 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route("properties.rooms.edit", [$property, $room]) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-700 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Room
                        </a>
                        <a href="{{ route("properties.rooms.index", $property) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-slate-600 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-slate-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Back to Rooms
                        </a>
                        <a href="{{ route("properties.show", $property) }}"
                            class="flex w-full items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            View Property
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
