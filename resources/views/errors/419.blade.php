<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-custom-pink/10 to-custom-red/10 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-2xl p-8 md:p-12 text-center border border-gray-100">
        <!-- Error Code -->
        <div class="mb-6">
            <h1 class="text-9xl md:text-[12rem] font-bold text-custom-pink leading-none">419</h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-custom-red mb-4">Page Expired</h2>
            <p class="text-gray-600 text-lg mb-2">Your session has expired due to inactivity.</p>
            <p class="text-gray-500">Please refresh the page and try again.</p>
        </div>

        <!-- Illustration or Icon -->
        <div class="mb-8">
            <svg class="w-48 h-48 mx-auto text-custom-pink/20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
            </svg>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.location.reload()"
                    class="px-8 py-3.5 bg-custom-pink text-white rounded-lg font-semibold hover:bg-custom-red shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                Refresh Page
            </button>

            <a href="{{ url('/') }}"
               class="px-8 py-3.5 bg-white text-custom-pink border-2 border-custom-pink rounded-lg font-semibold hover:bg-custom-pink hover:text-white shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                Go Back Home
            </a>
        </div>

        <!-- Additional Help -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-gray-600 text-sm">
                This usually happens when you've been inactive for too long.
            </p>
        </div>
    </div>
</body>
</html>
