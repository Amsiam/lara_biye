<?php

use function Livewire\Volt\{state, rules};

state(['address', 'user', 'isEditing' => false]);

rules([
    'address.country' => 'required|string|max:100',
    'address.division' => 'nullable|string|max:100',
    'address.district' => 'nullable|string|max:100',
    'address.upazilla' => 'nullable|string|max:100',
    'address.union' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->address->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->address->is_shown = !$this->address->is_shown;
    $this->address->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Present Address</h3>
        <div>
            @if (auth()->user()?->id == $address?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $address->is_shown ? 'Hide' : 'Show' }}
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
        <div>
            <p class="text-gray-600 text-sm">COUNTRY</p>
            @if ($isEditing)
                <input wire:model="address.country" class="w-full p-2 border border-gray-200 rounded-lg"
                    placeholder="e.g., Bangladesh" />
                @error('address.country')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-xs italic mt-1">Example: Bangladesh</p>
            @else
                <p>{{ $address->country ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">DIVISION</p>
            @if ($isEditing)
                <input wire:model="address.division" class="w-full p-2 border border-gray-200 rounded-lg"
                    placeholder="e.g., Dhaka" />
                @error('address.division')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-xs italic mt-1">Example: Dhaka</p>
            @else
                <p>{{ $address->division ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">DISTRICT</p>
            @if ($isEditing)
                <input wire:model="address.district" class="w-full p-2 border border-gray-200 rounded-lg"
                    placeholder="e.g., Gazipur" />
                @error('address.district')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-xs italic mt-1">Example: Gazipur</p>
            @else
                <p>{{ $address->district ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">UPAZILLA</p>
            @if ($isEditing)
                <input wire:model="address.upazilla" class="w-full p-2 border border-gray-200 rounded-lg"
                    placeholder="e.g., Kaliakair" />
                @error('address.upazilla')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-xs italic mt-1">Example: Kaliakair</p>
            @else
                <p>{{ $address->upazilla ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">UNION</p>
            @if ($isEditing)
                <input wire:model="address.union" class="w-full p-2 border border-gray-200 rounded-lg"
                    placeholder="e.g., Kanchanpur" />
                @error('address.union')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-xs italic mt-1">Example: Kanchanpur</p>
            @else
                <p>{{ $address->union ?? '-' }}</p>
            @endif
        </div>
    </div>
</div>
