<?php

use function Livewire\Volt\{state, rules};

state(['astronomicInfo', 'user', 'isEditing' => false]);

rules([
    'astronomicInfo.sun_sign' => 'nullable|string|max:50',
    'astronomicInfo.moon_sign' => 'nullable|string|max:50',
    'astronomicInfo.city_of_birth' => 'nullable|string|max:100',
    'astronomicInfo.time_of_birth' => 'nullable|string|max:50',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->astronomicInfo->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->astronomicInfo->is_shown = !$this->astronomicInfo->is_shown;
    $this->astronomicInfo->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Astronomic Information</h3>
        <div>
            @if (auth()->user()?->id == $astronomicInfo?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $astronomicInfo->is_shown ? 'Hide' : 'Show' }}
                </button>
                @if (!$isEditing)
                    <button wire:click="enableEditing" class="text-white bg-custom-pink px-2 rounded">✎</button>
                @else
                    <button wire:click="save" class="text-white bg-custom-pink px-2 rounded">Save</button>
                @endif
            @endif
        </div>
    </div>
    <div class="p-4 grid grid-cols-2 gap-4">
        @foreach ([
        'SUN SIGN' => ['field' => 'sun_sign', 'placeholder' => 'e.g., Aries'],
        'MOON SIGN' => ['field' => 'moon_sign', 'placeholder' => 'e.g., Cancer'],
        'CITY OF BIRTH' => ['field' => 'city_of_birth', 'placeholder' => 'e.g., Dhaka'],
        'TIME OF BIRTH' => ['field' => 'time_of_birth', 'placeholder' => 'e.g., 03:45 AM'],
    ] as $label => $data)
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    <input type="text" placeholder="{{ $data['placeholder'] }}"
                        wire:model="astronomicInfo.{{ $data['field'] }}"
                        class="w-full p-2 border border-gray-200 rounded-lg">
                    @error('astronomicInfo.' . $data['field'])
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    <p>{{ $astronomicInfo->{$data['field']} ?? '-' }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
