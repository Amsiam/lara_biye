<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OrhdekDeen Matrimony</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Marko One' rel='stylesheet'>

    @vite(['resources/css/app.css'])
</head>

<body class="font-['Inter']">

    <!-- Navbar -->
    <nav class="static top-0 w-full bg-white shadow-md z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" wire:navigate class="font-bold">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-10">
                </a>

                <!-- Mobile menu button -->
                <button id="hamburger" class="md:hidden focus:outline-none mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-900 hover:text-custom-pink">Home</a>
                    <a href="#" class="text-gray-600 hover:text-custom-pink">About Us</a>
                    <a href="#" class="text-gray-600 hover:text-custom-pink">FAQ</a>
                    <a href="#" class="text-gray-600 hover:text-custom-pink">Guide</a>
                    <a href="#" class="text-gray-600 hover:text-custom-pink">Contact</a>

                    @if (auth()->check())
                        <a href="{{ route('profile', auth()->user()->id) }}"
                            class="bg-custom-pink text-white rounded-full px-6 py-2 hover:bg-opacity-90">My Profile</a>
                    @else
                        <a href="{{ route('register') }}"
                            class="bg-custom-pink text-white rounded-full px-6 py-2 hover:bg-opacity-90">Registration</a>
                        <a href="{{ route('login') }}"
                            class="border border-custom-pink text-custom-pink rounded-full px-6 py-2 hover:bg-custom-pink hover:text-white">Sign
                            In</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu (Initially Hidden) -->
    <div id="mobile-menu"
        class="md:hidden hidden absolute top-16 left-0 w-full bg-white shadow-md z-[100] transition-transform transform duration-500 ease-in-out">
        <div class="flex flex-col space-y-4 py-4 px-6">
            <a href="#" class="text-gray-900 hover:text-custom-pink text-center">Home</a>
            <a href="#" class="text-gray-600 hover:text-custom-pink text-center">About Us</a>
            <a href="#" class="text-gray-600 hover:text-custom-pink text-center">FAQ</a>
            <a href="#" class="text-gray-600 hover:text-custom-pink text-center">Guide</a>
            <a href="#" class="text-gray-600 hover:text-custom-pink text-center">Contact</a>

            @if (auth()->check())
            @else
                <a href="{{ route('register') }}"
                    class="bg-custom-pink text-white rounded-full px-6 py-2 hover:bg-opacity-90 text-center">Registration</a>
                <a href="{{ route('login') }}"
                    class="border border-custom-pink text-custom-pink rounded-full px-6 py-2 hover:bg-custom-pink hover:text-white text-center">Sign
                    In</a>
            @endif
        </div>
    </div>

    <!-- JavaScript to toggle mobile menu -->
    <script>
        // Select elements
        const hamburgerButton = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu');

        // Toggle mobile menu
        hamburgerButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (event) => {
            if (!mobileMenu.contains(event.target) && !hamburgerButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>

    <!-- Main Wrapper -->
    {{ $slot }}

</body>

</html>
