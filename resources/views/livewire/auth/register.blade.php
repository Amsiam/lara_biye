<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $dob = '';
    public string $gender = 'MALE';
    public string $religion = 'ISLAM';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string'],
            'religion' => ['required', 'string'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        try {
            DB::transaction(function () use ($validated) {
                event(new Registered(($user = User::create($validated))));

                //create all other options also

                $user->basicInfo()->create([
                    'dob' => $this->dob,
                    'bio' => '',
                    'gender' => $this->gender,
                    'religion' => $this->religion,
                    'height' => 0,
                    'weight' => 0,
                ]);
                $user->physical_attr()->create();
                $user->personal()->create();
                $user->lifestyle()->create();
                $user->family()->create();
                $user->education()->create();
                $user->location()->create();
                $user->hobby()->create();
                $user->partnerExpectation()->create();
                $user->parmanent()->create();
                $user->spiritualSocial()->create();

                Auth::login($user);
            });

            $this->redirectIntended(default: route('profile', Auth::user()->id, absolute: false), navigate: true);
        } catch (\Exception $e) {
            $this->addError('name', 'Something went wrong');
            Log::error($e);
        }
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
        <h2 class="text-4xl font-semibold mb-6 text-custom-pink">Create your account</h2>
        <form wire:submit.prevent="register">
            <div class="mb-5">
                <input wire:model="name" type="text" placeholder="Enter name"
                    class="w-full p-3 border border-custom-pink rounded focus:outline-none focus:ring-2 focus:ring-custom-pink transition-all duration-300 ease-in-out" />
                @error('name')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-5">
                <input wire:model="dob" type="date" placeholder="Enter Date of birth"
                    class="w-full p-3 border border-custom-pink rounded focus:outline-none focus:ring-2 focus:ring-custom-pink transition-all duration-300 ease-in-out" />
                @error('dob')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-5">
                <select wire:model="gender"
                    class="w-full p-3 border border-custom-pink rounded focus:outline-none focus:ring-2 focus:ring-custom-pink transition-all duration-300 ease-in-out">
                    @foreach (['MALE', 'FEMALE'] as $gender)
                        <option>{{ $gender }}</option>
                    @endforeach
                </select>
                @error('gender')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-5">
                <select wire:model="religion"
                    class="w-full p-3 border border-custom-pink rounded focus:outline-none focus:ring-2 focus:ring-custom-pink transition-all duration-300 ease-in-out">
                    @foreach (['ISLAM', 'HINDU', 'CHRISTIAN', 'BUDDHIST', 'OTHER'] as $religion)
                        <option>{{ $religion }}</option>
                    @endforeach
                </select>
                @error('religion')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <hr class="my-4 border-t border-custom-pink" />
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

            <div class="mb-5 relative">
                <input wire:model="password_confirmation" type="password" id="password_confirmation"
                    placeholder="Enter password again"
                    class="w-full p-3 border border-custom-pink rounded focus:outline-none focus:ring-2 focus:ring-custom-pink transition-all duration-300 ease-in-out" />
                @error('password_confirmation')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <button
                class="w-full bg-custom-pink text-white py-3 rounded hover:bg-pink-700 transition-colors duration-300 ease-in-out">Register</button>
        </form>

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
