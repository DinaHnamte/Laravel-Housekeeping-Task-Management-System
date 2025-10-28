@extends("layouts.app")

@section("title", "Login")

@section("content")
    <div class="flex min-h-screen w-full items-center justify-center px-4 py-8">
        <form action="{{ route("login") }}" method="POST"
            class="w-full max-w-md space-y-6 rounded-2xl bg-white p-6 shadow-xl sm:p-8">
            @csrf

            <!-- Header -->
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Welcome Back</h1>
                <p class="mt-2 text-sm text-gray-600">Sign in to your account</p>
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
                <input type="password" id="password" name="password" autocomplete="current-password"
                    class="@error("password") border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    placeholder="Enter your password" required>
                @error("password")
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember" class="ml-2 text-sm text-gray-700">Remember me</label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="active:scale-98 w-full rounded-lg bg-indigo-600 px-4 py-3 text-base font-semibold text-white shadow-lg transition-all duration-200 hover:bg-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Sign In
            </button>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route("register") }}"
                        class="font-semibold text-indigo-600 transition-colors hover:text-indigo-500">
                        Create one
                    </a>
                </p>
            </div>
        </form>
    </div>
@endsection
