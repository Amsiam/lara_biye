<?php

use function Livewire\Volt\{state, rules, mount};
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use Illuminate\Support\Str;

state(['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => '', 'captcha' => '', 'captchaCode' => '']);

rules([
    'name' => 'required|string|max:255',
    'email' => 'required|email|max:255',
    'phone' => 'nullable|string|max:20',
    'subject' => 'required|string|max:255',
    'message' => 'required|string|min:10',
    'captcha' => 'required|string|size:6',
]);

// Generate random CAPTCHA code and store in session
$generateCaptcha = function () {
    $code = strtoupper(Str::random(6));
    session(['captcha_code' => $code]);
    $this->captchaCode = uniqid(); // Just a random token for cache busting
};

// Mount hook to generate CAPTCHA on page load
mount(function () {
    $this->generateCaptcha();
});

// Custom CAPTCHA validation
$validateCaptcha = function () {
    $sessionCode = session('captcha_code');
    if (!$sessionCode || strtoupper($this->captcha) !== $sessionCode) {
        $this->addError('captcha', 'The verification code is incorrect.');
        return false;
    }
    return true;
};

$submit = function () {
    $this->validate();

    // Validate CAPTCHA
    if (!$this->validateCaptcha()) {
        $this->generateCaptcha(); // Generate new CAPTCHA on failure
        return;
    }

    try {
        $adminEmail = Setting::get('contact.email', config('mail.from.address'));

        Mail::to($adminEmail)->send(
            new ContactMail(
                name: $this->name,
                email: $this->email,
                emailSubject: $this->subject,
                messageContent: $this->message,
                phone: $this->phone,
            )
        );

        session()->flash('success', 'Thank you for contacting us! We will get back to you soon.');

        // Reset form and generate new CAPTCHA
        $this->reset(['name', 'email', 'phone', 'subject', 'message', 'captcha']);
        $this->generateCaptcha();
    } catch (\Exception $e) {
        session()->flash('error', 'Sorry, there was an error sending your message. Please try again later.');
        $this->generateCaptcha(); // Generate new CAPTCHA on error
    }
};

?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl lg:text-5xl font-bold text-custom-red mb-4">Get In Touch</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Information -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Contact Info Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                    <h2 class="text-2xl font-bold text-custom-red mb-6">Contact Information</h2>

                    <div class="space-y-4">
                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-custom-pink/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-custom-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Email</h3>
                                <a href="mailto:{{ Setting::get('contact.email', 'connect@engineersdiarybd.com') }}" class="text-gray-600 hover:text-custom-pink transition-colors">
                                    {{ Setting::get('contact.email', 'connect@engineersdiarybd.com') }}
                                </a>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-custom-pink/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-custom-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Phone</h3>
                                <a href="tel:{{ Setting::get('contact.phone', '+8809611489040') }}" class="text-gray-600 hover:text-custom-pink transition-colors">
                                    {{ Setting::get('contact.phone', '+8809611489040') }}
                                </a>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-custom-pink/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-custom-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Address</h3>
                                <p class="text-gray-600">{{ Setting::get('contact.address', 'House:2, Road:32, Dhanmondi') }}</p>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-custom-pink/10 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-custom-pink" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">WhatsApp</h3>
                                <a href="https://wa.me/{{ Setting::get('contact.whatsapp', '8801911676540') }}" target="_blank" class="text-gray-600 hover:text-custom-pink transition-colors">
                                    {{ Setting::get('contact.whatsapp', '+880 1911 676540') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-custom-red mb-4">Follow Us</h2>
                    <div class="flex gap-4">
                        <a href="{{ Setting::get('social.facebook', 'https://www.facebook.com/Matrimony.ED/') }}" target="_blank" class="w-12 h-12 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="{{ Setting::get('social.instagram', 'https://www.instagram.com/engineersdiarybd/') }}" target="_blank" class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-500 hover:from-purple-700 hover:to-pink-600 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold text-custom-red mb-6">Send Us a Message</h2>

                    <!-- Success Message -->
                    @if (session()->has('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-green-700 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-red-700 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="submit" class="space-y-6">
                        <!-- Name and Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                <input wire:model="name" type="text" id="name" placeholder="John Doe"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                <input wire:model="email" type="email" id="email" placeholder="john@example.com"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone and Subject -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <input wire:model="phone" type="text" id="phone" placeholder="+880 1XXX XXXXXX"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject <span class="text-red-500">*</span></label>
                                <input wire:model="subject" type="text" id="subject" placeholder="How can we help you?"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300">
                                @error('subject')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Message <span class="text-red-500">*</span></label>
                            <textarea wire:model="message" id="message" rows="6" placeholder="Tell us what you're thinking about..."
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300 resize-none"></textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CAPTCHA Verification -->
                        <div>
                            <label for="captcha" class="block text-sm font-semibold text-gray-700 mb-2">Security Verification <span class="text-red-500">*</span></label>
                            <div class="flex gap-4 items-start flex-col sm:flex-row">
                                <div class="flex-shrink-0">
                                    <!-- CAPTCHA Image -->
                                    <img src="{{ route('captcha.image') }}?v={{ $captchaCode }}"
                                         alt="CAPTCHA Code"
                                         class="rounded-lg shadow-lg border-2 border-gray-200"
                                         wire:key="captcha-{{ $captchaCode }}"
                                         id="captcha-image">
                                    <button type="button" wire:click="generateCaptcha"
                                        class="mt-2 text-sm text-custom-pink hover:text-custom-red font-semibold flex items-center gap-1 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Reload Code
                                    </button>
                                </div>
                                <div class="flex-1">
                                    <input wire:model="captcha" type="text" id="captcha" placeholder="Enter the 6-character code"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300 uppercase"
                                        autocomplete="off" maxlength="6">
                                    @error('captcha')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-1">Enter the code shown in the image above (not case-sensitive)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="w-full bg-custom-pink text-white py-3.5 rounded-lg font-semibold hover:bg-custom-red shadow-md hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
