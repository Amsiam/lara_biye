<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PasswordReset) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-100 p-8">
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-custom-red mb-2">{{ __('Reset password') }}</h2>
        <p class="text-gray-600 text-sm">
            {{ __('Please enter your new password below') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form wire:submit="resetPassword" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Email Address') }}</label>
            <input wire:model="email" type="email" id="email" required autocomplete="email"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
            @error('email')
                <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Password') }}</label>
            <input wire:model="password" type="password" id="password" required autocomplete="new-password"
                placeholder="{{ __('Password') }}"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
            @error('password')
                <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation"
                class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Confirm password') }}</label>
            <input wire:model="password_confirmation" type="password" id="password_confirmation" required
                autocomplete="new-password" placeholder="{{ __('Confirm password') }}"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
            @error('password_confirmation')
                <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-custom-pink text-white py-3.5 rounded-lg font-semibold hover:bg-custom-red shadow-md hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300">
            {{ __('Reset password') }}
        </button>
    </form>
</div>
