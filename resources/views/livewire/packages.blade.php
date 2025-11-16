<?php
use App\Models\Package;
use function Livewire\Volt\{computed};

$packages = computed(function () {
    return Package::where('is_active', true)->orderBy('price', 'asc')->get();
});

?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Package</h1>
            <p class="text-lg text-gray-600">Select the perfect plan to find your life partner</p>
        </div>

        <!-- Packages Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach ($this->packages as $package)
                <div class="relative bg-white rounded-lg shadow-lg overflow-hidden border-2 {{ $package->is_popular ? 'border-custom-pink' : 'border-gray-200' }} hover:shadow-xl transition-shadow duration-300">

                    @if ($package->is_popular)
                        <div class="absolute top-0 right-0 bg-custom-pink text-white px-3 py-1 text-xs font-bold rounded-bl-lg">
                            POPULAR
                        </div>
                    @endif

                    <div class="p-6">
                        <!-- Package Name -->
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-6 h-12">{{ $package->description }}</p>

                        <!-- Price -->
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900">৳{{ number_format($package->price, 0) }}</span>
                        </div>

                        <!-- Connections -->
                        <div class="mb-6">
                            <div class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 mr-2 text-custom-pink" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-semibold">{{ $package->connections }} Profile Views</span>
                            </div>
                            <div class="flex items-center text-gray-700 mt-2">
                                <svg class="w-5 h-5 mr-2 text-custom-pink" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Send Connection Requests</span>
                            </div>
                            <div class="flex items-center text-gray-700 mt-2">
                                <svg class="w-5 h-5 mr-2 text-custom-pink" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Full Profile Access</span>
                            </div>
                        </div>

                        <!-- Button -->
                        <a href="/payment/bkash/{{ $package->id }}"
                           class="block w-full text-center px-4 py-3 rounded-lg font-semibold transition-colors duration-200 {{ $package->is_popular ? 'bg-custom-pink text-white hover:bg-opacity-90' : 'bg-gray-900 text-white hover:bg-gray-800' }}">
                            Choose {{ $package->name }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Features Section -->
        <div class="bg-white rounded-lg shadow-md p-8 mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">All Packages Include</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-custom-pink mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Verified Profiles</h3>
                        <p class="text-gray-600 text-sm">Access to verified engineer profiles</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-custom-pink mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Secure Platform</h3>
                        <p class="text-gray-600 text-sm">Your privacy and security guaranteed</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-custom-pink mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">24/7 Support</h3>
                        <p class="text-gray-600 text-sm">Get help whenever you need it</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8">
            <a href="/dashboard" class="text-custom-pink hover:text-custom-red font-semibold">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</div>
