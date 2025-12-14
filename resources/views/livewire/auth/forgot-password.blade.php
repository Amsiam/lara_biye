<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 p-8">
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-custom-red mb-2">{{ __('Forgot password') }}</h2>
        <p class="text-gray-600 text-sm">
            {{ __('Enter your email to receive a password reset link') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Email Address') }}</label>
            <input wire:model="email" type="email" id="email" placeholder="email@example.com" autofocus required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
            @error('email')
                <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-custom-pink text-white py-3.5 rounded-lg font-semibold hover:bg-custom-red shadow-md hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300">
            {{ __('Email password reset link') }}
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            {{ __('Or, return to') }}
            <a href="{{ route('login') }}" wire:navigate
                class="font-semibold text-custom-pink hover:text-custom-red transition-colors duration-200 hover:underline">
                {{ __('log in') }}
            </a>
            </p>
    </div>
</div>
