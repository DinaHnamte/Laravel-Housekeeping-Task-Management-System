@extends("layouts.sidebar")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Create New Checklist
                </h1>
                <p class="mt-2 text-slate-600">Create a new checklist for an assignment</p>
            </div>
            <a href="{{ route("checklists.index") }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Checklists
            </a>
        </div>

        <!-- Form -->
        <div class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
            <div class="p-6">
                <form action="{{ route("checklists.store") }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Assignment Selection -->
                        <div class="lg:col-span-2">
                            <label for="assignment_id" class="mb-2 block text-sm font-medium text-slate-700">
                                Assignment <span class="text-red-500">*</span>
                            </label>
                            <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('assignment_id') border-red-500 ring-2 ring-red-200 @enderror"
                                id="assignment_id" name="assignment_id" required>
                                <option value="">Select an assignment</option>
                                @foreach ($assignments as $assignment)
                                    <option value="{{ $assignment->id }}" {{ old('assignment_id') == $assignment->id ? 'selected' : '' }}>
                                        {{ $assignment->property->name }} - {{ $assignment->assignment_date->format('M d, Y') }}
                                        ({{ $assignment->user->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assignment_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tasks Selection -->
                        <div class="lg:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Tasks <span class="text-red-500">*</span>
                            </label>
                            <div id="tasks-container" class="space-y-3">
                                <p class="text-sm text-slate-500">Select an assignment to load tasks</p>
                            </div>
                            @error('tasks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="lg:col-span-2">
                            <label for="notes" class="mb-2 block text-sm font-medium text-slate-700">
                                Notes
                            </label>
                            <textarea class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('notes') border-red-500 ring-2 ring-red-200 @enderror"
                                id="notes" name="notes" rows="4" placeholder="Add any additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- GPS Coordinates -->
                        <div>
                            <label for="latitude" class="mb-2 block text-sm font-medium text-slate-700">
                                Latitude
                            </label>
                            <input type="number" step="any"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('latitude') border-red-500 ring-2 ring-red-200 @enderror"
                                id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="Auto-detect">
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="mb-2 block text-sm font-medium text-slate-700">
                                Longitude
                            </label>
                            <input type="number" step="any"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('longitude') border-red-500 ring-2 ring-red-200 @enderror"
                                id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="Auto-detect">
                            @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex items-center justify-end space-x-4">
                        <a href="{{ route("checklists.index") }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-blue-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Create Checklist
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const assignmentSelect = document.getElementById('assignment_id');
            const tasksContainer = document.getElementById('tasks-container');

            assignmentSelect.addEventListener('change', function() {
                const assignmentId = this.value;
                
                if (assignmentId) {
                    // Show loading state
                    tasksContainer.innerHTML = '<p class="text-sm text-slate-500">Loading tasks...</p>';
                    
                    // Fetch tasks for the selected assignment
                    fetch(`/api/assignments/${assignmentId}/tasks`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.tasks && data.tasks.length > 0) {
                                let tasksHtml = '<div class="space-y-3">';
                                data.tasks.forEach(task => {
                                    tasksHtml += `
                                        <div class="flex items-center space-x-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                                            <input type="checkbox" 
                                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" 
                                                id="task_${task.id}" 
                                                name="tasks[]" 
                                                value="${task.id}">
                                            <label for="task_${task.id}" class="text-sm font-medium text-slate-700">
                                                ${task.task}
                                            </label>
                                            <span class="text-xs text-slate-500">(${task.room.name})</span>
                                        </div>
                                    `;
                                });
                                tasksHtml += '</div>';
                                tasksContainer.innerHTML = tasksHtml;
                            } else {
                                tasksContainer.innerHTML = '<p class="text-sm text-slate-500">No tasks found for this assignment.</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            tasksContainer.innerHTML = '<p class="text-sm text-red-600">Error loading tasks. Please try again.</p>';
                        });
                } else {
                    tasksContainer.innerHTML = '<p class="text-sm text-slate-500">Select an assignment to load tasks</p>';
                }
            });

            // Auto-detect GPS coordinates
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                });
            }
        });
    </script>
@endsection