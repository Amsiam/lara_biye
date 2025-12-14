<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (
            !Auth::guard('web')->validate([
                'email' => Auth::user()->email,
                'password' => $this->password,
            ])
        ) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 p-8">
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-custom-red mb-2">{{ __('Confirm password') }}</h2>
        <p class="text-gray-600 text-sm">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form wire:submit="confirmPassword" class="space-y-6">
        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Password') }}</label>
            <input wire:model="password" type="password" id="password" required autocomplete="current-password"
                placeholder="{{ __('Password') }}"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
            @error('password')
                <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-custom-pink text-white py-3.5 rounded-lg font-semibold hover:bg-custom-red shadow-md hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300">
            {{ __('Confirm') }}
        </button>
    </form>
</div>
