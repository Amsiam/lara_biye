<?php

use function Livewire\Volt\{state, computed};

state(['profileId']);

$user = computed(function () {
    return \App\Models\User::with('basicInfo', 'location', 'education', 'physical_attr', 'hobby', 'language', 'personal', 'spiritualSocial', 'lifestyle', 'partnerExpectation', 'family', 'parmanent', 'siblingInfo')->where('id', $this->profileId)->findOrFail($this->profileId);
});

$deleteAccount = function () {
    if ($this->user->id != auth()->user()?->id) {
        session()->flash('error', 'You are not authorized to delete this account.');
        return redirect()->route('profile', ['profileId' => $this->user->id]);
    }
    $this->user->delete();
    session()->flash('message', 'Account deleted successfully.');
    return redirect()->route('home');
};

$buyConnection = function () {
    return redirect(route('packages'));
};

$sendConnection = function () {
    if ($this->user->id == auth()->user()?->id) {
        session()->flash('error', 'You cannot send a connection request to yourself.');
        return redirect()->route('profile', ['profileId' => $this->user->id]);
    }
    if (auth()->user()?->connection()?->first()?->connection <= 0) {
        session()->flash('error', 'You do not have enough connections to send a request.');
        return redirect()->route('profile', ['profileId' => $this->user->id]);
    }
    if (auth()->user()?->sendConnectionRequest($this->user)) {
        auth()->user()->connection()->decrement('connection', 1);

        if (auth()->user()->isConnected($this->user->id)) {
            $this->user->notifications()->create([
                'sender_id' => auth()->user()->id,
                'message' => auth()->user()->name . ' accepted your connection request.',
            ]);
        } else {
            $this->user->notifications()->create([
                'sender_id' => auth()->user()->id,
                'message' => auth()->user()->name . ' sent you a connection request.',
            ]);
        }

        session()->flash('message', 'Connection request sent successfully.');
    } else {
        session()->flash('error', 'Connection request failed.');
    }

    return redirect()->route('profile', ['profileId' => $this->user->id]);
};

?>

<div class="max-w-6xl mx-auto flex flex-col md:flex-row mt-20 px-4 gap-6">
    <!-- Sidebar -->
    <div class="w-full md:w-1/4 bg-custom-red text-white p-6 rounded-2xl shadow-xl border border-gray-200">
        <div class="text-center">
            <!-- Profile Image -->
            {{-- <div
                class="w-32 h-32 mx-auto border border-gray-300 rounded-full overflow-hidden flex items-center justify-center">
                <img class="w-full h-full object-cover" src="./assets/UserProfile.png" alt="Profile Image">
            </div> --}}

            <livewire:profile.upload-profile :user="$this->user" :previewUrl="$this->user->basicInfo?->image" />

            <!-- User Name & Followers -->
            <div class="mt-4 flex items-center justify-center gap-2">
                @if (auth()->user()->isConnected($this->user->id) || auth()->user()?->id == $this->user->id)
                    <h2 class="text-xl font-bold uppercase text-white">{{ $this->user->name }}</h2>
                @endif

                @if ($this->user->isProfileVerified())
                    <span class="inline-flex items-center group" title="Profile Verified by Admin">
                        <svg class="w-6 h-6 text-blue-300 group-hover:text-blue-400 transition-colors drop-shadow-lg"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
            {{-- <p class="text-gray-100 text-sm mt-1">0 Followers</p> --}}
            <hr class="my-4 border-white/30">
        </div>

        <!-- Package Information -->

        <div class="mt-4 p-4 bg-white rounded-xl shadow-lg flex flex-col space-y-3">
            <button wire:click="buyConnection" wire:confirm="Are you sure you want to buy a connection?"
                class="w-full bg-custom-pink hover:bg-pink-600 py-3 rounded-lg shadow-md hover:shadow-xl text-white font-semibold transition-all duration-300 transform hover:scale-105">
                🎟️ Buy Connection ({{ auth()->user()?->connection()?->first()?->connection ?? 0 }})
            </button>
            @if (auth()->user()?->id == $this->user->id)

                <button wire:click="deleteAccount" wire:confirm="Are you sure you want to delete your account?"
                    class="w-full bg-red-500 hover:bg-red-600 py-3 rounded-lg shadow-md hover:shadow-xl text-white font-semibold transition-all duration-300 transform hover:scale-105">
                    ❌ Close Account
                </button>
            @else
                @if (auth()->user()->isConnected($this->user->id))
                    <button
                        class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg shadow-md cursor-not-allowed border-2 border-gray-300 font-semibold">
                        ✅ You are already connected
                    </button>
                @elseif (auth()->user()->hasSentConnectionRequest($this->user))
                    <button wire:click="sendConnection"
                        wire:confirm="This action cost you a connection. Will you proceed?"
                        class="w-full bg-green-500 hover:bg-green-600 py-3 rounded-lg shadow-md hover:shadow-xl text-white font-semibold transition-all duration-300 transform hover:scale-105">
                        ✓ Accept Request
                    </button>
                @elseif (auth()->user()->isConnectionPending($this->user->id))
                    <button
                        class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg shadow-md cursor-not-allowed border-2 border-gray-300 font-semibold">
                        ⏳ Pending connection request
                    </button>
                @else
                    <button wire:click="sendConnection"
                        wire:confirm="This action cost you a connection. Will you proceed?"
                        class="w-full bg-custom-pink hover:bg-pink-600 py-3 rounded-lg shadow-md hover:shadow-xl text-white font-semibold transition-all duration-300 transform hover:scale-105">
                        🎟️ Send Connection Request
                    </button>
                @endif

            @endif
        </div>

        <!-- Sidebar Buttons -->
        {{-- <div class="mt-6 space-y-2">

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
        </div> --}}
    </div>


    <!-- Main Profile Section -->
    <div class="w-full md:w-3/4 bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-3xl font-bold text-gray-900">Profile Information</h2>
        </div>
        <p class="text-custom-pink font-semibold mb-6">Member ID - {{ $this->user->id }}</p>

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
            <livewire:profile.personal_attitude :user="$this->user" :personal="$this->user?->personal" />
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
