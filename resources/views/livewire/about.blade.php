<?php

use function Livewire\Volt\{state};

?>

<div class="min-h-screen bg-gradient-to-b from-white to-custom-pink/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-custom-red mb-4">About Us</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Welcome to {{ config('app.name') }}, where we help you find your perfect life partner
            </p>
        </div>

        <!-- Mission & Vision Section -->
        <div class="grid md:grid-cols-2 gap-8 mb-16">
            <div class="bg-white rounded-xl shadow-lg p-8 border border-custom-pink/20 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-custom-pink/10 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-custom-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-custom-red ml-4">Our Mission</h2>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Our mission is to create a safe, secure, and trustworthy platform where individuals can find their ideal life partners. We believe in connecting hearts with compatibility, values, and genuine intentions at the core of every match.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8 border border-custom-pink/20 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-custom-pink/10 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-custom-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-custom-red ml-4">Our Vision</h2>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    We envision a world where finding a life partner is accessible, transparent, and based on mutual respect and understanding. Our platform aims to be the most trusted matrimonial service, bringing families together.
                </p>
            </div>
        </div>

        <!-- What Makes Us Different -->
        <div class="bg-white rounded-xl shadow-lg p-8 md:p-12 mb-16 border border-custom-pink/20">
            <h2 class="text-3xl font-bold text-custom-red mb-8 text-center">What Makes Us Different</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-custom-pink/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-custom-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Verified Profiles</h3>
                    <p class="text-gray-600">
                        All profiles are verified with NID and Student ID to ensure authenticity and safety
                    </p>
                </div>

                <div class="text-center">
                    <div class="bg-custom-pink/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-custom-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Advanced Search</h3>
                    <p class="text-gray-600">
                        Find matches based on religion, education, location, and personal preferences
                    </p>
                </div>

                <div class="text-center">
                    <div class="bg-custom-pink/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-custom-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Privacy Control</h3>
                    <p class="text-gray-600">
                        You control who can see your profile and contact information
                    </p>
                </div>
            </div>
        </div>

        <!-- Our Values -->
        <div class="bg-gradient-to-r from-custom-pink to-custom-red rounded-xl shadow-lg p-8 md:p-12 mb-16 text-white">
            <h2 class="text-3xl font-bold mb-8 text-center">Our Core Values</h2>
            <div class="grid md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-4xl mb-3">🤝</div>
                    <h3 class="text-lg font-bold mb-2">Trust</h3>
                    <p class="text-white/90 text-sm">Building genuine connections through verified profiles</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">🔒</div>
                    <h3 class="text-lg font-bold mb-2">Privacy</h3>
                    <p class="text-white/90 text-sm">Your data and privacy are our top priority</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">💖</div>
                    <h3 class="text-lg font-bold mb-2">Respect</h3>
                    <p class="text-white/90 text-sm">Treating every member with dignity and care</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">✨</div>
                    <h3 class="text-lg font-bold mb-2">Quality</h3>
                    <p class="text-white/90 text-sm">Providing the best matchmaking experience</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center border border-custom-pink/20">
                <div class="text-3xl font-bold text-custom-pink mb-2">5000+</div>
                <div class="text-gray-600">Active Profiles</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center border border-custom-pink/20">
                <div class="text-3xl font-bold text-custom-pink mb-2">1000+</div>
                <div class="text-gray-600">Success Stories</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center border border-custom-pink/20">
                <div class="text-3xl font-bold text-custom-pink mb-2">100%</div>
                <div class="text-gray-600">Verified Profiles</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center border border-custom-pink/20">
                <div class="text-3xl font-bold text-custom-pink mb-2">24/7</div>
                <div class="text-gray-600">Support</div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-white rounded-xl shadow-lg p-8 md:p-12 text-center border border-custom-pink/20">
            <h2 class="text-3xl font-bold text-custom-red mb-4">Ready to Find Your Perfect Match?</h2>
            <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                Join thousands of happy members who found their life partners through our platform. Start your journey today!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('search') }}"
                        class="px-8 py-3 bg-custom-pink text-white rounded-lg font-semibold hover:bg-custom-red shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Start Searching
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="px-8 py-3 bg-custom-pink text-white rounded-lg font-semibold hover:bg-custom-red shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Create Account
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-8 py-3 bg-white text-custom-pink border-2 border-custom-pink rounded-lg font-semibold hover:bg-custom-pink hover:text-white shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
