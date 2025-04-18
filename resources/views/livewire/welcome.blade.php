<?php

use function Livewire\Volt\{layout};

layout('components.layouts.app');

?>

<div>
    <section id="home" class="min-h-screen flex flex-col lg:flex-row items-center bg-maroon text-white pt-16">
        <div class="container mx-auto px-4 flex flex-col lg:flex-row items-center">

            <!-- Left Content (Hero Section) -->
            <div class="w-full lg:w-1/2 text-center lg:text-left px-4">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                    Bangladeshi Islamic <br>
                    <span class="text-custom-pink">Matrimony.</span>
                </h1>
                <p class="mt-4 text-lg md:text-xl">
                    When a person gets married, he has completed half of his deen,
                    so let him fear Allah with regard to the other half.
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
                        <p class="text-lg font-bold">4.5/5</p>
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
                        <select name="gender" class="w-full p-3 border rounded-lg">
                            <option>I'm looking for</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                        <select name="marital_status" class="w-full p-3 border rounded-lg">
                            <option>Marital Status</option>
                            @foreach (['UNMARRIED', 'MARRIED', 'DIVORCED', 'WIDOWED'] as $maritalStatus)
                                <option>{{ $maritalStatus }}</option>
                            @endforeach
                        </select>
                        <select name="age" class="w-full p-3 border rounded-lg">
                            <option>Select Age</option>
                            <option>18-25</option>
                            <option>26-35</option>
                            <option>36-45</option>
                        </select>
                        <button class="w-full bg-custom-pink text-white p-3 rounded-lg hover:bg-opacity-90">
                            Search
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
                Create Biodata in <span class="text-custom-pink">OrhdekDeen</span><br>Completely Free
            </h2>
            <img src="{{ asset('img/image 27(1).png') }}" alt="flower-image" class="mx-auto mt-5">

            <div class="flex flex-col md:flex-row justify-center mt-10 gap-6">
                <div
                    class="bg-white p-6 md:p-10 rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-gray-100 text-center">
                    <img src="{{ asset('img/image 72.png') }}" alt="Icon" class="w-12 h-12 mx-auto">
                    <p class="text-custom-pink mt-5 cursor-pointer">+ Create Your Biodata</p>
                </div>
                <div
                    class="border-2 border-custom-pink p-6 md:p-10 rounded-lg shadow-md transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-gray-100 text-center">
                    <img src="{{ asset('img/Vector.png') }}" alt="Icon" class="w-12 h-12 mx-auto">
                    <p class="text-custom-pink mt-5 cursor-pointer">How To Create Biodata</p>
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
            <h3 class="text-3xl font-bold mb-12 text-white">How OrhdekDeen Works</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Vector(2).png') }}" alt="" class="mb-4 mx-auto">
                    <h5 class="text-xl font-semibold mb-2">Create Biodata</h5>
                    <p class="text-gray-600">Create your biodata for free in just a few steps.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Group.png') }}" alt="" class="mb-4 mx-auto">
                    <h5 class="text-xl font-semibold mb-2">Search Biodata</h5>
                    <p class="text-gray-600">Use filters like age, location, profession, and education to search.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Group(1).png') }}" alt="" class="mb-4 mx-auto">
                    <h5 class="text-xl font-semibold mb-2">Contact Guardians</h5>
                    <p class="text-gray-600">Directly contact the parents if you like a biodata.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Vector(3).png') }}" alt="" class="mb-4 mx-auto">
                    <h5 class="text-xl font-semibold mb-2">Get Married</h5>
                    <p class="text-gray-600">Finalize your decision and get married according to Sunnah.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section class="min-h-screen flex items-center py-16">
        <div class="container mx-auto px-4 text-center">
            <h5 class="text-custom-pink text-2xl mb-2">Trusted Brand</h5>
            <h2 class="text-4xl md:text-5xl font-bold mb-8 text-custom-red">Trusted by 1600+ Couples</h2>
            <img src="{{ asset('img/hero-img-leaf.png') }}" alt="" class="mx-auto mb-5 h-12">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mt-12">
                <div
                    class="bg-white p-8 rounded-lg shadow-md border border-gray-300 transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Rectangle 8762.png') }}" class="w-32 h-40 mx-auto mb-4">
                    <p class="text-gray-600">Lorem ipsum dolor sit amet consectetur, adipisicing elit...</p>
                    <h5 class="font-bold mt-4">MST. SADIA AKTER</h5>
                    <p class="text-gray-500">Dhaka</p>
                </div>

                <div
                    class="bg-white p-8 rounded-lg shadow-md border border-gray-300 transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Rectangle 8762(1).png') }}" class="w-32 h-40 mx-auto mb-4">
                    <p class="text-gray-600">Lorem ipsum dolor sit amet consectetur, adipisicing elit...</p>
                    <h5 class="font-bold mt-4">MIZANUR ISLAM</h5>
                    <p class="text-gray-500">Khulna</p>
                </div>

                <div
                    class="bg-white p-8 rounded-lg shadow-md border border-gray-300 transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Rectangle 8762(2).png') }}" class="w-32 h-40 mx-auto mb-4">
                    <p class="text-gray-600">Lorem ipsum dolor sit amet consectetur, adipisicing elit...</p>
                    <h5 class="font-bold mt-4">MD. MAHIM MIYA</h5>
                    <p class="text-gray-500">Chittagong</p>
                </div>
            </div>

            <button class="mt-10 bg-custom-pink text-white px-6 py-3 rounded-md hover:bg-opacity-90">
                More Customer Reviews
            </button>
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
                    <h5 class="font-bold text-black mb-2">Genuine Profiles</h5>
                    <p class="text-gray-600">All profiles are verified with 100% mobile authentication.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/image 23.png') }}" class="w-16 h-16 mx-auto mb-4">
                    <h5 class="font-bold text-black mb-2">Most Trusted</h5>
                    <p class="text-gray-600">Recognized as a top wedding matrimony brand.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md transition-transform hover:scale-105 hover:shadow-xl">
                    <img src="{{ asset('img/Group 4.png') }}" class="w-16 h-16 mx-auto mb-4">
                    <h5 class="font-bold text-black mb-2">1600+ Weddings</h5>
                    <p class="text-gray-600">Many have successfully found their life partner.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="min-h-screen flex items-center py-16">
        <div class="container mx-auto px-4 flex flex-wrap items-center">

            <!-- Image Section -->
            <div class="w-full md:w-1/2 relative">
                <div class="relative inline-block">
                    <img src="{{ asset('img/Rectangle 8769.png') }}" alt="Wedding Couple" class="w-full shadow-lg">

                    <!-- Decorative Elements -->
                    <div class="absolute -top-14 -left-14 -z-10">
                        <img src="{{ asset('img/Ellipse 2629.png') }}" alt="">
                    </div>
                </div>
                <div class="absolute right-5 bottom-0">
                    <img src="{{ asset('img/Rectangle 8771.png') }}" alt="Small Wedding Image">
                </div>
                <div class="absolute -right-2 -bottom-9 -z-10">
                    <img src="{{ asset('img/Rectangle 8772.png') }}" alt="">
                </div>
            </div>

            <!-- Text Section -->
            <div class="w-full md:w-1/2 mt-12 md:mt-0 md:pl-12 text-center md:text-left">
                <h1 class="text-4xl md:text-5xl font-bold text-custom-red mb-4">WELCOME TO</h1>
                <h2 class="text-2xl md:text-3xl font-bold text-custom-pink mb-6">WEDDING MATRIMONY</h2>
                <p class="text-gray-600 mb-6">Best wedding matrimony service. It is a long-established fact that a
                    reader will be distracted by readable content when looking at its layout.</p>
                <a href="#" class="text-custom-pink font-bold">Click here</a> to start your matrimony service
                now.
                <hr class="border-2 border-black my-6">
                <!-- Contact Information -->
                <div class="flex flex-col md:flex-row gap-6 md:gap-8 items-center md:items-start">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('img/Group 1000008572.png') }}" alt="">
                        <div>
                            <p>Enquiry:</p>
                            <p class="font-bold">+880 179 765 0987</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('img/Group 1000008571.png') }}" alt="">
                        <div>
                            <p>Get Support:</p>
                            <p class="font-bold">info@example.com</p>
                        </div>
                    </div>
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
                    <p class="text-gray-600">Total Groom and Bride's Biodatas</p>
                    <h3 class="text-4xl font-bold">5,165</h3>
                </div>
                <div class="border-l-2">
                    <img src="{{ asset('img/image 6.png') }}" alt="" class="mx-auto">
                    <p class="text-gray-600">Total Groom Biodatas</p>
                    <h3 class="text-4xl font-bold">2,184</h3>
                </div>
                <div class="border-l-2">
                    <img src="{{ asset('img/image 7.png') }}" alt="" class="mx-auto">
                    <p class="text-gray-600">Total Bride's Biodatas</p>
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
    <section class="py-16 relative">
        <img src="{{ asset('img/image 29.png') }}" alt="left green leaf"
            class="absolute top-0 left-0 h-32 opacity-90">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <div class="text-center md:text-left">
                    <p class="text-custom-pink text-xl uppercase mb-2">COLLECTION</p>
                    <h2 class="text-4xl font-bold">Photo Gallery</h2>
                </div>
                <a href="#"
                    class="border border-custom-pink text-custom-pink rounded-full px-8 py-3 hover:bg-custom-pink font-bold hover:text-white">See
                    All</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <img src="{{ asset('img/Mask group.png') }}" alt="Wedding Photo 1"
                    class="w-full h-64 md:h-96 object-cover rounded-lg">
                <img src="{{ asset('img/Mask group(1).png') }}" alt="Wedding Photo 2"
                    class="w-full h-64 md:h-96 object-cover rounded-lg">
                <img src="{{ asset('img/Mask group(2).png') }}" alt="Wedding Photo 3"
                    class="w-full h-64 md:h-96 object-cover rounded-lg">
                <img src="{{ asset('img/Mask group(3).png') }}" alt="Wedding Photo 4"
                    class="w-full h-64 md:h-96 object-cover rounded-lg">
                <img src="{{ asset('img/Mask group(4).png') }}" alt="Wedding Photo 5"
                    class="w-full h-64 md:h-96 object-cover rounded-lg">
                <img src="{{ asset('img/Mask group(5).png') }}" alt="Wedding Photo 6"
                    class="w-full h-64 md:h-96 object-cover rounded-lg">
                <img src="{{ asset('img/Mask group(6).png') }}" alt="Wedding Photo 7"
                    class="w-full h-64 md:h-96 object-cover rounded-lg">
                <img src="{{ asset('img/Mask group(2).png') }}" alt="Wedding Photo 8"
                    class="w-full h-64 md:h-96 object-cover rounded-lg">
            </div>
        </div>
    </section>


    <!-- Footer Section -->
    <footer class="bg-maroon text-white pt-12 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Get in Touch -->
                <div>
                    <h5 class="font-bold mb-4">GET IN TOUCH</h5>
                    <p class="mb-2 text-sm">Address: 123 Wedding Avenue</p>
                    <p class="mb-2 text-sm">Phone: +880 179 765 0987</p>
                    <p class="mb-4 text-sm">Email: info@example.com</p>
                    <div class="flex gap-4">
                        <img src="{{ asset('img/google-store 1.png') }}" alt="Google Play" class="h-10">
                        <img src="{{ asset('img/Apple_store.png') }}" alt="App Store" class="h-10">
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
                        <a href="#" aria-label="Facebook" class="hover:text-custom-pink">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Instagram" class="hover:text-custom-pink">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-gray-700 mt-8 pt-4 text-center text-sm">
                <p class="mb-2">Privacy Policy | Terms of Use | Sales and Refunds | Legal | Site Map</p>
                <p>&copy; 2025 Wedding Matrimony</p>
            </div>
        </div>
    </footer>


</div>
