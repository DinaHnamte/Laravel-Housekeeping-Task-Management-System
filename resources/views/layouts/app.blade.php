<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset("favicon.ico") }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Martian+Mono:wght@100..800&family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    </style>
    <title>Housekeeping Checklist System - @yield("title")</title>
    @vite("resources/css/app.css")
</head>

<body>
    <div class="min-h-screen bg-gray-100">
        <!-- Flash Messages -->
        <div class="fixed right-4 top-4 z-50 space-y-2">
            @if (session("success"))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 shadow-lg"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session("success") }}</span>
                    </div>
                </div>
            @endif

            @if (session("error"))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 shadow-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session("error") }}</span>
                    </div>
                </div>
            @endif

            @if (session("info"))
                <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-blue-800 shadow-lg"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session("info") }}</span>
                    </div>
                </div>
            @endif

            @if (session("warning"))
                <div class="rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-yellow-800 shadow-lg"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session("warning") }}</span>
                    </div>
                </div>
            @endif
        </div>
        {{-- Navigation Header --}}
        <nav class="border-b bg-white shadow-sm">
            <div class="container mx-auto px-4">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ route("properties.index") }}" class="text-xl font-bold text-gray-800">
                            Housekeeping System
                        </a>
                    </div>

                    <div class="flex items-center space-x-4">
                        @auth
                            <span class="text-sm text-gray-700">
                                Welcome, {{ Auth::user()->name }}
                            </span>
                            <form action="{{ route("logout") }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route("login") }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Login
                            </a>
                            <a href="{{ route("register") }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        @hasSection("content_full")
            @yield("content_full")
        @else
            <div class="container mx-auto p-4">
                @yield("content")
            </div>
        @endif
    </div>

    @vite("resources/js/app.js")

    <!-- Auto-hide flash messages after 5 seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });
    </script>
</body>

</html>
