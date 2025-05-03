<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>


<div
    class="w-full max-w-4xl p-8 bg-white rounded-2xl shadow-xl flex flex-col lg:flex-row overflow-hidden border-4 border-white">

    <div class="w-full lg:w-2/3 p-10 flex flex-col justify-center">

        <flux:text class="text-center">
            {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </flux:text>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <button wire:click="sendVerification"
                class="w-full bg-custom-pink text-white py-3 rounded hover:bg-pink-700 transition-colors duration-300 ease-in-out">
                {{ __('Resend verification email') }}
            </button>

            <flux:link class="text-sm cursor-pointer" wire:click="logout">
                {{ __('Log out') }}
            </flux:link>
        </div>


        {{-- <!-- Google Sign-In Button -->
            <div class="mt-5">
                <button class="w-full bg-blue-500 text-white py-3 rounded hover:bg-blue-600 transition-colors duration-300 ease-in-out">
                    <i class="fab fa-google mr-3"></i> Login with Google
                </button>
            </div> --}}

        <p class="mt-4 text-sm text-center text-gray-700">Already have an account? <a href="{{ route('login') }}"
                class="text-custom-pink font-medium hover:text-pink-700 transition-colors duration-200">Sign in here</a>
        </p>
    </div>
</div>
