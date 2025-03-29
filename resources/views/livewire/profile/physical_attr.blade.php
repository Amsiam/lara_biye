<?php

use function Livewire\Volt\{state, rules};

state(['physical', 'user', 'isEditing' => false]);

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

    $this->physical->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->physical->is_shown = !$this->physical->is_shown;
    $this->physical->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center  bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Physical Attributes</h3>
        <div>
            @if (auth()->user()?->id == $physical_attr?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $physical_attr->is_shown ? 'Hide' : 'Show' }}
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
            <p class="text-gray-600 text-sm">HEIGHT</p>
            <p>0.00 Feet</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">WEIGHT</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">EYE COLOR</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">HAIR COLOR</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">COMPLEXION</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">BLOOD GROUP</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">BODY TYPE</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">BODY ART</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">ANY DISABILITY</p>
            <p>-</p>
        </div>
    </div>
</div>
