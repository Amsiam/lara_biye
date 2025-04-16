<?php

use function Livewire\Volt\{state, computed};

state(['profileId']);

$user = computed(function () {
    return \App\Models\User::with('basicInfo', 'location', 'education', 'physical_attr', 'hobby', 'language', 'personal', 'spiritualSocial', 'lifestyle', 'partnerExpectation', 'family', 'parmanent')->findOrFail($this->profileId);
});

?>

<div class="max-w-6xl mx-auto flex flex-col md:flex-row mt-20">
    <!-- Sidebar -->
    <div class="w-full md:w-1/4 bg-custom-red text-white p-6 rounded-lg shadow-lg">
        <div class="text-center">
            <!-- Profile Image -->
            <div
                class="w-32 h-32 mx-auto border border-gray-300 rounded-full overflow-hidden flex items-center justify-center">
                <img class="w-full h-full object-cover" src="./assets/UserProfile.png" alt="Profile Image">
            </div>

            <!-- User Name & Followers -->
            <h2 class="mt-4 text-xl font-bold uppercase">Aslam Mahmud Siam</h2>
            <p class="text-gray-100 text-sm mt-1">0 Followers</p>
            <hr class="my-3 border-gray-300">
        </div>

        <!-- Package Information -->
        <div class="mt-4 p-4 bg-white text-black rounded-lg shadow">
            <h3 class="text-lg font-semibold text-custom-red">Package Information</h3>
            <p class="mt-2 flex items-center"><span class="mr-2">🎁</span> Free Package</p>
            <p class="text-sm text-gray-700">৳ 0.00</p>

            <h3 class="text-lg font-semibold text-custom-red mt-4">Premium Package</h3>
            <p class="text-sm text-gray-700 mt-1">None</p>

            <h3 class="text-lg font-semibold text-custom-red mt-4">Package Available</h3>
            <p class="text-sm text-gray-700 mt-1">2</p>

            <h3 class="text-lg font-semibold text-custom-red mt-4">PACKAGE EXPIRES AT</h3>
            <p class="text-sm font-medium mt-1">2025-03-19</p>
        </div>

        <!-- Sidebar Buttons -->
        <div class="mt-6 space-y-2">
            <button
                class="w-full bg-white text-custom-pink py-2 rounded-md shadow hover:bg-custom-pink hover:text-white transition">
                📷 Gallery
            </button>
            <button
                class="w-full bg-white text-custom-pink py-2 rounded-md shadow hover:bg-custom-pink hover:text-white transition">
                ❤️ Happy Story
            </button>
            <button
                class="w-full bg-white text-custom-pink py-2 rounded-md shadow hover:bg-custom-pink hover:text-white transition">
                🎟️ My Package
            </button>
            <button
                class="w-full bg-white text-custom-pink py-2 rounded-md shadow hover:bg-custom-pink hover:text-white transition">
                💳 Payment Information
            </button>
            <button
                class="w-full bg-white text-custom-pink py-2 rounded-md shadow hover:bg-custom-pink hover:text-white transition">
                🔒 Picture Privacy
            </button>
            <button
                class="w-full bg-white text-custom-pink py-2 rounded-md shadow hover:bg-custom-pink hover:text-white transition">
                🔑 Change Password
            </button>
            <button
                class="w-full bg-white text-custom-pink py-2 rounded-md shadow hover:bg-custom-pink hover:text-white transition">
                ❌ Close Account
            </button>
        </div>
    </div>


    <!-- Main Profile Section -->
    <div class="w-full md:w-3/4 bg-white p-6 rounded-lg shadow-lg ml-0 md:ml-6">
        <div class="flex  justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Profile Information</h2>
        </div>
        <p class="text-custom-pink">Member ID - {{ $this->user->id }}</p>

        <!-- Introduction -->
        <livewire:profile.introduction :bio="$this->user?->basicInfo" />

        <!-- Basic Information -->
        <livewire:profile.basic_info :user="$this->user" :bio="$this->user?->basicInfo" />


        <!-- Present Address -->
        @if ($this->user->id == auth()->user()?->id || $this->user?->location?->is_shown)
            <livewire:profile.present_address :user="$this->user" :address="$this->user?->location" />
        @endif


        <!-- Education And Career -->
        @if ($this->user->id == auth()->user()?->id || $this->user?->education?->is_shown)
            <livewire:profile.education :user="$this->user" :education="$this->user?->education" />
        @endif

        <!-- Physical Attributes -->


        @if ($this->user->id == auth()->user()?->id || $this->user?->physical_attr?->is_shown)
            <livewire:profile.physical_attr :user="$this->user" :physical="$this->user?->physical_attr" />
        @endif

        <!-- Language -->
        @if ($this->user->id == auth()->user()?->id || $this->user?->language?->is_shown)
            <livewire:profile.language :user="$this->user" :lang="$this->user?->language" />
        @endif

        <!-- Hobbies And Interests -->
        @if ($this->user->id == auth()->user()?->id || $this->user?->hobby?->is_shown)
            <livewire:profile.hobby :user="$this->user" :hobby="$this->user?->hobby" />
        @endif

        <!-- Personal Attitude And Behavior -->
        @if ($this->user->id == auth()->user()?->id || $this->user?->personal?->is_shown)
            <livewire:profile.personal_attitude :user="$this->user" :hobby="$this->user?->personal" />
        @endif

        {{-- <!-- Residency Information -->
        @if ($this->user->id == auth()->user()?->id || $this->user?->residencyInfo?->is_shown)
            <livewire:profile.residency :user="$this->user" :residencyInfo="$this->user?->residencyInfo" />
        @endif --}}

        <!-- Spiritual And Social Background -->

        @if ($this->user->id == auth()->user()?->id || $this->user?->spiritualSocial?->is_shown)
            <livewire:profile.spiritual :user="$this->user" :spiritualSocial="$this->user?->spiritualSocial" />
        @endif

        <!-- Astronomic Information -->
        @if ($this->user->id == auth()->user()?->id || $this->user?->lifestyle?->is_shown)
            <livewire:profile.lifestyle :user="$this->user" :lifestyle="$this->user?->lifestyle" />
        @endif

        <!-- Astronomic Information -->
        {{-- @if ($this->user->id == auth()->user()?->id || $this->user?->astronomicInfo?->is_shown)
            <livewire:profile.astronomic :user="$this->user" :astronomicInfo="$this->user?->astronomicInfo" />
        @endif --}}

        <!-- Permanent Address -->
        @if ($this->user->id == auth()->user()?->id || $this->user?->parmanent?->is_shown)
            <livewire:profile.parmanent :user="$this->user" :parmanent="$this->user?->parmanent" />
        @endif

        <!-- Family Information -->
        @if ($this->user->id == auth()->user()?->id || $this->user?->family?->is_shown)
            <livewire:profile.family :user="$this->user" :family="$this->user?->family" />
        @endif

        <!-- Additional Personal Details -->
        {{-- <div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
            <div class="flex justify-between items-center  bg-custom-red p-3 border-b">
                <h3 class="font-semibold text-white">Additional Personal Details</h3>
                <div>
                    <button class="text-white bg-custom-pink px-2 rounded mr-2">
                        Show
                    </button>
                    <button class="text-white bg-custom-pink px-2 rounded">✎</button>
                </div>
            </div>
            <div class="p-4 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">HOME DISTRICT</p>
                    <p>-</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">FAMILY RESIDENCE</p>
                    <p>-</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">FATHER'S OCCUPATION</p>
                    <p>-</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">SPECIAL CIRCUMSTANCES</p>
                    <p>-</p>
                </div>
            </div>
        </div> --}}

        <!-- Partner Expectation -->
        <livewire:profile.partner :user="$this->user" :partner="$this->user?->partnerExpectation" />
    </div>
</div>
