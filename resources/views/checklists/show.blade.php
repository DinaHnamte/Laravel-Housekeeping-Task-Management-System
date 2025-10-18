@extends("layouts.sidebar")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Checklist Details
                </h1>
                <p class="mt-2 text-slate-600">View checklist information and status</p>
            </div>
            <div class="flex items-center space-x-3">
                @can("edit:checklists")
                    <a href="{{ route("checklists.edit", $checklist) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-orange-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endcan
                <a href="{{ route("checklists.index") }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Checklists
                </a>
            </div>
        </div>

        <!-- Checklist Details -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-slate-900 mb-6">Checklist Information</h2>
                        
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Property -->
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Property</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $checklist->property->name }}</p>
                            </div>

                            <!-- Room -->
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Room</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $checklist->room->name }}</p>
                            </div>

                            <!-- Task -->
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Task</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $checklist->task->task }}</p>
                            </div>

                            <!-- User -->
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Assigned To</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $checklist->user->name }}</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Status</label>
                                @if ($checklist->checked_off)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        In Progress
                                    </span>
                                @endif
                            </div>

                            <!-- Start Time -->
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Start Time</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $checklist->time_date_stamp_start ? $checklist->time_date_stamp_start->format("M d, Y g:i A") : "-" }}
                                </p>
                            </div>

                            <!-- End Time -->
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">End Time</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $checklist->time_date_stamp_end ? $checklist->time_date_stamp_end->format("M d, Y g:i A") : "-" }}
                                </p>
                            </div>
                        </div>

                        <!-- Notes -->
                        @if ($checklist->notes)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-slate-500 mb-2">Notes</label>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-sm text-slate-700">{{ $checklist->notes }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- GPS Coordinates -->
                        @if ($checklist->latitude && $checklist->longitude)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-slate-500 mb-2">Location</label>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-sm text-slate-700">
                                        <span class="font-medium">Latitude:</span> {{ $checklist->latitude }}<br>
                                        <span class="font-medium">Longitude:</span> {{ $checklist->longitude }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Photo -->
            <div class="lg:col-span-1">
                <div class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-slate-900 mb-6">Task Photo</h2>
                        
                        @if ($checklist->image_link)
                            <div class="space-y-4">
                                <div class="relative overflow-hidden rounded-lg border border-slate-200">
                                    <img src="{{ Storage::url($checklist->image_link) }}" 
                                         alt="Task completion photo" 
                                         class="h-64 w-full object-cover">
                                </div>
                                <div class="text-center">
                                    <a href="{{ Storage::url($checklist->image_link) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        View Full Size
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-slate-900">No photo uploaded</h3>
                                <p class="mt-1 text-sm text-slate-500">No photo was uploaded for this task.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection