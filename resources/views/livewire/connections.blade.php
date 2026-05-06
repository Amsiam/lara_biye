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

$changeTab = fn($tab) => ($this->tab = $tab);
?>

<div class="max-w-6xl mx-auto px-4 py-8 sm:py-10">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-custom-red">My Connections</h1>
        <p class="text-gray-500 text-sm mt-1">Manage your connection requests and connected profiles</p>
    </div>

    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 sm:gap-3 mb-8 border-b border-gray-200 pb-0">
        @php
            $tabs = [
                'connected' => ['label' => 'Connected',     'icon' => 'ph-handshake'],
                'pending'   => ['label' => 'Sent Requests', 'icon' => 'ph-paper-plane-tilt'],
                'received'  => ['label' => 'Received',      'icon' => 'ph-tray-arrow-down'],
            ];
        @endphp
        @foreach ($tabs as $key => $meta)
            <button
                wire:click="changeTab('{{ $key }}')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-t-lg text-sm font-semibold border-b-2 transition-all duration-200
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

    <!-- Profile Grid -->
    @if ($this->profiles->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="text-6xl mb-4 text-gray-300">
                @if ($tab === 'connected')
                    <i class="ph-bold ph-heart-break"></i>
                @elseif ($tab === 'pending')
                    <i class="ph-bold ph-paper-plane-tilt"></i>
                @else
                    <i class="ph-bold ph-tray-arrow-down"></i>
                @endif
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">
                {{ $tab === 'connected' ? 'No connections yet' : ($tab === 'pending' ? 'No sent requests' : 'No received requests') }}
            </h3>
            <p class="text-gray-500 text-sm max-w-xs">
                {{ $tab === 'connected'
                    ? 'Start exploring profiles and send connection requests to meet your match.'
                    : ($tab === 'pending' ? 'You have not sent any connection requests yet.'
                    : 'No one has sent you a connection request yet.') }}
            </p>
            @if ($tab === 'connected')
                <a href="{{ route('search') }}"
                    class="mt-6 px-6 py-2.5 bg-custom-pink text-white rounded-lg font-semibold hover:bg-custom-red transition-colors duration-200">
                    Browse Profiles
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach ($this->profiles as $profile)
                <x-single-profile :profile="$profile" />
            @endforeach
        </div>
    @endif
</div>
