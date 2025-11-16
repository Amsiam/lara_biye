<?php

use function Livewire\Volt\{layout, computed};
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

layout('components.layouts.app');

$createProfile = function () {
    return redirect()->route('register');
};

// Computed properties for statistics with caching
$totalProfiles = computed(function () {
    return Cache::remember('stats.total_profiles', 3600, function () {
        $dbCount = User::where('is_admin', false)->whereHas('basicInfo')->count();
        return 5165 + $dbCount; // Adding to base value
    });
});

$groomProfiles = computed(function () {
    return Cache::remember('stats.groom_profiles', 3600, function () {
        $dbCount = User::where('is_admin', false)->whereHas('basicInfo', function ($query) {
            $query->where('gender', 'Male');
        })->count();
        return 2184 + $dbCount; // Adding to base value
    });
});

$brideProfiles = computed(function () {
    return Cache::remember('stats.bride_profiles', 3600, function () {
        $dbCount = User::where('is_admin', false)->whereHas('basicInfo', function ($query) {
            $query->where('gender', 'Female');
        })->count();
        return 2981 + $dbCount; // Adding to base value
    });
});

?>

<div>
    <section id="home"
        class="min-h-screen flex flex-col lg:flex-row items-center bg-gradient-to-br from-maroon via-[#490b22] to-maroon text-white pt-16 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-10 left-10 w-72 h-72 bg-custom-pink rounded-full mix-blend-multiply filter blur-3xl animate-pulse">
            </div>
            <div
                class="absolute bottom-10 right-10 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse delay-1000">
            </div>
        </div>

        <div class="container mx-auto px-4 flex flex-col lg:flex-row items-center relative z-10">
            <!-- Left Content (Hero Section) -->
            <div class="w-full lg:w-1/2 text-center lg:text-left px-4 animate-fade-in">
                <div class="inline-block mb-4">
                    <span
                        class="bg-custom-pink/20 text-custom-pink px-4 py-2 rounded-full text-sm font-semibold border border-custom-pink/30 backdrop-blur-sm">
                        ✨ #1 Matrimony Platform for Engineers
                    </span>
                </div>

                <h1
                    class="text-4xl md:text-6xl font-extrabold leading-tight mb-6 bg-gradient-to-r from-white via-pink-100 to-custom-pink bg-clip-text text-transparent animate-gradient">
                    Find Your Perfect<br>
                    <span class="text-custom-pink drop-shadow-lg">Life Partner</span>
                </h1>

                <p class="mt-4 text-lg md:text-xl text-gray-200 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                    Connect with verified engineers. Build your future together with trust and authenticity.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8 justify-center lg:justify-start">
                    <button wire:click="createProfile"
                        class="group bg-custom-pink text-white px-8 py-4 rounded-full font-bold text-lg shadow-2xl hover:shadow-custom-pink/50 transform hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
                        <span>Get Started Free</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>

                    <a href="{{ route('search') }}"
                        class="bg-white/10 backdrop-blur-md text-white border-2 border-white/30 px-8 py-4 rounded-full font-bold text-lg hover:bg-white/20 transform hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Browse Profiles</span>
                    </a>
                </div>

                <!-- Ratings & Profiles -->
                <div class="flex items-center mt-12 justify-center lg:justify-start gap-6 flex-wrap">
                    <div class="flex -space-x-3">
                        <img src="{{ asset('img/hero-img1.png') }}"
                            class="w-12 h-12 rounded-full border-4 border-maroon hover:scale-110 transition-transform cursor-pointer"
                            alt="Profile 1">
                        <img src="{{ asset('img/hero-img2.png') }}"
                            class="w-12 h-12 rounded-full border-4 border-maroon hover:scale-110 transition-transform cursor-pointer"
                            alt="Profile 2">
                        <img src="{{ asset('img/hero-img3.png') }}"
                            class="w-12 h-12 rounded-full border-4 border-maroon hover:scale-110 transition-transform cursor-pointer"
                            alt="Profile 3">
                        <img src="{{ asset('img/hero-img4.png') }}"
                            class="w-12 h-12 rounded-full border-4 border-maroon hover:scale-110 transition-transform cursor-pointer"
                            alt="Profile 4">
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 rounded-full">
                        <div class="flex text-yellow-400">
                            ⭐⭐⭐⭐⭐
                        </div>
                        <p class="text-lg font-bold">{{ Setting::get('stats.review_average', '4.7') }}/5</p>
                        <span
                            class="text-sm text-gray-300">({{ number_format(Setting::get('stats.total_reviews', '1200')) }}
                            reviews)</span>
                    </div>
                </div>
            </div>

            <!-- Right Image (Couple) -->
            <div class="w-full lg:w-1/2 mt-12 lg:mt-0 relative h-[600px] hidden sm:block">
                <div class="absolute inset-0 bg-gradient-to-tr from-custom-pink/20 to-transparent rounded-3xl"></div>
                <img src="{{ asset('img/Rectangle 8775.png') }}" class="absolute inset-0 mx-auto w-3/4 animate-float">
                <img src="{{ asset('img/couple.png') }}" class="absolute inset-0 mx-auto w-2/3 animate-float-delayed">
            </div>
        </div>
    </section>

    <!-- Search Section (Enhanced) -->
    <section class="w-full bg-gradient-to-br from-gray-50 to-gray-100 py-16 relative">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="mb-12">
                <span
                    class="inline-block bg-custom-pink/10 text-custom-pink px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    🔍 Quick Search
                </span>
                <h3 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">
                    Find Your <span class="text-custom-pink">Perfect Match</span>
                </h3>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Use our advanced filters to discover profiles that match your preferences
                </p>
            </div>

            <form action="{{ route('search') }}">
                <div class="max-w-5xl mx-auto">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 backdrop-blur-sm border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Gender -->
                            <x-select-input name="gender" placeholder="I'm looking for" :options="['Male' => 'Male', 'Female' => 'Female']"
                                :icon="'<svg class=\'h-5 w-5 text-custom-pink\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\' /></svg>'" />

                            <!-- Marital Status -->
                            <x-select-input name="marital_status" placeholder="Marital Status" :options="[
                                'UNMARRIED' => 'Unmarried',
                                'MARRIED' => 'Married',
                                'DIVORCED' => 'Divorced',
                                'WIDOWED' => 'Widowed',
                            ]"
                                :icon="'<svg class=\'h-5 w-5 text-custom-pink\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z\' /></svg>'" />

                            <!-- Age -->
                            <x-select-input name="age" placeholder="Select Age" :options="[
                                '18-25' => '18-25 years',
                                '26-35' => '26-35 years',
                                '36-45' => '36-45 years',
                                '46-55' => '46-55 years',
                                '56-65' => '56-65 years',
                            ]"
                                :icon="'<svg class=\'h-5 w-5 text-custom-pink\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\' /></svg>'" />

                            <!-- Search Button -->
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-custom-pink to-pink-600 text-white py-4 px-6 rounded-xl hover:shadow-2xl hover:scale-105 font-bold text-lg transition-all duration-300 flex items-center justify-center gap-2 group">
                                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span>Search Now</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Create Bio data Section -->
    <section class="min-h-screen flex flex-col items-center py-20 relative bg-gradient-to-b from-white to-gray-50">
        <img src="{{ asset('img/image 70.png') }}" alt=""
            class="absolute left-0 top-1/2 -translate-y-1/2 hidden md:block opacity-30">
        <div class="container mx-auto px-4 text-center relative z-10">
            <span
                class="inline-block bg-custom-pink/10 text-custom-pink px-6 py-3 rounded-full text-sm font-bold mb-6 border border-custom-pink/20">
                🎉 Get Started Today
            </span>
            <h2 class="text-4xl md:text-5xl font-extrabold leading-tight mb-6">
                Create Profile in <span class="text-custom-pink">Engineer's Matrimony</span><br>
                <span
                    class="text-3xl md:text-4xl bg-gradient-to-r from-custom-pink to-pink-600 bg-clip-text text-transparent">Completely
                    Free</span>
            </h2>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg mb-12">
                Join thousands of engineers who found their life partner through our platform
            </p>

            <div class="flex flex-col md:flex-row justify-center mt-12 gap-8 max-w-4xl mx-auto">
                <div wire:click="createProfile"
                    class="group bg-gradient-to-br from-custom-pink to-pink-600 p-1 rounded-2xl shadow-2xl hover:shadow-custom-pink/50 transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="bg-white p-8 md:p-12 rounded-2xl h-full flex flex-col items-center">
                        <div
                            class="bg-custom-pink/10 p-6 rounded-full mb-6 group-hover:scale-110 transition-transform duration-300">
                            <img src="{{ asset('img/image 72.png') }}" alt="Icon" class="w-16 h-16">
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Create Your Profile</h3>
                        <p class="text-custom-pink font-semibold text-lg mb-4">+ Start Your Journey</p>
                        <p class="text-gray-600 text-sm">Quick & Easy - Takes only 5 minutes</p>
                    </div>
                </div>

                <div
                    class="group bg-white border-2 border-custom-pink p-8 md:p-12 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div
                        class="bg-custom-pink/10 p-6 rounded-full mb-6 mx-auto w-fit group-hover:scale-110 transition-transform duration-300">
                        <img src="{{ asset('img/Vector.png') }}" alt="Icon" class="w-16 h-16">
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">How To Create Profile</h3>
                    <p class="text-custom-pink font-semibold text-lg mb-4">📖 Step-by-Step Guide</p>
                    <p class="text-gray-600 text-sm">Learn the process in detail</p>
                </div>
            </div>
        </div>
        <img src="{{ asset('img/image 71.png') }}" alt=""
            class="absolute right-0 top-1/2 -translate-y-1/2 hidden md:block opacity-30">
    </section>



    <!-- How It Works Section -->
    <section
        class="min-h-screen flex items-center bg-gradient-to-br from-[#490b22] via-maroon to-[#490b22] py-20 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 opacity-5">
            <div
                class="absolute top-20 left-20 w-96 h-96 bg-custom-pink rounded-full mix-blend-multiply filter blur-3xl animate-pulse">
            </div>
            <div
                class="absolute bottom-20 right-20 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse delay-1000">
            </div>
        </div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="mb-16">
                <span
                    class="inline-block bg-custom-pink/20 text-custom-pink px-6 py-3 rounded-full text-sm font-bold mb-6 border border-custom-pink/30 backdrop-blur-sm">
                    ⚡ Quick Access
                </span>
                <h3 class="text-4xl md:text-5xl font-extrabold text-white mb-4">
                    How <span
                        class="bg-gradient-to-r from-custom-pink to-pink-300 bg-clip-text text-transparent">Engineer's
                        Matrimony</span> Works
                </h3>
                <p class="text-gray-300 max-w-2xl mx-auto text-lg">
                    Four simple steps to find your perfect life partner
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <!-- Step 1 -->
                <div class="group relative h-full">
                    <div
                        class="relative bg-white p-8 rounded-2xl shadow-lg border-2 border-gray-100 transition-all duration-300 hover:shadow-2xl hover:border-custom-pink/30 hover:scale-105 hover:-translate-y-2 h-full flex flex-col">
                        <div
                            class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-gradient-to-br from-custom-pink to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            1
                        </div>
                        <div
                            class="bg-custom-pink/10 p-4 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <img src="{{ asset('img/Group.png') }}" alt="Find Match" class="w-12 h-12">
                        </div>
                        <h5 class="text-xl font-bold mb-3 text-gray-900">Find Match</h5>
                        <p class="text-gray-600 leading-relaxed">Use advanced filters to search your desired life
                            partner</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="group relative h-full">
                    <div
                        class="relative bg-white p-8 rounded-2xl shadow-lg border-2 border-gray-100 transition-all duration-300 hover:shadow-2xl hover:border-custom-pink/30 hover:scale-105 hover:-translate-y-2 h-full flex flex-col">
                        <div
                            class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-gradient-to-br from-custom-pink to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            2
                        </div>
                        <div
                            class="bg-custom-pink/10 p-4 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <img src="{{ asset('img/Vector(2).png') }}" alt="Create Profile" class="w-12 h-12">
                        </div>
                        <h5 class="text-xl font-bold mb-3 text-gray-900">Create Profile</h5>
                        <p class="text-gray-600 leading-relaxed">Create your profile for free in just a few steps</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="group relative h-full">
                    <div
                        class="relative bg-white p-8 rounded-2xl shadow-lg border-2 border-gray-100 transition-all duration-300 hover:shadow-2xl hover:border-custom-pink/30 hover:scale-105 hover:-translate-y-2 h-full flex flex-col">
                        <div
                            class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-gradient-to-br from-custom-pink to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            3
                        </div>
                        <div
                            class="bg-custom-pink/10 p-4 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <img src="{{ asset('img/Group(1).png') }}" alt="Start Communication" class="w-12 h-12">
                        </div>
                        <h5 class="text-xl font-bold mb-3 text-gray-900">Start Communication</h5>
                        <p class="text-gray-600 leading-relaxed">Connect and communicate with suitable profiles</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="group relative h-full">
                    <div
                        class="relative bg-white p-8 rounded-2xl shadow-lg border-2 border-gray-100 transition-all duration-300 hover:shadow-2xl hover:border-custom-pink/30 hover:scale-105 hover:-translate-y-2 h-full flex flex-col">
                        <div
                            class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-gradient-to-br from-custom-pink to-pink-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            4
                        </div>
                        <div
                            class="bg-custom-pink/10 p-4 rounded-full w-20 h-20 mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <img src="{{ asset('img/Vector(3).png') }}" alt="Get Married" class="w-12 h-12">
                        </div>
                        <h5 class="text-xl font-bold mb-3 text-gray-900">Get Married</h5>
                        <p class="text-gray-600 leading-relaxed">Finalize your decision and start your journey</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIP Assistant Service Section -->
    <section id="guide" class="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <!-- Top Centered Heading -->
            <div class="text-center mb-16">
                <span
                    class="inline-block bg-custom-pink/10 text-custom-pink px-6 py-3 rounded-full text-sm font-bold mb-6 border border-custom-pink/20">
                    👑 Premium Service
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold mb-4">
                    <span
                        class="bg-gradient-to-r from-custom-pink to-pink-600 bg-clip-text text-transparent">VIP</span>
                    <span class="text-gray-900">Assistant Service</span>
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Get personalized assistance to find your perfect match faster
                </p>
            </div>

            <!-- Content Flexbox -->
            <div class="flex flex-col lg:flex-row items-center justify-center gap-12 max-w-6xl mx-auto">
                <!-- Left Illustration -->
                <div class="w-full lg:w-1/2 flex justify-center">
                    <div class="relative group">
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-custom-pink to-pink-600 rounded-3xl blur-2xl opacity-20 group-hover:opacity-30 transition duration-300">
                        </div>
                        <div
                            class="relative max-w-sm w-full bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl p-8 transform group-hover:scale-105 transition-all duration-300 border-2 border-gray-100">
                            <!-- VIP Icon -->
                            <div class="flex justify-center mb-6">
                                <div class="bg-gradient-to-br from-custom-pink to-pink-600 p-5 rounded-full shadow-xl">
                                    <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Decorative Elements -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-custom-pink/10 p-2 rounded-full flex-shrink-0">
                                        <svg class="w-5 h-5 text-custom-pink" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div
                                        class="flex-1 h-2 bg-gradient-to-r from-custom-pink/30 to-transparent rounded-full">
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="bg-custom-pink/10 p-2 rounded-full flex-shrink-0">
                                        <svg class="w-5 h-5 text-custom-pink" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div
                                        class="flex-1 h-2 bg-gradient-to-r from-custom-pink/30 to-transparent rounded-full">
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="bg-custom-pink/10 p-2 rounded-full flex-shrink-0">
                                        <svg class="w-5 h-5 text-custom-pink" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div
                                        class="flex-1 h-2 bg-gradient-to-r from-custom-pink/30 to-transparent rounded-full">
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="bg-custom-pink/10 p-2 rounded-full flex-shrink-0">
                                        <svg class="w-5 h-5 text-custom-pink" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div
                                        class="flex-1 h-2 bg-gradient-to-r from-custom-pink/30 to-transparent rounded-full">
                                    </div>
                                </div>
                            </div>

                            <!-- VIP Badge -->
                            <div class="mt-6 text-center">
                                <div
                                    class="inline-block bg-gradient-to-r from-custom-pink to-pink-600 text-white px-5 py-1.5 rounded-full font-bold text-xs shadow-lg">
                                    Premium Service
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content -->
                <div class="w-full lg:w-1/2">
                    <!-- Timeline -->
                    <div class="relative space-y-6">
                        <div class="absolute left-3 top-0 bottom-0 w-1 bg-gradient-to-b from-custom-pink to-pink-400">
                        </div>

                        <!-- Timeline Item 1 -->
                        <div class="flex items-start gap-4 relative group">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-custom-pink to-pink-600 border-4 border-white shadow-lg z-10 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-xs font-bold">1</span>
                            </div>
                            <div
                                class="flex-1 bg-white border-2 border-custom-pink/20 text-gray-800 rounded-xl px-6 py-4 shadow-md hover:shadow-xl hover:border-custom-pink/40 transition-all duration-300">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">👤</span>
                                    <span class="font-bold text-custom-pink">Assign Personal Advisor</span>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Item 2 -->
                        <div class="flex items-start gap-4 relative group">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-custom-pink to-pink-600 border-4 border-white shadow-lg z-10 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-xs font-bold">2</span>
                            </div>
                            <div
                                class="flex-1 bg-white border-2 border-custom-pink/20 text-gray-800 rounded-xl px-6 py-4 shadow-md hover:shadow-xl hover:border-custom-pink/40 transition-all duration-300">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">📋</span>
                                    <span class="font-bold text-custom-pink">Advisor Will Manage Your Profile</span>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Item 3 -->
                        <div class="flex items-start gap-4 relative group">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-custom-pink to-pink-600 border-4 border-white shadow-lg z-10 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-xs font-bold">3</span>
                            </div>
                            <div
                                class="flex-1 bg-white border-2 border-custom-pink/20 text-gray-800 rounded-xl px-6 py-4 shadow-md hover:shadow-xl hover:border-custom-pink/40 transition-all duration-300">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">💝</span>
                                    <span class="font-bold text-custom-pink">Handpick Matches For You</span>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Item 4 -->
                        <div class="flex items-start gap-4 relative group">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-custom-pink to-pink-600 border-4 border-white shadow-lg z-10 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <span class="text-white text-xs font-bold">4</span>
                            </div>
                            <div
                                class="flex-1 bg-white border-2 border-custom-pink/20 text-gray-800 rounded-xl px-6 py-4 shadow-md hover:shadow-xl hover:border-custom-pink/40 transition-all duration-300">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">🤝</span>
                                    <span class="font-bold text-custom-pink">Arranging Meetings</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button - Centered at the bottom -->
            <div class="mt-16 text-center w-full">
                <button
                    class="group bg-gradient-to-r from-custom-pink to-pink-600 text-white px-10 py-4 rounded-full font-bold text-lg shadow-2xl hover:shadow-custom-pink/50 transform hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 mx-auto">
                    <span>View More Details</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </div>
        </div>
    </section>
    <!-- Why Choose Us Section -->
    <section
        class="bg-gradient-to-br from-maroon via-[#490b22] to-maroon min-h-screen flex items-center py-20 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-20 left-20 w-96 h-96 bg-custom-pink rounded-full mix-blend-multiply filter blur-3xl animate-pulse">
            </div>
            <div
                class="absolute bottom-20 right-20 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse delay-1000">
            </div>
        </div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="mb-16">
                <span
                    class="inline-block bg-custom-pink/20 text-custom-pink px-6 py-3 rounded-full text-sm font-bold mb-6 border border-custom-pink/30 backdrop-blur-sm">
                    🏆 #1 WEDDING WEBSITE
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6 text-white">
                    Why <span
                        class="bg-gradient-to-r from-custom-pink to-pink-300 bg-clip-text text-transparent">Choose
                        Us</span>
                </h2>
                <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                    The Most Trusted and Premium Matrimony Service for Engineers
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 max-w-5xl mx-auto">
                <!-- Feature 1 -->
                <div class="group relative">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-custom-pink to-pink-600 rounded-2xl blur opacity-40 group-hover:opacity-100 transition duration-300">
                    </div>
                    <div
                        class="relative bg-white p-8 rounded-2xl shadow-2xl transition-all duration-300 hover:scale-105 hover:-translate-y-2">
                        <div
                            class="bg-gradient-to-br from-custom-pink/10 to-pink-100 p-5 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <img src="{{ asset('img/image 24.png') }}" class="w-16 h-16" alt="Verified Engineers">
                        </div>
                        <h5 class="text-xl font-bold text-gray-900 mb-3">Verified Engineers</h5>
                        <p class="text-gray-600 leading-relaxed">All profiles are thoroughly verified with their
                            student ID for authenticity</p>
                        <div class="mt-4 flex items-center justify-center gap-2 text-custom-pink font-semibold">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm">100% Verified</span>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="group relative">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-custom-pink to-pink-600 rounded-2xl blur opacity-40 group-hover:opacity-100 transition duration-300">
                    </div>
                    <div
                        class="relative bg-white p-8 rounded-2xl shadow-2xl transition-all duration-300 hover:scale-105 hover:-translate-y-2">
                        <div
                            class="bg-gradient-to-br from-custom-pink/10 to-pink-100 p-5 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <img src="{{ asset('img/image 23.png') }}" class="w-16 h-16" alt="AI Based Matching">
                        </div>
                        <h5 class="text-xl font-bold text-gray-900 mb-3">AI Based Matching</h5>
                        <p class="text-gray-600 leading-relaxed">Our advanced AI algorithm suggests the best matches
                            based on your preferences</p>
                        <div class="mt-4 flex items-center justify-center gap-2 text-custom-pink font-semibold">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 7H7v6h6V7z" />
                                <path fill-rule="evenodd"
                                    d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm">Smart Algorithm</span>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="group relative">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-custom-pink to-pink-600 rounded-2xl blur opacity-40 group-hover:opacity-100 transition duration-300">
                    </div>
                    <div
                        class="relative bg-white p-8 rounded-2xl shadow-2xl transition-all duration-300 hover:scale-105 hover:-translate-y-2">
                        <div
                            class="bg-gradient-to-br from-custom-pink/10 to-pink-100 p-5 rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <img src="{{ asset('img/Group 4.png') }}" class="w-16 h-16" alt="Success Stories">
                        </div>
                        <h5 class="text-xl font-bold text-gray-900 mb-3">1600+ Weddings</h5>
                        <p class="text-gray-600 leading-relaxed">Thousands of engineers have successfully found their
                            life partner through us</p>
                        <div class="mt-4 flex items-center justify-center gap-2 text-custom-pink font-semibold">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm">Success Stories</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="bg-gradient-to-b from-white to-gray-50 py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-12">
                <span
                    class="inline-block bg-custom-pink/10 text-custom-pink px-6 py-3 rounded-full text-sm font-bold mb-6 border border-custom-pink/20">
                    📊 Our Impact
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">
                    Trusted by <span class="text-custom-pink">Thousands</span>
                </h2>
            </div>

            <div class="max-w-6xl mx-auto bg-white rounded-3xl shadow-2xl border-2 border-gray-100 p-8 md:p-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <!-- Stat 1 - Total Profiles -->
                    <div class="group relative">
                        <div
                            class="absolute -inset-2 bg-gradient-to-r from-custom-pink to-pink-600 rounded-2xl blur opacity-0 group-hover:opacity-20 transition duration-300">
                        </div>
                        <div class="relative">
                            <div
                                class="bg-gradient-to-br from-custom-pink/10 to-pink-100 p-4 rounded-2xl w-20 h-20 mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12 text-custom-pink" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                </svg>
                            </div>
                            <h3
                                class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-custom-pink to-pink-600 bg-clip-text text-transparent mb-2">
                                {{ number_format($this->totalProfiles) }}</h3>
                            <p class="text-gray-600 text-sm font-medium leading-tight">Total Groom and<br>Bride's
                                Profiles</p>
                        </div>
                    </div>

                    <!-- Stat 2 - Groom Profiles -->
                    <div class="group relative border-l-2 border-gray-200">
                        <div
                            class="absolute -inset-2 bg-gradient-to-r from-custom-pink to-pink-600 rounded-2xl blur opacity-0 group-hover:opacity-20 transition duration-300">
                        </div>
                        <div class="relative">
                            <div
                                class="bg-gradient-to-br from-custom-pink/10 to-pink-100 p-4 rounded-2xl w-20 h-20 mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12 text-custom-pink" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h3
                                class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-custom-pink to-pink-600 bg-clip-text text-transparent mb-2">
                                {{ number_format($this->groomProfiles) }}</h3>
                            <p class="text-gray-600 text-sm font-medium leading-tight">Total Groom<br>Profiles</p>
                        </div>
                    </div>

                    <!-- Stat 3 - Bride Profiles -->
                    <div class="group relative border-l-2 border-gray-200">
                        <div
                            class="absolute -inset-2 bg-gradient-to-r from-custom-pink to-pink-600 rounded-2xl blur opacity-0 group-hover:opacity-20 transition duration-300">
                        </div>
                        <div class="relative">
                            <div
                                class="bg-gradient-to-br from-custom-pink/10 to-pink-100 p-4 rounded-2xl w-20 h-20 mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12 text-custom-pink" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h3
                                class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-custom-pink to-pink-600 bg-clip-text text-transparent mb-2">
                                {{ number_format($this->brideProfiles) }}</h3>
                            <p class="text-gray-600 text-sm font-medium leading-tight">Total Bride's<br>Profiles</p>
                        </div>
                    </div>

                    <!-- Stat 4 - Successful Marriages -->
                    <div class="group relative border-l-2 border-gray-200">
                        <div
                            class="absolute -inset-2 bg-gradient-to-r from-custom-pink to-pink-600 rounded-2xl blur opacity-0 group-hover:opacity-20 transition duration-300">
                        </div>
                        <div class="relative">
                            <div
                                class="bg-gradient-to-br from-custom-pink/10 to-pink-100 p-4 rounded-2xl w-20 h-20 mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12 text-custom-pink" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h3
                                class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-custom-pink to-pink-600 bg-clip-text text-transparent mb-2">
                                {{ number_format(Setting::get('stats.total_marriages', '1600')) }}+</h3>
                            <p class="text-gray-600 text-sm font-medium leading-tight">Successful<br>Marriages</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="mt-12 flex flex-wrap justify-center items-center gap-8">
                <div class="flex items-center gap-2 bg-white px-6 py-3 rounded-full shadow-lg border border-gray-200">
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-semibold text-gray-700">Verified Profiles</span>
                </div>
                <div class="flex items-center gap-2 bg-white px-6 py-3 rounded-full shadow-lg border border-gray-200">
                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    <span class="font-semibold text-gray-700">Active Community</span>
                </div>
                <div class="flex items-center gap-2 bg-white px-6 py-3 rounded-full shadow-lg border border-gray-200">
                    <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="font-semibold text-gray-700">{{ Setting::get('stats.review_average', '4.7') }}/5
                        Rated</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery Section -->


    <!-- Footer Section -->
    <footer class="bg-gradient-to-b from-maroon to-[#490b22] text-white pt-16 pb-8 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 opacity-5">
            <div
                class="absolute top-10 left-10 w-64 h-64 bg-custom-pink rounded-full mix-blend-multiply filter blur-3xl animate-pulse">
            </div>
            <div
                class="absolute bottom-10 right-10 w-64 h-64 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse delay-1000">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Get in Touch -->
                <div class="space-y-4">
                    <h5 class="text-xl font-extrabold mb-6 text-custom-pink flex items-center gap-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        GET IN TOUCH
                    </h5>
                    <div class="space-y-3 text-gray-300">
                        <div class="flex items-start gap-3 group hover:text-custom-pink transition-colors">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm">{{ Setting::get('contact.address', 'House:2, Road:32, Dhanmondi') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3 group hover:text-custom-pink transition-colors">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                            <p class="text-sm">{{ Setting::get('contact.phone', '+8809611489040') }}</p>
                        </div>
                        <div class="flex items-center gap-3 group hover:text-custom-pink transition-colors">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                            <a href="mailto:{{ Setting::get('contact.email', 'connect@engineersdiarybd.com') }}"
                                class="text-sm hover:underline">{{ Setting::get('contact.email', 'connect@engineersdiarybd.com') }}</a>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Google_Play_Store_badge_EN.svg/180px-Google_Play_Store_badge_EN.svg.png"
                            alt="Google Play" class="h-10 hover:scale-105 transition-transform cursor-pointer">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/App_Store_%28iOS%29.svg/250px-App_Store_%28iOS%29.svg.png"
                            alt="App Store" class="h-10 hover:scale-105 transition-transform cursor-pointer">
                    </div>
                </div>

                <!-- Resources -->
                <div>
                    <h5 class="text-xl font-extrabold mb-6 text-custom-pink">Resources</h5>
                    <ul class="space-y-3">
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-custom-pink text-sm transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>About Us</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-custom-pink text-sm transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Contact Us</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-custom-pink text-sm transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>FAQ</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-custom-pink text-sm transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Guide</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h5 class="text-xl font-extrabold mb-6 text-custom-pink">Support</h5>
                    <ul class="space-y-3">
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-custom-pink text-sm transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Help Center</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-custom-pink text-sm transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Safety Information</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-custom-pink text-sm transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Cancellation & Returns</span>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="text-gray-300 hover:text-custom-pink text-sm transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Our COVID-19 Response</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div>
                    <h5 class="text-xl font-extrabold mb-6 text-custom-pink">CONNECT WITH US</h5>
                    <p class="text-gray-300 text-sm mb-6">Follow us on social media for updates and success stories</p>
                    <div class="flex gap-4">
                        <a href="{{ Setting::get('social.facebook', 'https://www.facebook.com/Matrimony.ED/') }}"
                            target="_blank" aria-label="Facebook"
                            class="group bg-white/10 backdrop-blur-sm p-3 rounded-full hover:bg-custom-pink transition-all duration-300 transform hover:scale-110 hover:-translate-y-1">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z" />
                            </svg>
                        </a>
                        <a href="{{ Setting::get('social.instagram', 'https://www.instagram.com/engineersdiarybd/') }}"
                            target="_blank" aria-label="Instagram"
                            class="group bg-white/10 backdrop-blur-sm p-3 rounded-full hover:bg-custom-pink transition-all duration-300 transform hover:scale-110 hover:-translate-y-1">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                        <a href="https://wa.me/{{ Setting::get('contact.whatsapp', '8801911676540') }}"
                            target="_blank" aria-label="WhatsApp"
                            class="group bg-white/10 backdrop-blur-sm p-3 rounded-full hover:bg-custom-pink transition-all duration-300 transform hover:scale-110 hover:-translate-y-1">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M7.25361 18.4944L7.97834 18.917C9.18909 19.623 10.5651 20 12.001 20C16.4193 20 20.001 16.4183 20.001 12C20.001 7.58172 16.4193 4 12.001 4C7.5827 4 4.00098 7.58172 4.00098 12C4.00098 13.4363 4.37821 14.8128 5.08466 16.0238L5.50704 16.7478L4.85355 19.1494L7.25361 18.4944ZM2.00516 22L3.35712 17.0315C2.49494 15.5536 2.00098 13.8345 2.00098 12C2.00098 6.47715 6.47813 2 12.001 2C17.5238 2 22.001 6.47715 22.001 12C22.001 17.5228 17.5238 22 12.001 22C10.1671 22 8.44851 21.5064 6.97086 20.6447L2.00516 22ZM8.39232 7.30833C8.5262 7.29892 8.66053 7.29748 8.79459 7.30402C8.84875 7.30758 8.90265 7.31384 8.95659 7.32007C9.11585 7.33846 9.29098 7.43545 9.34986 7.56894C9.64818 8.24536 9.93764 8.92565 10.2182 9.60963C10.2801 9.76062 10.2428 9.95633 10.125 10.1457C10.0652 10.2428 9.97128 10.379 9.86248 10.5183C9.74939 10.663 9.50599 10.9291 9.50599 10.9291C9.50599 10.9291 9.40738 11.0473 9.44455 11.1944C9.45903 11.25 9.50521 11.331 9.54708 11.3991C9.57027 11.4368 9.5918 11.4705 9.60577 11.4938C9.86169 11.9211 10.2057 12.3543 10.6259 12.7616C10.7463 12.8783 10.8631 12.9974 10.9887 13.108C11.457 13.5209 11.9868 13.8583 12.559 14.1082L12.5641 14.1105C12.6486 14.1469 12.692 14.1668 12.8157 14.2193C12.8781 14.2457 12.9419 14.2685 13.0074 14.2858C13.0311 14.292 13.0554 14.2955 13.0798 14.2972C13.2415 14.3069 13.335 14.2032 13.3749 14.1555C14.0984 13.279 14.1646 13.2218 14.1696 13.2222V13.2238C14.2647 13.1236 14.4142 13.0888 14.5476 13.097C14.6085 13.1007 14.6691 13.1124 14.7245 13.1377C15.2563 13.3803 16.1258 13.7587 16.1258 13.7587L16.7073 14.0201C16.8047 14.0671 16.8936 14.1778 16.8979 14.2854C16.9005 14.3523 16.9077 14.4603 16.8838 14.6579C16.8525 14.9166 16.7738 15.2281 16.6956 15.3913C16.6406 15.5058 16.5694 15.6074 16.4866 15.6934C16.3743 15.81 16.2909 15.8808 16.1559 15.9814C16.0737 16.0426 16.0311 16.0714 16.0311 16.0714C15.8922 16.159 15.8139 16.2028 15.6484 16.2909C15.391 16.428 15.1066 16.5068 14.8153 16.5218C14.6296 16.5313 14.4444 16.5447 14.2589 16.5347C14.2507 16.5342 13.6907 16.4482 13.6907 16.4482C12.2688 16.0742 10.9538 15.3736 9.85034 14.402C9.62473 14.2034 9.4155 13.9885 9.20194 13.7759C8.31288 12.8908 7.63982 11.9364 7.23169 11.0336C7.03043 10.5884 6.90299 10.1116 6.90098 9.62098C6.89729 9.01405 7.09599 8.4232 7.46569 7.94186C7.53857 7.84697 7.60774 7.74855 7.72709 7.63586C7.85348 7.51651 7.93392 7.45244 8.02057 7.40811C8.13607 7.34902 8.26293 7.31742 8.39232 7.30833Z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-white/20 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-300">
                        <a href="#" class="hover:text-custom-pink transition-colors">Privacy Policy</a>
                        <span class="text-gray-600">|</span>
                        <a href="#" class="hover:text-custom-pink transition-colors">Terms of Use</a>
                        <span class="text-gray-600">|</span>
                        <a href="#" class="hover:text-custom-pink transition-colors">Sales and Refunds</a>
                        <span class="text-gray-600">|</span>
                        <a href="#" class="hover:text-custom-pink transition-colors">Legal</a>
                        <span class="text-gray-600">|</span>
                        <a href="#" class="hover:text-custom-pink transition-colors">Site Map</a>
                    </div>
                    <div class="text-center md:text-right">
                        <p class="text-sm text-gray-300">&copy; {{ date('Y') }} <span
                                class="font-bold text-custom-pink">Engineer's Matrimony</span>. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes float-delayed {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes gradient {

            0%,
            100% {
                background-size: 200% 200%;
                background-position: left center;
            }

            50% {
                background-size: 200% 200%;
                background-position: right center;
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float-delayed 6s ease-in-out infinite;
            animation-delay: 1s;
        }

        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }

        .animate-gradient {
            animation: gradient 3s ease infinite;
        }

        .delay-1000 {
            animation-delay: 1s;
        }

        .bg-grid-pattern {
            background-image: linear-gradient(to right, #f3f4f6 1px, transparent 1px),
                linear-gradient(to bottom, #f3f4f6 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</div>
