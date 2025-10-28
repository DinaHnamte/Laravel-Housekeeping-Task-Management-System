@extends("layouts.app")

@section("title", "Register")

@section("content")
    <div class="flex min-h-screen w-full items-center justify-center px-4 py-8">
        <form action="{{ route("register") }}" method="POST"
            class="w-full max-w-md space-y-6 rounded-2xl bg-white p-6 shadow-xl sm:p-8">
            @csrf

            <!-- Header -->
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Create Account</h1>
                <p class="mt-2 text-sm text-gray-600">Sign up to get started</p>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                    <div class="flex items-start">
                        <svg class="mr-3 mt-0.5 h-5 w-5 flex-shrink-0 text-red-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-red-700">
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Name -->
            <div class="flex flex-col space-y-2">
                <label for="name" class="text-sm font-semibold text-gray-700">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old("name") }}" autocomplete="name"
                    class="@error("name") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    placeholder="Enter your full name" required>
                @error("name")
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="flex flex-col space-y-2">
                <label for="email" class="text-sm font-semibold text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old("email") }}" autocomplete="email"
                    class="@error("email") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    placeholder="Enter your email" required>
                @error("email")
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="flex flex-col space-y-2">
                <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                <input type="password" id="password" name="password" autocomplete="new-password"
                    class="@error("password") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    placeholder="Create a password" required>
                @error("password")
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="flex flex-col space-y-2">
                <label for="password_confirmation" class="text-sm font-semibold text-gray-700">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    placeholder="Confirm your password" required>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="active:scale-98 w-full rounded-lg bg-indigo-600 px-4 py-3 text-base font-semibold text-white shadow-lg transition-all duration-200 hover:bg-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Create Account
            </button>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route("login") }}"
                        class="font-semibold text-indigo-600 transition-colors hover:text-indigo-500">
                        Sign in
                    </a>
                </p>
            </div>
        </form>
    </div>
@endsection
