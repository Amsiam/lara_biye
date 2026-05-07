<?php
use function Livewire\Volt\{state, computed};

state(['tab' => 'connected']);

$counts = computed(function () {
    $user = auth()->user();
    return [
        'connected' => $user->connectedUsers()->where('status', 'ACCEPTED')->count(),
        'pending'   => $user->connectedUsers()->where('status', 'PENDING')->count(),
        'received'  => $user->rConnectedUsers()->where('status', 'PENDING')->count(),
    ];
});

$profiles = computed(function () {
    $user = auth()->user();
    return match($this->tab) {
        'connected' => $user->connectedUsers()->where('status', 'ACCEPTED')->with('basicInfo')->get(),
        'pending'   => $user->connectedUsers()->where('status', 'PENDING')->with('basicInfo')->get(),
        'received'  => $user->rConnectedUsers()->where('status', 'PENDING')->with('basicInfo')->get(),
        default     => collect(),
    };
});

$changeTab = function ($tab) {
    $this->tab = $tab;
};

$acceptRequest = function ($userId) {
    $sender = \App\Models\User::findOrFail($userId);
    try {
        auth()->user()->sendConnectionRequest($sender);
        $sender->notifications()->create([
            'sender_id' => auth()->id(),
            'message'   => auth()->user()->name . ' accepted your connection request.',
        ]);
        session()->flash('message', 'Connection accepted.');
    } catch (\Exception $e) {
        session()->flash('error', $e->getMessage());
    }
};

$declineRequest = function ($userId) {
    auth()->user()->rConnectedUsers()->detach($userId);
    session()->flash('message', 'Request declined.');
};

$cancelRequest = function ($userId) {
    auth()->user()->connectedUsers()->detach($userId);
    session()->flash('message', 'Request cancelled.');
};
?>

<div class="max-w-6xl mx-auto px-4 py-8 sm:py-10">

    <!-- Flash messages -->
    @if (session('message'))
        <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
            <i class="ph-bold ph-check-circle text-lg"></i>
            {{ session('message') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium">
            <i class="ph-bold ph-warning-circle text-lg"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-custom-red">My Connections</h1>
            <p class="text-gray-500 text-sm mt-1">Manage your connection requests and connected profiles</p>
        </div>
        @if ($this->counts['received'] > 0)
            <span class="flex items-center gap-1.5 bg-pink-50 border border-pink-200 text-pink-700 px-3 py-1.5 rounded-full text-sm font-semibold">
                <i class="ph-fill ph-bell-ringing text-base"></i>
                {{ $this->counts['received'] }} new {{ Str::plural('request', $this->counts['received']) }}
            </span>
        @endif
    </div>

    <!-- Tabs -->
    <div class="flex flex-wrap gap-1 sm:gap-2 mb-6 border-b border-gray-200">
        @php
            $tabs = [
                'connected' => ['label' => 'Connected',     'icon' => 'ph-handshake'],
                'pending'   => ['label' => 'Sent',          'icon' => 'ph-paper-plane-tilt'],
                'received'  => ['label' => 'Received',      'icon' => 'ph-tray-arrow-down'],
            ];
        @endphp
        @foreach ($tabs as $key => $meta)
            <button wire:click="changeTab('{{ $key }}')"
                class="flex items-center gap-1.5 px-3 sm:px-4 py-2.5 rounded-t-lg text-sm font-semibold border-b-2 transition-all duration-200
                    {{ $tab === $key
                        ? 'border-custom-pink text-custom-pink bg-pink-50'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="ph-bold {{ $meta['icon'] }} text-base"></i>
                <span>{{ $meta['label'] }}</span>
                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold rounded-full
                    {{ $tab === $key ? 'bg-custom-pink text-white' : 'bg-gray-200 text-gray-600' }}">
                    {{ $this->counts[$key] }}
                </span>
            </button>
        @endforeach
    </div>

    <!-- Loading -->
    <div wire:loading class="flex justify-center py-10">
        <div class="flex items-center gap-2 text-custom-pink font-semibold">
            <i class="ph-bold ph-circle-notch animate-spin text-2xl"></i>
            Loading...
        </div>
    </div>

    <div wire:loading.remove>
        @if ($this->profiles->isEmpty())
            <!-- Empty state -->
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <i class="ph-bold {{ $tab === 'connected' ? 'ph-heart-break' : ($tab === 'pending' ? 'ph-paper-plane-tilt' : 'ph-tray-arrow-down') }} text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">
                    {{ $tab === 'connected' ? 'No connections yet' : ($tab === 'pending' ? 'No sent requests' : 'No received requests') }}
                </h3>
                <p class="text-gray-500 text-sm max-w-xs">
                    {{ $tab === 'connected'
                        ? 'Start exploring profiles and send connection requests to meet your match.'
                        : ($tab === 'pending'
                            ? 'You have not sent any connection requests yet.'
                            : 'No one has sent you a connection request yet.') }}
                </p>
                @if ($tab !== 'received')
                    <a href="{{ route('search') }}"
                        class="mt-6 inline-flex items-center gap-2 px-6 py-2.5 bg-custom-pink text-white rounded-lg font-semibold hover:bg-custom-red transition-colors">
                        <i class="ph-bold ph-magnifying-glass"></i>
                        Browse Profiles
                    </a>
                @endif
            </div>

        @elseif ($tab === 'connected')
            <!-- Connected: full profile cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach ($this->profiles as $profile)
                    <x-single-profile :profile="$profile" />
                @endforeach
            </div>

        @else
            <!-- Pending / Received: action cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($this->profiles as $profile)
                    @php
                        $age = $profile->basicInfo?->dob ? \Carbon\Carbon::parse($profile->basicInfo->dob)->age : null;
                        $gender = $profile->basicInfo?->gender;
                        $genderColor = $gender === 'FEMALE' ? 'text-pink-500' : 'text-blue-500';
                    @endphp
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 flex items-center gap-4 p-4">
                        <!-- Avatar -->
                        <div class="relative w-16 h-16 shrink-0">
                            <img src="{{ route('profile.image', $profile->id) }}"
                                 class="w-16 h-16 rounded-full object-cover border-2 border-gray-100"
                                 onerror="this.src='{{ asset('default.png') }}'">
                            @if ($gender)
                                <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-white flex items-center justify-center shadow-sm border border-gray-100">
                                    <i class="ph-bold {{ $gender === 'FEMALE' ? 'ph-gender-female' : 'ph-gender-male' }} text-xs {{ $genderColor }}"></i>
                                </span>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-800 truncate text-sm">{{ $profile->name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                ID #{{ $profile->id }}
                                @if ($age) · {{ $age }} yrs @endif
                            </p>
                            @if ($profile->basicInfo?->religion)
                                <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst(strtolower($profile->basicInfo->religion)) }}</p>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2 shrink-0">
                            @if ($tab === 'received')
                                <button wire:click="acceptRequest({{ $profile->id }})"
                                    wire:confirm="Accept connection request from {{ $profile->name }}?"
                                    class="flex items-center gap-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition-colors">
                                    <i class="ph-bold ph-check"></i> Accept
                                </button>
                                <button wire:click="declineRequest({{ $profile->id }})"
                                    wire:confirm="Decline request from {{ $profile->name }}?"
                                    class="flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-red-50 text-gray-600 hover:text-red-600 text-xs font-semibold rounded-lg transition-colors border border-gray-200 hover:border-red-200">
                                    <i class="ph-bold ph-x"></i> Decline
                                </button>
                            @else
                                <a href="{{ route('profile', $profile->id) }}"
                                    class="flex items-center gap-1 px-3 py-1.5 bg-custom-pink text-white text-xs font-semibold rounded-lg hover:bg-pink-600 transition-colors">
                                    <i class="ph-bold ph-user"></i> View
                                </a>
                                <button wire:click="cancelRequest({{ $profile->id }})"
                                    wire:confirm="Cancel your request to {{ $profile->name }}?"
                                    class="flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-red-50 text-gray-600 hover:text-red-600 text-xs font-semibold rounded-lg transition-colors border border-gray-200 hover:border-red-200">
                                    <i class="ph-bold ph-x-circle"></i> Cancel
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
