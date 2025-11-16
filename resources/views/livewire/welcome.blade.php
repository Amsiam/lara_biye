<?php

use function Livewire\Volt\{layout};

layout('components.layouts.app');

$createProfile = function () {
    return redirect()->route('register');
};

?>

<div>
    <section id="home" class="min-h-screen flex flex-col lg:flex-row items-center bg-maroon text-white pt-16">
        <div class="container mx-auto px-4 flex flex-col lg:flex-row items-center">

            <!-- Left Content (Hero Section) -->
            <div class="w-full lg:w-1/2 text-center lg:text-left px-4">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                    Reliable Match-making Site<br>
                    <span class="text-custom-pink"> For Engineers.</span>
                </h1>
                <p class="mt-4 text-lg md:text-xl">
                    We assure authetic background of your life partner.
                </p>

                <!-- Ratings & Profiles -->
                <div class="flex items-center mt-8 justify-center lg:justify-start">
                    <div class="flex -space-x-2">
                        <img src="{{ asset('img/hero-img1.png') }}" class="w-10 h-10 rounded-full" alt="Profile 1">
                        <img src="{{ asset('img/hero-img2.png') }}" class="w-10 h-10 rounded-full" alt="Profile 2">
                        <img src="{{ asset('img/hero-img3.png') }}" class="w-10 h-10 rounded-full" alt="Profile 3">
                        <img src="{{ asset('img/hero-img4.png') }}" class="w-10 h-10 rounded-full" alt="Profile 4">
                    </div>
                    <div class="flex items-center ml-4 space-x-2">
                        <img src="{{ asset('img/Vector(1).png') }}" alt="Rating Icon" class="w-6 h-6">
                        <img src="{{ asset('img/Rating and Reviews.png') }}" alt="Reviews" class="w-24">
                        <p class="text-lg font-bold">4.7/5</p>
                    </div>
                </div>
                <div class="mt-5">
                    <img src="{{ asset('img/image 27(1).png') }}" alt="Flower" class="mx-50">
                </div>
            </div>

            <!-- Right Image (Couple) -->
            <div class="w-full lg:w-1/2 mt-8 lg:mt-0 relative h-[600px] hidden sm:block">
                <img src="{{ asset('img/Rectangle 8775.png') }}" class="absolute inset-0 mx-auto w-3/4">
                <img src="{{ asset('img/couple.png') }}" class="absolute inset-0 mx-auto w-2/3">
            </div>
        </div>
    </section>

    <!-- Search Section (Integrated) -->
    <section class="w-full bg-gray-100 py-8 flex justify-center">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-2xl md:text-3xl font-bold leading-tight">
                Find your <span class="text-custom-pink">Right Match</span> here
            </h3>
            <form action="{{ route('search') }}">
                <div class="max-w-4xl mx-auto mt-8 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Gender -->
                        <x-select-input
                            name="gender"
                            placeholder="I'm looking for"
                            :options="['Male' => 'Male', 'Female' => 'Female']"
                            :icon="'<svg class=\'h-5 w-5 text-custom-pink\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\' /></svg>'"
                        />

                        <!-- Marital Status -->
                        <x-select-input
                            name="marital_status"
                            placeholder="Marital Status"
                            :options="['UNMARRIED' => 'Unmarried', 'MARRIED' => 'Married', 'DIVORCED' => 'Divorced', 'WIDOWED' => 'Widowed']"
                            :icon="'<svg class=\'h-5 w-5 text-custom-pink\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z\' /></svg>'"
                        />

                        <!-- Age -->
                        <x-select-input
                            name="age"
                            placeholder="Select Age"
                            :options="['18-25' => '18-25 years', '26-35' => '26-35 years', '36-45' => '36-45 years', '46-55' => '46-55 years', '56-65' => '56-65 years']"
                            :icon="'<svg class=\'h-5 w-5 text-custom-pink\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\' /></svg>'"
                        />

                        <!-- Search Button -->
                        <button type="submit" class="w-full bg-custom-pink text-white py-3 px-4 rounded-lg hover:bg-opacity-90 font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Create Bio data Section -->
    <section class="min-h-screen flex flex-col items-center py-16 relative">
        <img src="{{ asset('img/image 70.png') }}" alt=""
            class="absolute left-0 top-1/2 -translate-y-1/2 hidden md:block">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold leading-tight">
                Create profile in <span class="text-custom-pink">Engineer's Matrimony</span><br>Completely Free
            </h2>
            <img src="{{ asset('img/image 27(1).png') }}" alt="flower-image" class="mx-auto mt-5">

            <div class="flex flex-col md:flex-row justify-center mt-10 gap-6">
                <div wire:click="createProfile"
                    class="bg-white p-6 md:p-10 rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-gray-100 text-center">
                    <img src="{{ asset('img/image 72.png') }}" alt="Icon" class="w-12 h-12 mx-auto">
                    <p class="text-custom-pink mt-5 cursor-pointer">+ Create Your profile</p>
                </div>
                <div
                    class="border-2 border-custom-pink p-6 md:p-10 rounded-lg shadow-md transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-gray-100 text-center">
                    <img src="{{ asset('img/Vector.png') }}" alt="Icon" class="w-12 h-12 mx-auto">
                    <p class="text-custom-pink mt-5 cursor-pointer">How To Create profile</p>
                </div>
            </div>
        </div>
        <img src="{{ asset('img/image 71.png') }}" alt=""
            class="absolute right-0 top-1/2 -translate-y-1/2 hidden md:block">
    </section>



    <!-- How It Works Section -->
    <section class="min-h-screen flex items-center bg-[#490b22] py-16">
        <div class="container mx-auto px-4 text-center">
            <p class="text-custom-pink font-bold mb-2">Quick Access</p>
            <h3 class="text-3xl font-bold mb-12 text-white">How Engineer's Matrimony Works</h3>



            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Group.png') }}" alt="" class="mb-4 mx-auto">
                    <h5 class="text-xl font-semibold mb-2">Find Match</h5>
                    <p class="text-gray-600">Use filters to search your desired life partner.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Vector(2).png') }}" alt="" class="mb-4 mx-auto">
                    <h5 class="text-xl font-semibold mb-2">Create Profile</h5>
                    <p class="text-gray-600">Create your profile for free in just a few steps.</p>
                </div>


                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Group(1).png') }}" alt="" class="mb-4 mx-auto">
                    <h5 class="text-xl font-semibold mb-2">Start Communication</h5>
                    <p class="text-gray-600">Start communication with suitable profile.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Vector(3).png') }}" alt="" class="mb-4 mx-auto">
                    <h5 class="text-xl font-semibold mb-2">Get Married</h5>
                    <p class="text-gray-600">Finalize your decision and get married.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section id="guide" class="py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-0">

      <!-- Top Centered Heading -->
      <h2 class="text-4xl font-semibold text-center mb-12">
        <span class="text-custom-pink font-bold">VIP</span> Assistant Service
      </h2>

      <!-- Content Flexbox -->
      <div class="flex flex-col lg:flex-row items-center justify-center">

        <!-- Left Image -->
        <div class="w-full lg:w-1/2 mb-12 lg:mb-0 flex justify-center">
          <img src="./assets/assistant2.jpg" alt="Assistant Service Illustration" class="max-w-md w-full">
        </div>

        <!-- Right Content -->
        <div class="w-full lg:w-1/2">
          <!-- Timeline -->
          <div class="relative">
            <div class="absolute left-2.5 top-0 bottom-0 w-0.5 bg-black"></div>

            <!-- Timeline Item -->
            <div class="flex items-center mb-6 relative">
              <div class="w-6 h-5 rounded-full bg-white border-4 border-custom-pink z-10 "></div>
              <div class="ml-6 bg-gray-100 text-custom-pink rounded-md px-3 py-2 w-full">
                Assign Personal Advisor
              </div>
            </div>

            <div class="flex items-center mb-6 relative">
              <div class="w-6 h-5 rounded-full bg-white border-4 border-custom-pink z-10"></div>
              <div class="ml-6 bg-gray-100 text-custom-pink rounded-md px-4 py-2 w-full">
                Advisor Will Manage Your Profile
              </div>
            </div>

            <div class="flex items-center mb-6 relative">
              <div class="w-6 h-5 rounded-full bg-white border-4 border-custom-pink z-10"></div>
              <div class="ml-6 bg-gray-100 text-custom-pink rounded-md px-4 py-2 w-full">
                Handpick Matches For You
              </div>
            </div>

            <div class="flex items-center relative">
              <div class="w-6 h-5 rounded-full bg-white border-4 border-custom-pink z-10"></div>
              <div class="ml-6 bg-gray-100 text-custom-pink rounded-md px-4 py-2 w-full">
                Arranging Meetings
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Button - Now centered at the bottom -->
      <div class="mt-10 text-center w-full">
        <button class="border border-custom-pink text-custom-pink rounded-md px-6 py-2 hover:bg-custom-pink hover:text-white transition">
          View More Details
        </button>
      </div>
    </div>
  </section>
  <!-- Why Choose Us Section -->
    <section class="bg-maroon min-h-screen flex items-center">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-2 text-custom-pink">#1 WEDDING WEBSITE</p>
            <h2 class="text-4xl md:text-5xl font-bold mb-4 text-white">Why Choose Us</h2>
            <p class="text-gray-300">The Most Trusted and Premium Matrimony Service</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/image 24.png') }}" class="w-16 h-16 mx-auto mb-4">
                    <h5 class="font-bold text-black mb-2">Verified Engineers</h5>
                    <p class="text-gray-600">All profiles are verified with their student id.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/image 23.png') }}" class="w-16 h-16 mx-auto mb-4">
                    <h5 class="font-bold text-black mb-2">AI Based Matching</h5>
                    <p class="text-gray-600">Our AI will suggest the best match for your interest.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Group 4.png') }}" class="w-16 h-16 mx-auto mb-4">
                    <h5 class="font-bold text-black mb-2">1600+ Weddings</h5>
                    <p class="text-gray-600">Many have successfully found their life partner.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="bg-white py-16">
        <div class="container mx-auto px-4 border-b-2 border-t-2 py-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <img src="{{ asset('img/image 8.png') }}" alt="" class="mx-auto">
                    <p class="text-gray-600">Total Groom and Bride's profiles</p>
                    <h3 class="text-4xl font-bold">5,165</h3>
                </div>
                <div class="border-l-2">
                    <img src="{{ asset('img/image 6.png') }}" alt="" class="mx-auto">
                    <p class="text-gray-600">Total Groom profiles</p>
                    <h3 class="text-4xl font-bold">2,184</h3>
                </div>
                <div class="border-l-2">
                    <img src="{{ asset('img/image 7.png') }}" alt="" class="mx-auto">
                    <p class="text-gray-600">Total Bride's profiles</p>
                    <h3 class="text-4xl font-bold">5,165</h3>
                </div>
                <div class="border-l-2">
                    <img src="{{ asset('img/Group 4.png') }}" alt="" class="mx-auto h-12">
                    <p class="text-gray-600">Total Successful Marriages</p>
                    <h3 class="text-4xl font-bold">1,600+</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery Section -->


    <!-- Footer Section -->
    <footer class="bg-maroon text-white pt-12 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Get in Touch -->
                <div>
                    <h5 class="font-bold mb-4">GET IN TOUCH</h5>
                    <p class="mb-2 text-sm">Address: House:2, Road:32, Dhanmondi</p>
                    <p class="mb-2 text-sm">Phone: +8809611489040</p>
                    <div class="mb-4 text-xs"> Email: <a
                            href="mailto:connect@engineersdiarybd.com">connect@engineersdiarybd.com</a> </div>
                    <div class="flex gap-4">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Google_Play_Store_badge_EN.svg/180px-Google_Play_Store_badge_EN.svg.png"
                            alt="Google Play" class="h-10">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/App_Store_%28iOS%29.svg/250px-App_Store_%28iOS%29.svg.png"
                            alt="App Store" class="h-10">
                    </div>
                </div>

                <!-- Resources -->
                <div>
                    <h5 class="font-bold mb-4">Resources</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-custom-pink text-sm">About Us</a></li>
                        <li><a href="#" class="hover:text-custom-pink text-sm">Contact Us</a></li>
                        <li><a href="#" class="hover:text-custom-pink text-sm">FAQ</a></li>
                        <li><a href="#" class="hover:text-custom-pink text-sm">Guide</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h5 class="font-bold mb-4">Support</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-custom-pink text-sm">Help Center</a></li>
                        <li><a href="#" class="hover:text-custom-pink text-sm">Safety Information</a></li>
                        <li><a href="#" class="hover:text-custom-pink text-sm">Cancellation & Returns</a></li>
                        <li><a href="#" class="hover:text-custom-pink text-sm">Our COVID-19 Response</a></li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div>
                    <h5 class="font-bold mb-4 uppercase">Social Media</h5>
                    <div class="flex gap-4">
                        <a href="https://www.facebook.com/Matrimony.ED/" target="_blank" aria-label="Facebook"
                            class="hover:text-custom-pink">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/engineersdiarybd/" target="_blank" aria-label="Instagram"
                            class="hover:text-custom-pink">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                        <a href="https://wa.me/8801911676540" target="_blank" aria-label="Instagram"
                            class="hover:text-custom-pink">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M7.25361 18.4944L7.97834 18.917C9.18909 19.623 10.5651 20 12.001 20C16.4193 20 20.001 16.4183 20.001 12C20.001 7.58172 16.4193 4 12.001 4C7.5827 4 4.00098 7.58172 4.00098 12C4.00098 13.4363 4.37821 14.8128 5.08466 16.0238L5.50704 16.7478L4.85355 19.1494L7.25361 18.4944ZM2.00516 22L3.35712 17.0315C2.49494 15.5536 2.00098 13.8345 2.00098 12C2.00098 6.47715 6.47813 2 12.001 2C17.5238 2 22.001 6.47715 22.001 12C22.001 17.5228 17.5238 22 12.001 22C10.1671 22 8.44851 21.5064 6.97086 20.6447L2.00516 22ZM8.39232 7.30833C8.5262 7.29892 8.66053 7.29748 8.79459 7.30402C8.84875 7.30758 8.90265 7.31384 8.95659 7.32007C9.11585 7.33846 9.29098 7.43545 9.34986 7.56894C9.64818 8.24536 9.93764 8.92565 10.2182 9.60963C10.2801 9.76062 10.2428 9.95633 10.125 10.1457C10.0652 10.2428 9.97128 10.379 9.86248 10.5183C9.74939 10.663 9.50599 10.9291 9.50599 10.9291C9.50599 10.9291 9.40738 11.0473 9.44455 11.1944C9.45903 11.25 9.50521 11.331 9.54708 11.3991C9.57027 11.4368 9.5918 11.4705 9.60577 11.4938C9.86169 11.9211 10.2057 12.3543 10.6259 12.7616C10.7463 12.8783 10.8631 12.9974 10.9887 13.108C11.457 13.5209 11.9868 13.8583 12.559 14.1082L12.5641 14.1105C12.6486 14.1469 12.692 14.1668 12.8157 14.2193C12.8781 14.2457 12.9419 14.2685 13.0074 14.2858C13.0311 14.292 13.0554 14.2955 13.0798 14.2972C13.2415 14.3069 13.335 14.2032 13.3749 14.1555C14.0984 13.279 14.1646 13.2218 14.1696 13.2222V13.2238C14.2647 13.1236 14.4142 13.0888 14.5476 13.097C14.6085 13.1007 14.6691 13.1124 14.7245 13.1377C15.2563 13.3803 16.1258 13.7587 16.1258 13.7587L16.7073 14.0201C16.8047 14.0671 16.8936 14.1778 16.8979 14.2854C16.9005 14.3523 16.9077 14.4603 16.8838 14.6579C16.8525 14.9166 16.7738 15.2281 16.6956 15.3913C16.6406 15.5058 16.5694 15.6074 16.4866 15.6934C16.3743 15.81 16.2909 15.8808 16.1559 15.9814C16.0737 16.0426 16.0311 16.0714 16.0311 16.0714C15.8922 16.159 15.8139 16.2028 15.6484 16.2909C15.391 16.428 15.1066 16.5068 14.8153 16.5218C14.6296 16.5313 14.4444 16.5447 14.2589 16.5347C14.2507 16.5342 13.6907 16.4482 13.6907 16.4482C12.2688 16.0742 10.9538 15.3736 9.85034 14.402C9.62473 14.2034 9.4155 13.9885 9.20194 13.7759C8.31288 12.8908 7.63982 11.9364 7.23169 11.0336C7.03043 10.5884 6.90299 10.1116 6.90098 9.62098C6.89729 9.01405 7.09599 8.4232 7.46569 7.94186C7.53857 7.84697 7.60774 7.74855 7.72709 7.63586C7.85348 7.51651 7.93392 7.45244 8.02057 7.40811C8.13607 7.34902 8.26293 7.31742 8.39232 7.30833Z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-gray-700 mt-8 pt-4 text-center text-sm">
                <p class="mb-2">Privacy Policy | Terms of Use | Sales and Refunds | Legal | Site Map</p>
                <p>&copy; {{ date('Y') }} Engineer's Matrimony</p>
            </div>
        </div>
    </footer>


</div>
