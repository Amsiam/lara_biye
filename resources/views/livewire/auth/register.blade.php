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
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Models\ConnectionHistory;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $mobile = '';
    public string $email = '';
    public string $password = '';
    public string $dob = '';
    public string $gender = 'MALE';
    public string $religion = 'ISLAM';
    public ?string $nid = '';
    public ?string $birth_certificate = '';
    public ?string $student_id = '';
    public string $verification_type = 'nid'; // Default to NID
    public ?string $university = '';
    public string $password_confirmation = '';
    public string $referral_code = '';
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
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // Clear the other field based on selection to ensure cleanliness
        if ($this->verification_type === 'nid') {
            $this->birth_certificate = null;
        } else {
            $this->nid = null;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20', 'regex:/^(?:\+?88)?01[3-9]\d{8}$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'dob' => ['required', 'date', 'before:17 years ago'],
            'gender' => ['required', 'string'],
            'religion' => ['required', 'string'],
            'nid' => ['nullable', 'required_if:verification_type,nid', 'string', 'max:20'],
            'birth_certificate' => ['nullable', 'required_if:verification_type,birth_certificate', 'string', 'max:30'],
            'student_id' => ['required', 'string', 'max:20'],
            'university' => ['required', 'string', 'max:100'],
            'referral_code' => ['nullable', 'string', 'exists:users,referral_code'],
            'captcha' => ['required', 'string', 'size:6'],
        ]);

        // Validate CAPTCHA
        if (!$this->validateCaptcha()) {
            $this->generateCaptcha();
            return;
        }

        $validated['password'] = Hash::make($validated['password']);

        try {
            DB::transaction(function () use ($validated) {
                // Handle referral logic
                $referrer = null;
                if (!empty($this->referral_code)) {
                    $referrer = User::where('referral_code', $this->referral_code)->first();
                    if ($referrer) {
                        $validated['referrer_id'] = $referrer->id;
                    }
                }
                // Remove referral_code from validated as it's not a column in users table
                unset($validated['referral_code']);

                event(new Registered(($user = User::create($validated))));

                // Reward connections
                $initialConnections = 3; // Every new user gets 3 free connections
                if ($referrer) {
                    $initialConnections += 2; // Bonus 2 connections if referred (Total 5)
                    // Referrer gets 2 connections
                    $referrerConnection = $referrer->connection;
                    $reward = (int) Setting::get('referral_reward', 2);
                    if (!$referrerConnection) {
                        $referrer->connection()->create(['connection' => $reward]);
                    } else {
                        $referrer->connection()->increment('connection', $reward);
                    }
                    // Log for referrer
                    ConnectionHistory::create([
                        'user_id' => $referrer->id,
                        'amount' => $reward,
                        'type' => 'referral_bonus',
                        'description' => 'Bonus for referring ' . $user->name,
                    ]);
                }
                $user->connection()->create(['connection' => $initialConnections]);

                // Log for new user
                ConnectionHistory::create([
                    'user_id' => $user->id,
                    'amount' => 3,
                    'type' => 'signup_bonus',
                    'description' => 'Welcome bonus for joining',
                ]);

                if ($referrer) {
                    ConnectionHistory::create([
                        'user_id' => $user->id,
                        'amount' => 2,
                        'type' => 'referral_bonus',
                        'description' => 'Bonus for using referral code',
                    ]);
                }

                //create all other options also

                $user->basicInfo()->create([
                    'dob' => $this->dob,
                    'bio' => '',
                    'gender' => $this->gender,
                    'religion' => $this->religion,
                    'height' => 0,
                    'weight' => 0,
                    'nid' => $this->nid,
                    'birth_certificate' => $this->birth_certificate,
                    'student_id' => $this->student_id,
                    'university' => $this->university,
                ]);
                $user->physical_attr()->create();
                $user->personal()->create();
                $user->lifestyle()->create();
                $user->language()->create();
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
            $this->generateCaptcha();
            Log::error($e);
        }
    }
}; ?>

<div
    class="w-full max-w-6xl bg-white rounded-xl shadow-2xl flex flex-col lg:flex-row overflow-hidden border border-gray-100">
    <!-- Left Section with Image -->
    <div
        class="hidden lg:flex lg:w-2/5 relative bg-gradient-to-br from-custom-pink/10 to-custom-red/10 p-8 items-center justify-center">
        <div class="text-center space-y-4">
            <img src="{{ asset('img/image 70.png') }}" alt="Registration Image"
                class="w-full max-w-sm mx-auto rounded-xl shadow-lg">
            <h3 class="text-2xl font-bold text-custom-red">Join Us Today!</h3>
            <p class="text-gray-600">Create an account to start your journey</p>
        </div>
    </div>

    <!-- Right Section -->
    <div class="w-full lg:w-3/5 p-8 lg:p-10 flex flex-col justify-center">
        <div class="mb-6">
            <h2 class="text-3xl lg:text-4xl font-bold text-custom-red mb-2">Create your account</h2>
            <p class="text-gray-600">Fill in the details below to get started</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-5 max-h-[calc(100vh-250px)] overflow-y-auto pr-2">
            <!-- Personal Information Section -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-custom-red border-b-2 border-custom-pink/30 pb-2">Personal Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input wire:model="name" type="text" id="name" placeholder="Enter your full name"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                        @error('name')
                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>



                    <div>
                        <label for="dob" class="block text-sm font-semibold text-gray-700 mb-2">Date of
                            Birth</label>
                        <input wire:model="dob" type="date" id="dob"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                        @error('dob')
                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                        <x-select-input wireModel="gender" placeholder="Select Gender" :options="['MALE' => 'Male', 'FEMALE' => 'Female']" />
                        @error('gender')
                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="religion" class="block text-sm font-semibold text-gray-700 mb-2">Religion</label>
                        <x-select-input wireModel="religion" placeholder="Select Religion" :options="[
        'ISLAM' => 'Islam',
        'HINDU' => 'Hindu',
        'CHRISTIAN' => 'Christian',
        'BUDDHIST' => 'Buddhist',
        'OTHER' => 'Other',
    ]" />
                        @error('religion')
                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Verification Information Section -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-custom-red border-b-2 border-custom-pink/30 pb-2">Verification Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Verification Type Selector -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Verify ID With</label>
                        <div class="flex gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" wire:model.live="verification_type" value="nid"
                                    class="form-radio text-custom-pink focus:ring-custom-pink h-5 w-5">
                                <span class="ml-2 text-gray-700 font-medium">National ID (NID)</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" wire:model.live="verification_type" value="birth_certificate"
                                    class="form-radio text-custom-pink focus:ring-custom-pink h-5 w-5">
                                <span class="ml-2 text-gray-700 font-medium">Birth Certificate</span>
                            </label>
                        </div>
                    </div>

                    @if($verification_type === 'nid')
                        <div class="md:col-span-2">
                            <label for="nid" class="block text-sm font-semibold text-gray-700 mb-2">NID Number</label>
                            <input wire:model="nid" type="text" id="nid" placeholder="Enter NID number"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                            @error('nid')
                                <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div class="md:col-span-2">
                            <label for="birth_certificate" class="block text-sm font-semibold text-gray-700 mb-2">Birth
                                Certificate</label>
                            <input wire:model="birth_certificate" type="text" id="birth_certificate"
                                placeholder="Enter Birth Certificate No."
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                            @error('birth_certificate')
                                <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Student Info -->
                    <div>
                        <label for="student_id" class="block text-sm font-semibold text-gray-700 mb-2">Student
                            ID</label>
                        <input wire:model="student_id" type="text" id="student_id" placeholder="Enter student ID"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                        @error('student_id')
                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="university" class="block text-sm font-semibold text-gray-700 mb-2">University
                            Name</label>
                        <input wire:model="university" type="text" id="university" placeholder="Enter university name"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                        @error('university')
                            <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Credentials Section -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-custom-red border-b-2 border-custom-pink/30 pb-2">Account Credentials
                </h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email
                                Address</label>
                            <input wire:model="email" type="email" id="email" placeholder="example@email.com"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                            @error('email')
                                <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="mobile" class="block text-sm font-semibold text-gray-700 mb-2">Mobile
                                Number</label>
                            <input wire:model="mobile" type="text" id="mobile" placeholder="Enter your mobile number"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                            @error('mobile')
                                <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div x-data="{ show: false }">
                            <label for="password"
                                class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative" style="position: relative;">
                                <input wire:model="password" :type="show ? 'text' : 'password'" id="password"
                                    placeholder="Create a strong password"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300 pr-10" />
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-custom-pink focus:outline-none"
                                    style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center;">
                                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                            clip-rule="evenodd" />
                                        <path
                                            d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                                    </svg>
                                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        viewBox="0 0 20 20" fill="currentColor" style="display: none;">
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

                        <div x-data="{ show: false }">
                            <label for="password_confirmation"
                                class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                            <div class="relative" style="position: relative;">
                                <input wire:model="password_confirmation" :type="show ? 'text' : 'password'"
                                    id="password_confirmation" placeholder="Re-enter your password"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300 pr-10" />
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-custom-pink focus:outline-none"
                                    style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center;">
                                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                            clip-rule="evenodd" />
                                        <path
                                            d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                                    </svg>
                                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral Code Section -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-custom-red border-b-2 border-custom-pink/30 pb-2">Referral (Optional)
                </h3>
                <div>
                    <label for="referral_code" class="block text-sm font-semibold text-gray-700 mb-2">Referral
                        Code</label>
                    <input wire:model="referral_code" type="text" id="referral_code" placeholder="Enter referral code if any"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all duration-300" />
                    @error('referral_code')
                        <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
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
                Create Account
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}"
                    class="font-semibold text-custom-pink hover:text-custom-red transition-colors duration-200 hover:underline">
                    Sign in here
                </a>
            </p>
        </div>
        <div class="mt-6 text-center">
            <a href="/" class="bg-custom-pink px-5 py-2 mt-5 text-white rounded">
                Go Back Home
            </a>
        </div>
    </div>
</div>