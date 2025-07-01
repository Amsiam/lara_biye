<?php
use function Livewire\Volt\{mount, state, computed};

state(['state' => 'connected']);

$profiles = computed(function () {
    $user = auth()->user();

    if ($this->state === 'connected') {
        return $user
            ->connectedUsers()
            ->where('user_id', auth()->user()->id)
            ->where('status', 'ACCEPTED')
            ->with('basicInfo')
            ->get();
    } elseif ($this->state === 'pending') {
        return $user
            ->connectedUsers()
            ->where('user_id', auth()->user()->id)
            ->where('status', 'PENDING')
            ->with('basicInfo')
            ->get();
    } elseif ($this->state === 'recieved') {
        return $user
            ->rConnectedUsers()
            ->where('connected_user_id', auth()->user()->id)
            ->where('status', 'PENDING')
            ->with('basicInfo')
            ->get();
    }

    return collect();
});

$changeState = function ($newState) {
    $this->state = $newState;
};

?>


<div class="max-w-6xl mx-auto mt-20">
    <div class="flex justify-center space-x-4">
        <button class="px-4 py-2 rounded {{ $state === 'connected' ? 'bg-custom-pink text-white' : 'bg-gray-200 ' }}"
            wire:click="changeState('connected')">Connected Persons</button>
        <button class="px-4 py-2 rounded {{ $state === 'pending' ? 'bg-custom-pink text-white' : 'bg-gray-200 ' }}"
            wire:click="changeState('pending')">Pending Requests</button>
        <button class="px-4 py-2 rounded {{ $state === 'recieved' ? 'bg-custom-pink text-white' : 'bg-gray-200 ' }}"
            wire:click="changeState('recieved')">Recieved Requests</button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @foreach ($this->profiles as $profile)
            <x-single-profile :profile="$profile" />
        @endforeach
    </div>
</div>
