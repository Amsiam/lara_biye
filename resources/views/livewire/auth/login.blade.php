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


<div class="w-full max-w-5xl bg-white rounded-xl shadow-2xl flex flex-col lg:flex-row overflow-hidden border border-gray-100">
    <!-- Left Section with Image -->
    <div class="w-full lg:w-2/5 relative bg-gradient-to-br from-custom-pink/10 to-custom-red/10 p-8 flex items-center justify-center">
        <div class="text-center space-y-4">
            <img src="{{ asset('img/image 70.png') }}" alt="Login Image" class="w-full max-w-sm mx-auto rounded-xl shadow-lg">
            <h3 class="text-2xl font-bold text-custom-red">Welcome Back!</h3>
            <p class="text-gray-600">Login to continue your journey</p>
        </div>
    </div>

    <!-- Right Section -->
    <div class="w-full lg:w-3/5 p-10 lg:p-12 flex flex-col justify-center">
        <div class="mb-8">
            <h2 class="text-3xl lg:text-4xl font-bold text-custom-red mb-2">Login to your account</h2>
            <p class="text-gray-600">Enter your credentials to access your profile</p>
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

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input wire:model="password" type="password" id="password" placeholder="Enter your password"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                @error('password')
                    <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input wire:model="remember" type="checkbox" id="remember"
                        class="w-4 h-4 text-custom-pink bg-gray-100 border-gray-300 rounded focus:ring-custom-pink focus:ring-2 cursor-pointer">
                    <label for="remember" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer select-none">Remember me</label>
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
                    <img src="{{ route('captcha.image') }}?v={{ $captchaCode }}"
                         alt="CAPTCHA Code"
                         class="h-16 rounded-lg shadow-lg border-2 border-gray-200"
                         wire:key="captcha-{{ $captchaCode }}"
                         id="captcha-image">
                    <button type="button" wire:click="generateCaptcha"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
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
                <a href="{{ route('register') }}" class="font-semibold text-custom-pink hover:text-custom-red transition-colors duration-200 hover:underline">
                    Create one now
                </a>
            </p>
        </div>
    </div>
</div>
