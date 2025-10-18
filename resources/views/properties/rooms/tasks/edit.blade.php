@extends("layouts.sidebar")

@section("title", "Edit Task")

@section("sidebar-content")
    <div class="mx-auto max-w-2xl">
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                Edit Task
            </h1>
            <p class="mt-2 text-sm text-slate-600">
                Update task information for {{ $room->name }} in {{ $property->name }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200/50 bg-white/80 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">
            <form action="{{ route("properties.rooms.tasks.update", [$property, $room, $task]) }}" method="POST"
                class="space-y-6">
                @csrf
                @method("PUT")

                <!-- Task Description -->
                <div class="space-y-2">
                    <label for="task" class="block text-sm font-semibold text-slate-700">Task Description</label>
                    <input type="text" id="task" name="task" value="{{ old("task", $task->task) }}"
                        class="@error("task") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 placeholder-slate-500 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                        placeholder="Enter task description" required>
                    @error("task")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Room Information (Read-only) -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Room</label>
                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-slate-600">
                        {{ $room->name }}
                    </div>
                    <p class="text-sm text-slate-500">Task belongs to this room</p>
                </div>

                <!-- Property Information (Read-only) -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Property</label>
                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-slate-600">
                        {{ $property->name }}
                    </div>
                    <p class="text-sm text-slate-500">Room belongs to this property</p>
                </div>

                <!-- Default Task Checkbox -->
                <div class="space-y-2">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" id="is_default" name="is_default" value="1"
                            {{ old("is_default", $task->is_default) ? "checked" : "" }}
                            class="h-4 w-4 rounded border-slate-300 text-slate-600 focus:ring-slate-500">
                        <label for="is_default" class="text-sm font-semibold text-slate-700">
                            Set as default task
                        </label>
                    </div>
                    <p class="text-sm text-slate-500">Check this if this task should be a default task for this room type
                    </p>
                    @error("is_default")
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
                    <a href="{{ route("properties.rooms.tasks.index", [$property, $room]) }}"
                        class="rounded-lg border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-8 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
