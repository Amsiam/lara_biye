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

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

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
    class="w-full max-w-4xl p-8 bg-white rounded-2xl shadow-xl flex flex-col lg:flex-row overflow-hidden border-4 border-white">
    <!-- Left Section with Image -->
    <div class="w-full lg:w-1/3 relative mb-6 lg:mb-0">
        <img src="{{ asset('img/image 70.png') }}" alt="Login Image" class="w-full h-full object-cover rounded-lg">
    </div>

    <!-- Right Section -->
    <div class="w-full lg:w-2/3 p-10 flex flex-col justify-center">
        <h2 class="text-4xl font-semibold mb-6 text-custom-pink">Login to your account</h2>
        <form wire:submit.prevent="login">
            <div class="mb-5">
                <input wire:model="email" type="email" placeholder="Enter email"
                    class="w-full p-3 border border-custom-pink rounded focus:outline-none focus:ring-2 focus:ring-custom-pink transition-all duration-300 ease-in-out" />
                @error('email')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-5 relative">
                <input wire:model="password" type="password" id="password" placeholder="Enter password"
                    class="w-full p-3 border border-custom-pink rounded focus:outline-none focus:ring-2 focus:ring-custom-pink transition-all duration-300 ease-in-out" />
                @error('password')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center ml-1">
                    <input wire:model="remember" type="checkbox" id="checkbox1" class="custom-checkbox mr-3">
                    <label for="checkbox1" class="text-gray-700 cursor-pointer">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}"
                    class="text-sm text-custom-pink font-medium hover:text-pink-700 transition-colors duration-200">Forgot
                    password?</a>
            </div>
            <button
                class="w-full bg-custom-pink text-white py-3 rounded hover:bg-pink-700 transition-colors duration-300 ease-in-out">Login</button>
        </form>

        {{-- <!-- Google Sign-In Button -->
            <div class="mt-5">
                <button class="w-full bg-blue-500 text-white py-3 rounded hover:bg-blue-600 transition-colors duration-300 ease-in-out">
                    <i class="fab fa-google mr-3"></i> Login with Google
                </button>
            </div> --}}

        <p class="mt-4 text-sm text-center text-gray-700">Don't have an account? <a href="{{ route('register') }}"
                class="text-custom-pink font-medium hover:text-pink-700 transition-colors duration-200">Sign up here</a>
        </p>
    </div>
</div>
