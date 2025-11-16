<?php

use App\Models\Faq;
use function Livewire\Volt\{state, computed};

state(['openFaq' => null]);

$toggleFaq = function ($index) {
    $this->openFaq = $this->openFaq === $index ? null : $index;
};

$faqs = computed(function () {
    return Faq::active()
        ->ordered()
        ->get()
        ->groupBy('category');
});

?>

<div class="min-h-screen bg-gradient-to-b from-white to-custom-pink/5">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-custom-red mb-4">Frequently Asked Questions</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Find answers to common questions about our matrimonial service
            </p>
        </div>

        <!-- Search Box -->
        <div class="max-w-2xl mx-auto mb-12">
            <div class="relative">
                <input type="text" placeholder="Search for answers..."
                    class="w-full px-6 py-4 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300 pr-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 absolute right-4 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        @if($this->faqs->isEmpty())
            <!-- No FAQs Available -->
            <div class="bg-white rounded-xl shadow-md p-12 text-center border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No FAQs Available Yet</h3>
                <p class="text-gray-600">We're working on adding frequently asked questions. Please check back soon!</p>
            </div>
        @else
            <!-- FAQ Categories -->
            @foreach($this->faqs as $categoryName => $categoryFaqs)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-custom-red mb-4 flex items-center">
                        <span class="bg-custom-pink/10 px-4 py-2 rounded-lg">{{ $categoryName }}</span>
                    </h2>

                    <div class="space-y-3">
                        @foreach($categoryFaqs as $index => $faq)
                            @php
                                $faqId = $faq->id;
                                $isOpen = $openFaq === $faqId;
                            @endphp

                            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <button
                                    wire:click="toggleFaq({{ $faqId }})"
                                    class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-custom-pink/5 transition-colors duration-200">
                                    <span class="font-semibold text-gray-800 pr-4">{{ $faq->question }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-custom-pink transform transition-transform duration-300 flex-shrink-0 {{ $isOpen ? 'rotate-180' : '' }}"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div class="overflow-hidden transition-all duration-300 {{ $isOpen ? 'max-h-96' : 'max-h-0' }}">
                                    <div class="px-6 pb-4 pt-2 text-gray-600 leading-relaxed border-t border-gray-100">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Still Have Questions Section -->
        <div class="bg-gradient-to-r from-custom-pink to-custom-red rounded-xl shadow-lg p-8 md:p-12 text-white text-center mt-12">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold mb-4">Still Have Questions?</h2>
                <p class="mb-6 text-white/90">
                    Can't find the answer you're looking for? Our support team is here to help you.
                </p>
                <a href="{{ route('contact') }}"
                    class="inline-block px-8 py-3 bg-white text-custom-pink rounded-lg font-semibold hover:bg-gray-100 shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    Contact Support
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid md:grid-cols-3 gap-6 mt-12">
            <a href="{{ route('about') }}" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all duration-300 border border-custom-pink/20 group">
                <div class="bg-custom-pink/10 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-custom-pink/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-custom-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">About Us</h3>
                <p class="text-sm text-gray-600">Learn more about our mission and values</p>
            </a>

            <a href="{{ route('packages') }}" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all duration-300 border border-custom-pink/20 group">
                <div class="bg-custom-pink/10 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-custom-pink/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-custom-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Packages</h3>
                <p class="text-sm text-gray-600">View our premium membership plans</p>
            </a>

            <a href="{{ route('contact') }}" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition-all duration-300 border border-custom-pink/20 group">
                <div class="bg-custom-pink/10 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-custom-pink/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-custom-pink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Contact Us</h3>
                <p class="text-sm text-gray-600">Get in touch with our support team</p>
            </a>
        </div>
    </div>
</div>
