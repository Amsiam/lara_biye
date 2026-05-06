<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public string $captcha = '';
    public string $captchaCode = '';

    /**
     * Mount the component and generate initial CAPTCHA
     */
    public function mount(): void
    {
        $this->generateCaptcha();
    }

    /**
     * Generate a new CAPTCHA code
     */
    public function generateCaptcha(): void
    {
        $code = strtoupper(Str::random(6));
        session(['captcha_code' => $code]);
        $this->captchaCode = uniqid();
    }

    /**
     * Validate the CAPTCHA code
     */
    protected function validateCaptcha(): bool
    {
        $sessionCode = session('captcha_code');
        if (!$sessionCode || strtoupper($this->captcha) !== $sessionCode) {
            $this->addError('captcha', 'The verification code is incorrect.');
            return false;
        }
        return true;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'captcha' => 'required|string|size:6',
        ]);

        // Validate CAPTCHA
        if (!$this->validateCaptcha()) {
            $this->generateCaptcha();
            return;
        }

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            $this->generateCaptcha();

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('profile', Auth::user()->id, absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>


<div
    class="w-full max-w-5xl bg-white rounded-xl shadow-2xl flex flex-col lg:flex-row overflow-hidden border border-gray-100">
    <!-- Left Section with Image — hidden on mobile -->
    <div
        class="hidden lg:flex lg:w-2/5 relative bg-gradient-to-br from-custom-pink/10 to-custom-red/10 p-8 items-center justify-center">
        <div class="text-center space-y-4">
            <img src="{{ asset('img/image 70.png') }}" alt="Login Image"
                class="w-full max-w-sm mx-auto rounded-xl shadow-lg">
            <h3 class="text-2xl font-bold text-custom-red">Welcome Back!</h3>
            <p class="text-gray-600">Login to continue your journey</p>
        </div>
    </div>

    <!-- Right Section -->
    <div class="w-full lg:w-3/5 p-6 sm:p-8 lg:p-12 flex flex-col justify-center">
        <!-- Mobile brand header -->
        <div class="flex items-center justify-center mb-4 lg:hidden">
            <div class="text-center">
                <h3 class="text-xl font-bold text-custom-red">Engineer's Matrimony</h3>
                <p class="text-sm text-gray-500">Welcome back!</p>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-custom-red mb-1">Login to your account</h2>
            <p class="text-gray-600 text-sm sm:text-base">Enter your credentials to access your profile</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <input wire:model="email" type="email" id="email" placeholder="example@email.com"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                @error('email')
                    <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div x-data="{ show: false }">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <div class="relative" style="position: relative;">
                    <input wire:model="password" :type="show ? 'text' : 'password'" id="password"
                        placeholder="Enter your password"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300 pr-10" />
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-custom-pink focus:outline-none"
                        style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center;">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                clip-rule="evenodd" />
                            <path
                                d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                        </svg>
                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor" style="display: none;">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd"
                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input wire:model="remember" type="checkbox" id="remember"
                        class="w-4 h-4 text-custom-pink bg-gray-100 border-gray-300 rounded focus:ring-custom-pink focus:ring-2 cursor-pointer">
                    <label for="remember"
                        class="ml-2 text-sm font-medium text-gray-700 cursor-pointer select-none">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}"
                    class="text-sm font-semibold text-custom-pink hover:text-custom-red transition-colors duration-200 hover:underline">
                    Forgot password?
                </a>
            </div>

            <!-- CAPTCHA Verification -->
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-gray-700">Verification Code</label>
                <div class="flex items-center gap-4">
                    <img src="{{ route('captcha.image') }}?v={{ $captchaCode }}" alt="CAPTCHA Code"
                        class="h-16 rounded-lg shadow-lg border-2 border-gray-200" wire:key="captcha-{{ $captchaCode }}"
                        id="captcha-image">
                    <button type="button" wire:click="generateCaptcha"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reload
                    </button>
                </div>
                <input wire:model="captcha" type="text" id="captcha" placeholder="Enter the code shown above"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300"
                    autocomplete="off" />
                @error('captcha')
                    <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-custom-pink text-white py-3.5 rounded-lg font-semibold hover:bg-custom-red shadow-md hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300">
                Login to Account
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}"
                    class="font-semibold text-custom-pink hover:text-custom-red transition-colors duration-200 hover:underline">
                    Create one now
                </a>
            </p>

        </div>
        <div class="mt-4 text-center">
            <a href="/" class="inline-block bg-custom-pink px-6 py-2.5 text-white rounded-lg text-sm font-medium hover:bg-custom-red transition-colors duration-200">
                Go Back Home
            </a>
        </div>
    </div>
</div>