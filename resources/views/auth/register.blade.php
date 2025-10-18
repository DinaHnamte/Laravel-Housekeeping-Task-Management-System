@extends("layouts.app")

@section("title", "Register")

@section("content")
    <div class="flex h-full w-full items-center justify-center">
        <form action="{{ route("register") }}" method="POST"
            class="mt-[10%] w-1/2 space-y-4 rounded-2xl bg-white p-6 text-gray-800 shadow">
            @csrf
            <h1 class="text-2xl font-bold">Create Account</h1>

            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="text-sm text-red-700">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="flex flex-col space-y-2">
                <label for="name" class="text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old("name") }}"
                    class="@error("name") border-red-500 @enderror rounded-md border border-gray-300 p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                @error("name")
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col space-y-2">
                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old("email") }}"
                    class="@error("email") border-red-500 @enderror rounded-md border border-gray-300 p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                @error("email")
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col space-y-2">
                <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password"
                    class="@error("password") border-red-500 @enderror rounded-md border border-gray-300 p-2 focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                @error("password")
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col space-y-2">
                <label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="rounded-md border border-gray-300 p-2 focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <button type="submit"
                class="w-full rounded-md bg-indigo-600 p-2 px-4 text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Create Account
            </button>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route("login") }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Login here
                    </a>
                </p>
            </div>
        </form>
    </div>
@endsection
