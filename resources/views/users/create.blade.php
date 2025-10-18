@extends("layouts.sidebar")

@section("title", "Create User")

@section("sidebar-content")
    <div class="mx-auto max-w-2xl">
        <div class="mb-8">
            <h1 class="bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-3xl font-bold text-transparent">Create
                New User</h1>
            <p class="mt-2 text-sm text-gray-600">Add a new user to the system with appropriate role and permissions</p>
        </div>

        <div class="rounded-2xl border border-gray-200/50 bg-white/80 p-8 shadow-xl shadow-gray-200/50 backdrop-blur-sm">
            <form action="{{ route("users.store") }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name Field -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old("name") }}"
                        class="@error("name") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 placeholder-slate-500 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                        placeholder="Enter full name" required>
                    @error("name")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old("email") }}"
                        class="@error("email") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 placeholder-slate-500 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                        placeholder="Enter email address" required>
                    @error("email")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Field -->
                <div class="space-y-2">
                    <label for="phone" class="block text-sm font-semibold text-gray-700">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old("phone") }}"
                        class="@error("phone") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 placeholder-slate-500 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                        placeholder="Enter phone number" required>
                    @error("phone")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Field -->
                <div class="space-y-2">
                    <label for="role" class="block text-sm font-semibold text-gray-700">User Role</label>
                    <select id="role" name="role"
                        class="@error("role") border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 transition-all duration-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20"
                        required>
                        <option value="">Select a role</option>
                        <option value="Housekeeper" {{ old("role") == "Housekeeper" ? "selected" : "" }}>Housekeeping Staff
                        </option>
                        <option value="Admin" {{ old("role") == "Admin" ? "selected" : "" }}>Administrator</option>
                        <option value="Owner" {{ old("role") == "Owner" ? "selected" : "" }}>Property Owner</option>
                    </select>
                    @error("role")
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
                    <a href="{{ route("users.index") }}"
                        class="rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-8 py-3 font-semibold text-white shadow-lg transition-all duration-200 hover:bg-slate-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
