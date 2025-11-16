<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Engineer's Matrimony</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Marko One' rel='stylesheet'>
    @fluxAppearance
    @vite(['resources/css/app.css'])
</head>

<body class="font-['Inter']">

    <!-- Navbar -->
    <nav class="static top-0 w-full bg-white shadow-md z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" wire:navigate class="font-bold">
                    Engineer's Matrimony
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
                    <a href="{{ route('home') }}"
                        class="text-gray-900 hover:text-custom-pink {{ Route::is('home') ? 'text-custom-pink font-bold' : '' }}">Home</a>
                    <a href="{{ route('your.connections') }}"
                        class="text-gray-900 hover:text-custom-pink {{ Route::is('your.connections') ? 'text-custom-pink font-bold' : '' }}">Your
                        Connections</a>

                    @if (auth()->check())
                        <flux:dropdown>
                            <flux:button variant="ghost"
                                class="{{ Route::is('payment.history') || Route::is('connection.history') ? 'text-custom-pink font-bold' : '' }}"
                                icon:trailing="chevron-down">History</flux:button>
                            <flux:menu>
                                <flux:menu.item href="{{ route('payment.history') }}" icon="credit-card">Payment
                                </flux:menu.item>
                                <flux:menu.item icon="users" href="{{ route('connection.history') }}">Connection
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                        <!-- History Dropdown -->
                    @endif

                    <a href="#" class="text-gray-600 hover:text-custom-pink">About Us</a>
                    <a href="#" class="text-gray-600 hover:text-custom-pink">FAQ</a>
                    <a href="#" class="text-gray-600 hover:text-custom-pink">Guide</a>
                    <a href="#" class="text-gray-600 hover:text-custom-pink">Contact</a>



                    @if (auth()->check())
                        <x-notification />
                        <a href="{{ route('profile', auth()->user()->id) }}"
                            class="bg-custom-pink text-white rounded-full px-6 py-2 hover:bg-opacity-90">My Profile</a>
                        @livewire('logout')
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
            <a href="{{ route('home') }}" class="text-gray-900 hover:text-custom-pink text-center">Home</a>
            @if (auth()->check())
                <a href="{{ route('your.connections') }}" class="text-gray-600 hover:text-custom-pink text-center">Your
                    Connections</a>
                <a href="{{ route('payment.history') }}"
                    class="text-gray-600 hover:text-custom-pink text-center flex justify-center items-center gap-1">
                    <flux:icon.credit-card />
                    Payment History</a>
                <a href="{{ route('connection.history') }}"
                    class="text-gray-600 hover:text-custom-pink text-center flex justify-center items-center gap-1">
                    <flux:icon.users />
                    Connection History
                </a>
            @endif
            <a href="#" class="text-gray-600 hover:text-custom-pink text-center">About Us</a>
            <a href="#" class="text-gray-600 hover:text-custom-pink text-center">FAQ</a>
            <a href="#" class="text-gray-600 hover:text-custom-pink text-center">Guide</a>
            <a href="#" class="text-gray-600 hover:text-custom-pink text-center">Contact</a>

            @if (auth()->check())
                <a href="{{ route('profile', auth()->user()->id) }}"
                    class="bg-custom-pink text-white rounded-full px-6 py-2 hover:bg-opacity-90">My Profile</a>
                @livewire('logout')
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
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
