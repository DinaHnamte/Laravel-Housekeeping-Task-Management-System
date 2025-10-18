@extends("layouts.app")

@section("title", "Login")

@section("content")
    <div class="flex h-full w-full items-center justify-center">
        <form action="{{ route("login") }}" method="POST"
            class="mt-[20%] w-1/2 space-y-4 rounded-2xl bg-white p-6 text-gray-800 shadow">
            @csrf
            <h1 class="text-2xl font-bold">Login</h1>

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

            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember"
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="remember" class="ml-2 text-sm text-gray-700">Remember me</label>
            </div>

            <button type="submit"
                class="w-full rounded-md bg-indigo-600 p-2 px-4 text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Login
            </button>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route("register") }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Register here
                    </a>
                </p>
            </div>
        </form>
    </div>
@endsection
