@extends("layouts.sidebar")

@section("title", $room->name . " - Task Checklist")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    {{ $room->name }} - Task Checklist</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Property: {{ $assignment->property->name }} |
                    Date: {{ $assignment->assignment_date->format("M d, Y") }}
                </p>
            </div>
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                <svg class="h-8 w-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                </svg>
            </div>
        </div>

        <!-- Photo Requirement Alert -->
        <div class="rounded-xl border border-orange-200 bg-orange-50 p-4">
            <div class="flex items-start">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-orange-100">
                    <svg class="h-4 w-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-orange-800">Photo Requirement</h3>
                    <p class="mt-1 text-sm text-orange-700">You must upload at least one photo for each completed task.</p>
                </div>
            </div>
        </div>

        <!-- Tasks Form -->
        <form id="roomChecklistForm" action="{{ route("checklists.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

            <div class="grid gap-6 sm:grid-cols-2">
                @foreach ($tasks as $task)
                    @php
                        $existingChecklist = $existingChecklists->get($task->id);
                    @endphp
                    <div class="task-card group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 p-6 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50"
                        data-task-id="{{ $task->id }}">
                        <!-- Task Header -->
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100">
                                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $task->task }}</h3>
                            </div>
                            <div class="flex items-center">
                                <input
                                    class="task-checkbox h-4 w-4 rounded border-slate-300 text-green-600 focus:ring-green-500"
                                    type="checkbox" id="task_{{ $task->id }}"
                                    name="tasks[{{ $task->id }}][completed]" value="1"
                                    {{ $existingChecklist && $existingChecklist->checked_off ? "checked" : "" }}>
                                <label class="ml-2 text-sm font-medium text-slate-700" for="task_{{ $task->id }}">
                                    Complete
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="tasks[{{ $task->id }}][task_id]" value="{{ $task->id }}">

                        <!-- Notes Section -->
                        <div class="mb-4">
                            <label for="notes_{{ $task->id }}" class="mb-2 block text-sm font-medium text-slate-700">
                                Notes (Optional)
                            </label>
                            <textarea
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                id="notes_{{ $task->id }}" name="tasks[{{ $task->id }}][notes]" rows="3"
                                placeholder="Add any notes about this task...">{{ $existingChecklist ? $existingChecklist->notes : "" }}</textarea>
                        </div>

                        <!-- Photo Section -->
                        <div class="mb-4">
                            <label for="photo_{{ $task->id }}" class="mb-2 block text-sm font-medium text-slate-700">
                                Photo <span class="text-red-500">*</span>
                            </label>
                            <input type="file"
                                class="task-photo w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-4 file:rounded-full file:border-0 file:bg-green-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-green-700 hover:file:bg-green-100 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                id="photo_{{ $task->id }}" name="tasks[{{ $task->id }}][photo]" accept="image/*">

                            @if ($existingChecklist && $existingChecklist->image_link)
                                <div class="mt-3">
                                    <img src="{{ asset("storage/" . $existingChecklist->image_link) }}" alt="Current photo"
                                        class="h-24 w-24 rounded-lg border border-slate-200 object-cover">
                                    <p class="mt-1 text-xs text-slate-500">Current photo</p>
                                </div>
                            @endif
                        </div>

                        <!-- GPS Coordinates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="latitude_{{ $task->id }}"
                                    class="mb-2 block text-sm font-medium text-slate-700">
                                    Latitude
                                </label>
                                <input type="number" step="any"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                    id="latitude_{{ $task->id }}" name="tasks[{{ $task->id }}][latitude]"
                                    value="{{ $existingChecklist ? $existingChecklist->latitude : "" }}"
                                    placeholder="Auto-detect">
                            </div>
                            <div>
                                <label for="longitude_{{ $task->id }}"
                                    class="mb-2 block text-sm font-medium text-slate-700">
                                    Longitude
                                </label>
                                <input type="number" step="any"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                                    id="longitude_{{ $task->id }}" name="tasks[{{ $task->id }}][longitude]"
                                    value="{{ $existingChecklist ? $existingChecklist->longitude : "" }}"
                                    placeholder="Auto-detect">
                            </div>
                        </div>

                        <!-- Individual Task Completion Button -->
                        <div class="mt-4 border-t border-slate-200 pt-4">
                            <button type="button"
                                class="complete-task-btn inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-green-700 disabled:cursor-not-allowed disabled:bg-slate-400"
                                data-task-id="{{ $task->id }}"
                                {{ $existingChecklist && $existingChecklist->checked_off ? "disabled" : "" }}>
                                @if ($existingChecklist && $existingChecklist->checked_off)
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Task Completed
                                @else
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Complete This Task
                                @endif
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route("checklists.start", $assignment) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Rooms
                </a>

                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-6 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-green-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Save Room Progress
                </button>
            </div>
        </form>
    </div>

    <script>
        // Notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />' :
                          type === 'error' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />' :
                          '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'}
                    </svg>
                    ${message}
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize existing checked tasks
            document.querySelectorAll('.task-checkbox:checked').forEach(checkbox => {
                const taskCard = checkbox.closest('.task-card');
                const photoInput = taskCard.querySelector('.task-photo');
                const photoLabel = taskCard.querySelector('label[for="' + photoInput.id + '"]');

                // Mark photo as required for already checked tasks
                photoInput.setAttribute('data-required', 'true');
                taskCard.classList.add('border-green-500', 'ring-2', 'ring-green-200');
                taskCard.classList.remove('border-slate-200');

                // Update label to show required
                if (photoLabel && !photoLabel.innerHTML.includes('*')) {
                    photoLabel.innerHTML = photoLabel.innerHTML.replace('Photo',
                        'Photo <span class="text-red-500">*</span>');
                }
            });

            // Auto-detect GPS location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Fill all latitude/longitude fields
                    document.querySelectorAll('input[name*="[latitude]"]').forEach(input => {
                        if (!input.value) {
                            input.value = latitude;
                        }
                    });

                    document.querySelectorAll('input[name*="[longitude]"]').forEach(input => {
                        if (!input.value) {
                            input.value = longitude;
                        }
                    });
                });
            }

            // Handle individual task completion
            document.querySelectorAll('.complete-task-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const taskId = this.getAttribute('data-task-id');
                    const taskCard = this.closest('.task-card');
                    const checkbox = taskCard.querySelector('.task-checkbox');
                    const photoInput = taskCard.querySelector('.task-photo');
                    const notesInput = taskCard.querySelector('textarea[name*="[notes]"]');
                    const latitudeInput = taskCard.querySelector('input[name*="[latitude]"]');
                    const longitudeInput = taskCard.querySelector('input[name*="[longitude]"]');

                    // Validate photo requirement
                    if (!photoInput.files.length && !photoInput.value) {
                        photoInput.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                        alert('Please upload a photo before completing this task.');
                        return;
                    }

                    // Create form data for this specific task
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('input[name="_token"]').value);
                    formData.append('assignment_id', document.querySelector(
                        'input[name="assignment_id"]').value);
                    formData.append('tasks[' + taskId + '][task_id]', taskId);
                    formData.append('tasks[' + taskId + '][completed]', '1');
                    formData.append('tasks[' + taskId + '][notes]', notesInput.value);
                    formData.append('tasks[' + taskId + '][latitude]', latitudeInput.value);
                    formData.append('tasks[' + taskId + '][longitude]', longitudeInput.value);

                    if (photoInput.files.length > 0) {
                        formData.append('tasks[' + taskId + '][photo]', photoInput.files[0]);
                    }

                    // Disable button during submission
                    this.disabled = true;
                    this.innerHTML =
                        '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Completing...';

                    // Submit individual task
                    fetch('{{ route("checklists.store") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Mark task as completed
                                checkbox.checked = true;
                                taskCard.classList.add('border-green-500', 'ring-2',
                                    'ring-green-200');
                                taskCard.classList.remove('border-slate-200');

                                // Update button
                                this.innerHTML =
                                    '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Task Completed';
                                this.classList.remove('bg-green-600', 'hover:bg-green-700');
                                this.classList.add('bg-slate-400');

                                // Show success message
                                showNotification('Task completed successfully!', 'success');
                            } else {
                                throw new Error(data.message || 'Failed to complete task');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.disabled = false;
                            this.innerHTML =
                                '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Complete This Task';
                            showNotification('Failed to complete task. Please try again.',
                                'error');
                        });
                });
            });

            // Handle task completion checkbox (for visual feedback only)
            document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const taskCard = this.closest('.task-card');
                    const photoInput = taskCard.querySelector('.task-photo');
                    const photoLabel = taskCard.querySelector('label[for="' + photoInput.id + '"]');

                    if (this.checked) {
                        // Mark photo as required for completed tasks
                        photoInput.setAttribute('data-required', 'true');
                        taskCard.classList.add('border-green-500', 'ring-2', 'ring-green-200');
                        taskCard.classList.remove('border-slate-200');

                        // Update label to show required
                        if (photoLabel && !photoLabel.innerHTML.includes('*')) {
                            photoLabel.innerHTML = photoLabel.innerHTML.replace('Photo',
                                'Photo <span class="text-red-500">*</span>');
                        }
                    } else {
                        // Remove requirement for uncompleted tasks
                        photoInput.removeAttribute('data-required');
                        taskCard.classList.remove('border-green-500', 'ring-2', 'ring-green-200');
                        taskCard.classList.add('border-slate-200');

                        // Remove required indicator from label
                        if (photoLabel) {
                            photoLabel.innerHTML = photoLabel.innerHTML.replace(
                                ' <span class="text-red-500">*</span>', '');
                        }

                        // Clear any error styling
                        photoInput.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
                    }
                });
            });

            // Form validation
            document.getElementById('roomChecklistForm').addEventListener('submit', function(e) {
                const checkedTasks = document.querySelectorAll('.task-checkbox:checked');
                let hasErrors = false;
                let errorMessages = [];

                // Clear all previous error styling
                document.querySelectorAll('.task-photo').forEach(input => {
                    input.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
                });

                // Only validate checked tasks that require photos
                checkedTasks.forEach(checkbox => {
                    const taskCard = checkbox.closest('.task-card');
                    const photoInput = taskCard.querySelector('.task-photo');
                    const taskName = taskCard.querySelector('h3').textContent;

                    // Check if task is completed and marked as requiring a photo
                    if (checkbox.checked && photoInput.getAttribute('data-required') === 'true' && !
                        photoInput.files.length && !photoInput.value) {
                        photoInput.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                        errorMessages.push(`"${taskName}" - Photo required for completed task`);
                        hasErrors = true;
                    }
                });

                if (hasErrors) {
                    e.preventDefault();
                    alert('Please upload photos for the following completed tasks:\n\n' + errorMessages
                        .join('\n'));
                }
            });
        });
    </script>
@endsection
