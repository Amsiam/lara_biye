<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Forbidden</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-custom-pink/10 to-custom-red/10 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-2xl p-8 md:p-12 text-center border border-gray-100">
        <!-- Error Code -->
        <div class="mb-6">
            <h1 class="text-9xl md:text-[12rem] font-bold text-custom-red leading-none">403</h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-custom-red mb-4">Access Forbidden</h2>
            <p class="text-gray-600 text-lg mb-2">You don't have permission to access this page.</p>
            <p class="text-gray-500">{{ $exception->getMessage() ?: 'This action is unauthorized.' }}</p>
        </div>

        <!-- Illustration or Icon -->
        <div class="mb-8">
            <svg class="w-48 h-48 mx-auto text-custom-red/20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
            </svg>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}"
               class="px-8 py-3.5 bg-custom-pink text-white rounded-lg font-semibold hover:bg-custom-red shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                Go Back Home
            </a>

            @auth
            <a href="{{ route('profile', auth()->id()) }}"
               class="px-8 py-3.5 bg-white text-custom-pink border-2 border-custom-pink rounded-lg font-semibold hover:bg-custom-pink hover:text-white shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                View My Profile
            </a>
            @else
            <a href="{{ route('login') }}"
               class="px-8 py-3.5 bg-white text-custom-pink border-2 border-custom-pink rounded-lg font-semibold hover:bg-custom-pink hover:text-white shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                Login
            </a>
            @endauth
        </div>

        <!-- Additional Help -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-gray-600 text-sm">
                Think this is a mistake?
                <a href="{{ url('/') }}" class="text-custom-pink hover:text-custom-red font-semibold hover:underline">
                    Contact Support
                </a>
            </p>
        </div>
    </div>
</body>
</html>
