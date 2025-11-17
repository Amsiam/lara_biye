<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-custom-pink/10 to-custom-red/10 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-2xl p-8 md:p-12 text-center border border-gray-100">
        <!-- Error Code -->
        <div class="mb-6">
            <h1 class="text-9xl md:text-[12rem] font-bold text-custom-pink leading-none">404</h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-custom-red mb-4">Page Not Found</h2>
            <p class="text-gray-600 text-lg mb-2">Oops! The page you're looking for doesn't exist.</p>
            <p class="text-gray-500">It might have been moved or deleted.</p>
        </div>

        <!-- Illustration or Icon -->
        <div class="mb-8">
            <svg class="w-48 h-48 mx-auto text-custom-pink/20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
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
            @endauth
        </div>

        <!-- Additional Help -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-gray-600 text-sm">
                Need help?
                <a href="{{ url('/') }}" class="text-custom-pink hover:text-custom-red font-semibold hover:underline">
                    Contact Support
                </a>
            </p>
        </div>
    </div>
</body>
</html>
