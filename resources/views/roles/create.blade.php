@extends("layouts.sidebar")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Create New Role
                </h1>
                <p class="mt-2 text-slate-600">Create a new role with specific permissions</p>
            </div>
            <a href="{{ route("roles.index") }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Roles
            </a>
        </div>

        <!-- Form -->
        <div
            class="group relative overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50 backdrop-blur-sm transition-all duration-300 hover:shadow-xl hover:shadow-slate-300/50">
            <div class="p-6">
                <form action="{{ route("roles.store") }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Role Name -->
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
                                Role Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                class="@error("name") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-slate-300 px-3 py-2 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                id="name" name="name" value="{{ old("name") }}" placeholder="Enter role name"
                                required>
                            @error("name")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permissions -->
                        <div>
                            <label class="mb-4 block text-sm font-medium text-slate-700">
                                Permissions
                            </label>

                            @foreach ($permissions as $category => $categoryPermissions)
                                <div class="mb-6">
                                    <h3 class="mb-3 text-sm font-semibold capitalize text-slate-800">{{ $category }}
                                    </h3>
                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                                        @foreach ($categoryPermissions as $permission)
                                            <label
                                                class="flex cursor-pointer items-center space-x-3 rounded-lg border border-slate-200 bg-slate-50 p-3 hover:bg-slate-100">
                                                <input type="checkbox"
                                                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                                    name="permissions[]" value="{{ $permission->id }}"
                                                    {{ in_array($permission->id, old("permissions", [])) ? "checked" : "" }}>
                                                <span
                                                    class="text-sm font-medium text-slate-700">{{ $permission->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @error("permissions")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex items-center justify-end space-x-4">
                        <a href="{{ route("roles.index") }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-blue-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Create Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add select all functionality for each category
            const categories = document.querySelectorAll('h3');
            categories.forEach(category => {
                const categoryDiv = category.nextElementSibling;
                const checkboxes = categoryDiv.querySelectorAll('input[type="checkbox"]');

                // Create select all checkbox
                const selectAllDiv = document.createElement('div');
                selectAllDiv.className = 'mb-2 flex items-center space-x-2';
                selectAllDiv.innerHTML = `
                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 select-all-category"
                           id="select-all-${category.textContent.trim().toLowerCase().replace(/\s+/g, '-')}">
                    <label for="select-all-${category.textContent.trim().toLowerCase().replace(/\s+/g, '-')}"
                           class="text-xs font-medium text-slate-600">Select All</label>
                `;

                categoryDiv.parentNode.insertBefore(selectAllDiv, categoryDiv);

                const selectAllCheckbox = selectAllDiv.querySelector('.select-all-category');

                // Handle select all
                selectAllCheckbox.addEventListener('change', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });

                // Handle individual checkbox changes
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked)
                            .length;
                        selectAllCheckbox.checked = checkedCount === checkboxes.length;
                        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount <
                            checkboxes.length;
                    });
                });

                // Set initial state
                const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
                selectAllCheckbox.checked = checkedCount === checkboxes.length;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
            });
        });
    </script>
@endsection
