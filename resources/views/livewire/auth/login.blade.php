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
