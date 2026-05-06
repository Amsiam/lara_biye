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
    $this->dispatch('profile-section-saved');

    $this->isEditing = false;
};

$toggle = function () {
    $this->address->is_shown = !$this->address->is_shown;
    $this->address->save();
};

?>

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Present Address</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == $address?->user_id)
                <button wire:click="toggle"
                    class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                    @if ($address->is_shown)
                        <i class="ph-bold ph-eye-slash"></i>
                        <span>Hide</span>
                    @else
                        <i class="ph-bold ph-eye"></i>
                        <span>Show</span>
                    @endif
                </button>
                @if (!$isEditing)
                    <button wire:click="enableEditing"
                        class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <i class="ph-bold ph-pencil-simple"></i>
                        <span>Edit</span>
                    </button>
                @else
                    <button wire:click="save"
                        class="text-white bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <i class="ph-bold ph-floppy-disk"></i>
                        <span>Save</span>
                    </button>
                @endif
            @endif
        </div>
    </div>
    <div class="p-6 bg-white grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Country</p>
            @if ($isEditing)
                <input wire:model="address.country"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white"
                    placeholder="e.g., Bangladesh" />
                @error('address.country')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">{{ $address->country ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Division</p>
            @if ($isEditing)
                <input wire:model="address.division"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white"
                    placeholder="e.g., Dhaka" />
                @error('address.division')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">{{ $address->division ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">District</p>
            @if ($isEditing)
                <input wire:model="address.district"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white"
                    placeholder="e.g., Gazipur" />
                @error('address.district')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">{{ $address->district ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Upazilla</p>
            @if ($isEditing)
                <input wire:model="address.upazilla"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white"
                    placeholder="e.g., Kaliakair" />
                @error('address.upazilla')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">{{ $address->upazilla ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Union</p>
            @if ($isEditing)
                <input wire:model="address.union"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white"
                    placeholder="e.g., Kanchanpur" />
                @error('address.union')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">{{ $address->union ?? '-' }}</p>
            @endif
        </div>
    </div>
</div>
