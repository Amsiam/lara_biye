<?php

use function Livewire\Volt\{state, computed, on};

state(['profileId']);

on(['profile-section-saved' => '$refresh']);

$user = computed(function () {
    return \App\Models\User::with('basicInfo', 'location', 'education', 'physical_attr', 'hobby', 'language', 'personal', 'spiritualSocial', 'lifestyle', 'partnerExpectation', 'family', 'parmanent', 'siblingInfo')->where('id', $this->profileId)->where('is_admin', false)->findOrFail($this->profileId);
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
    $authUser = auth()->user();
    $profileId = $this->user->id;

    if ($this->user->id == $authUser?->id) {
        session()->flash('error', 'You cannot send a connection request to yourself.');
        return redirect()->route('profile', ['profileId' => $profileId]);
    }

    $balance = (int) ($authUser?->connection()?->first()?->getAttribute('connection') ?? 0);
    if ($balance <= 0) {
        session()->flash('error', 'You do not have enough connections to send a request.');
        return redirect()->route('profile', ['profileId' => $profileId]);
    }

    try {
        $sent = $authUser->sendConnectionRequest($this->user);
    } catch (\Exception $e) {
        session()->flash('error', $e->getMessage());
        return redirect()->route('profile', ['profileId' => $profileId]);
    }

    if ($sent) {
        if ($authUser->isConnected($this->user->id)) {
            $this->user->notifications()->create([
                'sender_id' => $authUser->id,
                'message' => $authUser->name . ' accepted your connection request.',
            ]);
        } else {
            $this->user->notifications()->create([
                'sender_id' => $authUser->id,
                'message' => $authUser->name . ' sent you a connection request.',
            ]);
        }
        session()->flash('message', 'Connection request sent successfully.');
    } else {
        session()->flash('error', 'Connection request failed.');
    }

    return redirect()->route('profile', ['profileId' => $profileId]);
};

?>

<div class="max-w-6xl mx-auto flex flex-col md:flex-row mt-4 md:mt-20 px-3 sm:px-4 gap-4 md:gap-6 pb-10">
    <!-- Sidebar -->
    <div class="w-full md:w-1/4 md:sticky md:top-24 md:self-start bg-custom-red text-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-4 md:p-6">

            <!-- Profile Header: horizontal on mobile, centered column on desktop -->
            <div class="flex items-center gap-4 md:flex-col md:items-center md:text-center">

                <!-- Profile Image -->
                <div class="flex-shrink-0">
                    <livewire:profile.upload-profile :user="$this->user" :previewUrl="$this->user->basicInfo?->image" />
                </div>

                <!-- Name + verified: visible on mobile only -->
                <div class="flex-1 min-w-0 md:hidden">
                    @if (auth()->user()->isConnected($this->user->id) || auth()->user()?->id == $this->user->id)
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <h2 class="text-sm font-bold uppercase text-white leading-tight truncate">{{ $this->user->name }}</h2>
                            @if ($this->user->isProfileVerified())
                                <i class="ph-fill ph-seal-check text-base text-pink-300" title="Verified"></i>
                            @endif
                        </div>
                    @endif
                    <p class="text-white/60 text-xs mt-0.5">ID #{{ $this->user->id }}</p>

                    @if (auth()->user()?->id == $this->user->id)
                        @php
                            $completion = $this->user->profileCompletionPercentage();
                            $color = $completion < 50 ? 'bg-red-400' : ($completion < 80 ? 'bg-yellow-400' : 'bg-green-400');
                        @endphp
                        <div class="mt-2">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-white/80">Completion</span>
                                <span class="font-bold text-white">{{ $completion }}%</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-1.5">
                                <div class="{{ $color }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $completion }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Name + verified: desktop only -->
            <div class="hidden md:flex mt-4 items-center justify-center gap-2">
                @if (auth()->user()->isConnected($this->user->id) || auth()->user()?->id == $this->user->id)
                    <h2 class="text-xl font-bold uppercase text-white">{{ $this->user->name }}</h2>
                @endif
                @if ($this->user->isProfileVerified())
                    <span class="inline-flex items-center group" title="Profile Verified by Admin">
                        <i class="ph-fill ph-seal-check text-2xl text-pink-400 group-hover:text-pink-500 transition-colors drop-shadow-lg"></i>
                    </span>
                @endif
            </div>

            <hr class="my-3 md:my-4 border-white/30">

            <!-- Profile Completion: desktop only -->
            @if (auth()->user()?->id == $this->user->id)
                @php
                    $completion = $this->user->profileCompletionPercentage();
                    $color = $completion < 50 ? 'bg-red-500' : ($completion < 80 ? 'bg-yellow-500' : 'bg-green-500');
                @endphp
                <div class="hidden md:block p-4 bg-white rounded-xl shadow-lg border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700">Profile Completion</span>
                        <span class="text-sm font-bold text-custom-pink">{{ $completion }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="{{ $color }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $completion }}%"></div>
                    </div>
                    @if($completion < 100)
                        <p class="text-xs text-gray-500 mt-2 text-center">Complete your profile to get more matches!</p>
                    @else
                        <p class="text-xs text-green-600 mt-2 text-center font-semibold">Great job! Your profile is complete.</p>
                    @endif
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-3 md:mt-4 bg-white rounded-xl shadow-lg p-3 md:p-4 flex flex-col gap-2">
                <button wire:click="buyConnection" wire:confirm="Are you sure you want to buy a connection?"
                    class="w-full bg-custom-pink hover:bg-pink-600 py-2 md:py-3 rounded-lg shadow-md hover:shadow-xl text-white font-semibold text-sm transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-1.5">
                    <i class="ph-bold ph-ticket"></i>
                    <span>Buy Connection</span>
                    <span class="bg-white/20 px-1.5 py-0.5 rounded text-xs font-bold">{{ auth()->user()?->connection()?->first()?->connection ?? 0 }}</span>
                </button>

                @if (auth()->user()?->id == $this->user->id)
                    <button wire:click="deleteAccount" wire:confirm="Are you sure you want to delete your account?"
                        class="w-full bg-red-500 hover:bg-red-600 py-2 md:py-3 rounded-lg shadow-md text-white font-semibold text-sm transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-x-circle"></i> Close Account
                    </button>
                @else
                    @if (auth()->user()->isConnected($this->user->id))
                        <button class="w-full bg-gray-100 text-gray-600 py-2 md:py-3 rounded-lg cursor-not-allowed border border-gray-200 font-semibold text-sm flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-check-circle text-green-500"></i> Already Connected
                        </button>
                    @elseif (auth()->user()->hasSentConnectionRequest($this->user))
                        <button wire:click="sendConnection" wire:confirm="This action cost you a connection. Will you proceed?"
                            class="w-full bg-green-500 hover:bg-green-600 py-2 md:py-3 rounded-lg shadow-md text-white font-semibold text-sm transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-check"></i> Accept Request
                        </button>
                    @elseif (auth()->user()->isConnectionPending($this->user->id))
                        <button class="w-full bg-gray-100 text-gray-600 py-2 md:py-3 rounded-lg cursor-not-allowed border border-gray-200 font-semibold text-sm flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-hourglass-medium text-yellow-500"></i> Request Pending
                        </button>
                    @else
                        <button wire:click="sendConnection" wire:confirm="This action cost you a connection. Will you proceed?"
                            class="w-full bg-custom-pink hover:bg-pink-600 py-2 md:py-3 rounded-lg shadow-md text-white font-semibold text-sm transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-ticket"></i> Send Connection Request
                        </button>
                    @endif
                @endif
            </div>

        </div>
    </div>

    <!-- Main Profile Section -->
    <div class="w-full md:w-3/4 bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-xl border border-gray-100">
        <div class="flex justify-between items-center mb-1">
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Profile Information</h2>
        </div>
        <p class="text-custom-pink font-semibold text-sm mb-4 md:mb-6">Member ID - {{ $this->user->id }}</p>

        <!-- Introduction -->
        <livewire:profile.introduction :bio="$this->user?->basicInfo" />

        <!-- Basic Information -->
        <livewire:profile.basic_info :user="$this->user" :bio="$this->user?->basicInfo" />

        <!-- Privacy Settings (Only visible to profile owner) -->
        @if ($this->user->id == auth()->user()?->id)
            <livewire:profile.privacy-settings />
        @endif

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
                    <button class="text-white bg-custom-pink px-2 rounded mr-2 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                            <path fill-rule="evenodd"
                                d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 8.201 2.372 9.336 6.404.332.66.332 1.42 0 1.186A10.004 10.004 0 0110 17c-4.257 0-8.201-2.372-9.336-6.406zM10 15a5 5 0 100-10 5 5 0 000 10z"
                                clip-rule="evenodd" />
                        </svg>
                        Show
                    </button>
                    <button class="text-white bg-custom-pink px-2 rounded flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path
                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                            <path
                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                        </svg>
                    </button>
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
