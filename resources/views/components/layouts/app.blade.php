<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Engineer's Matrimony</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <meta name="color-scheme" content="light only">
    <script>
        // Force light mode BEFORE Flux initializes
        localStorage.setItem('flux-appearance', 'light');
    </script>
    @vite(['resources/css/app.css'])
    @fluxAppearance
</head>

<body class="font-['Poppins'] bg-gray-100">

    <!-- Navbar -->
    <nav class="sticky top-0 w-full bg-white/95 backdrop-blur-md shadow-lg z-50 border-b border-gray-100">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" wire:navigate class="group flex items-center gap-2">
                    <div
                        class="bg-gradient-to-br from-custom-pink to-pink-600 p-2 rounded-lg shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all duration-300">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span
                        class="font-extrabold text-xl bg-gradient-to-r from-maroon to-custom-pink bg-clip-text text-transparent group-hover:scale-105 transition-transform duration-300">
                        Engineer's Matrimony
                    </span>
                </a>

                <!-- Mobile menu button -->
                <button id="hamburger"
                    class="md:hidden focus:outline-none p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('home') }}"
                        class="relative px-4 py-2 text-gray-700 font-semibold hover:text-custom-pink transition-colors group {{ Route::is('home') ? 'text-custom-pink' : '' }}">
                        Home
                        <span
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-custom-pink to-pink-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 {{ Route::is('home') ? 'scale-x-100' : '' }}"></span>
                    </a>
                    <a href="{{ route('your.connections') }}"
                        class="relative px-4 py-2 text-gray-700 font-semibold hover:text-custom-pink transition-colors group {{ Route::is('your.connections') ? 'text-custom-pink' : '' }}">
                        Connections
                        <span
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-custom-pink to-pink-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 {{ Route::is('your.connections') ? 'scale-x-100' : '' }}"></span>
                    </a>

                    @if (auth()->check())
                        <flux:dropdown>
                            <flux:button variant="ghost"
                                class="relative px-4 py-2 text-gray-700 font-semibold hover:text-custom-pink transition-colors {{ Route::is('payment.history') || Route::is('connection.history') ? 'text-custom-pink' : '' }}"
                                icon:trailing="chevron-down">History</flux:button>
                            <flux:menu>
                                <flux:menu.item href="{{ route('payment.history') }}" icon="credit-card">Payment
                                </flux:menu.item>
                                <flux:menu.item icon="users" href="{{ route('connection.history') }}">Connection
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    @endif

                    <a href="{{ route('about') }}"
                        class="relative px-4 py-2 text-gray-700 font-medium hover:text-custom-pink transition-colors group {{ Route::is('about') ? 'text-custom-pink' : '' }}">
                        About
                        <span
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-custom-pink to-pink-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 {{ Route::is('about') ? 'scale-x-100' : '' }}"></span>
                    </a>
                    <a href="{{ route('faq') }}"
                        class="relative px-4 py-2 text-gray-700 font-medium hover:text-custom-pink transition-colors group {{ Route::is('faq') ? 'text-custom-pink' : '' }}">
                        FAQ
                        <span
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-custom-pink to-pink-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 {{ Route::is('faq') ? 'scale-x-100' : '' }}"></span>
                    </a>
                    <a href="{{ route('contact') }}"
                        class="relative px-4 py-2 text-gray-700 font-medium hover:text-custom-pink transition-colors group {{ Route::is('contact') ? 'text-custom-pink' : '' }}">
                        Contact
                        <span
                            class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-custom-pink to-pink-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 {{ Route::is('contact') ? 'scale-x-100' : '' }}"></span>
                    </a>

                    <div class="flex items-center gap-3 ml-4 pl-4 border-l border-gray-200">
                        @if (auth()->check())
                            <x-notification />
                            <a href="{{ route('profile', auth()->user()->id) }}"
                                class="bg-gradient-to-r from-custom-pink to-pink-600 text-white rounded-full px-6 py-2.5 font-semibold shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300">
                                My Profile
                            </a>
                            @livewire('logout')
                        @else
                            <a href="{{ route('register') }}"
                                class="bg-gradient-to-r from-custom-pink to-pink-600 text-white rounded-full px-6 py-2.5 font-semibold shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300">
                                Registration
                            </a>
                            <a href="{{ route('login') }}"
                                class="border-2 border-custom-pink text-custom-pink rounded-full px-6 py-2.5 font-semibold hover:bg-custom-pink hover:text-white transition-all duration-300 hover:scale-105">
                                Sign In
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu (Initially Hidden) -->
    <div id="mobile-menu"
        class="md:hidden hidden absolute top-20 left-0 w-full bg-white/95 backdrop-blur-md shadow-2xl z-[100] border-b border-gray-200">
        <div class="flex flex-col space-y-2 py-6 px-6">
            <a href="{{ route('home') }}"
                class="px-4 py-3 text-gray-700 font-semibold hover:text-custom-pink hover:bg-custom-pink/5 rounded-lg text-center transition-all {{ Route::is('home') ? 'text-custom-pink bg-custom-pink/10' : '' }}">
                Home
            </a>
            @if (auth()->check())
                <a href="{{ route('your.connections') }}"
                    class="px-4 py-3 text-gray-700 font-medium hover:text-custom-pink hover:bg-custom-pink/5 rounded-lg text-center transition-all {{ Route::is('your.connections') ? 'text-custom-pink bg-custom-pink/10' : '' }}">
                    Your Connections
                </a>
                <a href="{{ route('payment.history') }}"
                    class="px-4 py-3 text-gray-700 font-medium hover:text-custom-pink hover:bg-custom-pink/5 rounded-lg text-center flex justify-center items-center gap-2 transition-all {{ Route::is('payment.history') ? 'text-custom-pink bg-custom-pink/10' : '' }}">
                    <flux:icon.credit-card class="w-5 h-5" />
                    Payment History
                </a>
                <a href="{{ route('connection.history') }}"
                    class="px-4 py-3 text-gray-700 font-medium hover:text-custom-pink hover:bg-custom-pink/5 rounded-lg text-center flex justify-center items-center gap-2 transition-all {{ Route::is('connection.history') ? 'text-custom-pink bg-custom-pink/10' : '' }}">
                    <flux:icon.users class="w-5 h-5" />
                    Connection History
                </a>
            @endif

            <div class="border-t border-gray-200 my-2"></div>

            <a href="{{ route('about') }}"
                class="px-4 py-3 text-gray-700 font-medium hover:text-custom-pink hover:bg-custom-pink/5 rounded-lg text-center transition-all {{ Route::is('about') ? 'text-custom-pink bg-custom-pink/10' : '' }}">About
                Us</a>
            <a href="{{ route('faq') }}"
                class="px-4 py-3 text-gray-700 font-medium hover:text-custom-pink hover:bg-custom-pink/5 rounded-lg text-center transition-all {{ Route::is('faq') ? 'text-custom-pink bg-custom-pink/10' : '' }}">FAQ</a>
            <a href="{{ route('contact') }}"
                class="px-4 py-3 text-gray-700 font-medium hover:text-custom-pink hover:bg-custom-pink/5 rounded-lg text-center transition-all {{ Route::is('contact') ? 'text-custom-pink bg-custom-pink/10' : '' }}">Contact</a>

            <div class="border-t border-gray-200 my-2"></div>

            @if (auth()->check())
                <a href="{{ route('profile', auth()->user()->id) }}"
                    class="bg-gradient-to-r from-custom-pink to-pink-600 text-white rounded-full px-6 py-3 font-semibold shadow-md hover:shadow-lg text-center transition-all">
                    My Profile
                </a>
                @livewire('logout')
            @else
                <a href="{{ route('register') }}"
                    class="bg-gradient-to-r from-custom-pink to-pink-600 text-white rounded-full px-6 py-3 font-semibold shadow-md hover:shadow-lg text-center transition-all">
                    Registration
                </a>
                <a href="{{ route('login') }}"
                    class="border-2 border-custom-pink text-custom-pink rounded-full px-6 py-3 font-semibold hover:bg-custom-pink hover:text-white text-center transition-all">
                    Sign In
                </a>
            @endif
        </div>
    </div>

    <!-- JavaScript to toggle mobile menu -->
    <script>
        // Select elements
        const hamburgerButton = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu');
        const historyDropdownButton = document.getElementById('history-dropdown-button');
        const historyDropdown = document.getElementById('history-dropdown');

        // Toggle mobile menu
        if (hamburgerButton && mobileMenu) {
            hamburgerButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Toggle history dropdown
        if (historyDropdownButton && historyDropdown) {
            historyDropdownButton.addEventListener('click', (e) => {
                e.stopPropagation();
                historyDropdown.classList.toggle('hidden');
            });
        }

        // Close menus when clicking outside
        document.addEventListener('click', (event) => {
            // Close mobile menu
            if (mobileMenu && hamburgerButton && !mobileMenu.contains(event.target) && !hamburgerButton.contains(
                    event.target)) {
                mobileMenu.classList.add('hidden');
            }

            // Close history dropdown
            if (historyDropdown && historyDropdownButton && !historyDropdown.contains(event.target) && !
                historyDropdownButton.contains(event.target)) {
                historyDropdown.classList.add('hidden');
            }
        });

        // Hide error message after 3 seconds
        setTimeout(() => {
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                errorMessage.remove();
            }
        }, 3000);
    </script>


    <div>
        @if (session()->has('error'))
            <div id="error-message"
                class="fixed z-30 top-0 right-0 mt-4 mr-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Main Wrapper -->
    {{ $slot }}
    @fluxScripts
    <script>
        // Force Flux UI to always use light mode using global Flux object
        document.addEventListener('DOMContentLoaded', function() {
            // Set appearance to light using global Flux object
            Flux.appearance = 'light';

            // Also explicitly set dark to false
            Flux.dark = false;
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
