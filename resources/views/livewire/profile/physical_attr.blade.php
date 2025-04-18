<?php

use function Livewire\Volt\{state, rules};

state(['physical', 'body', 'isEditing' => false]);

rules([
    'body.height' => 'required|numeric',
    'body.weight' => 'required|numeric',
    'body.blood_group' => 'required|string',
    'physical.eye_color' => 'nullable|string|max:100',
    'physical.hair_color' => 'nullable|string|max:100',
    'physical.complexion' => 'nullable|string|max:100',
    'physical.body_type' => 'nullable|string|max:100',
    'physical.body_art' => 'nullable|string|max:100',
    'physical.any_disability' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->body->save();
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
            @if (auth()->user()?->id == $physical?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $physical->is_shown ? 'Hide' : 'Show' }}
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
            @if ($isEditing)
                <input wire:model="body.height" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('body.height')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $body->height }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">WEIGHT</p>
            @if ($isEditing)
                <input wire:model="body.weight" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('body.weight')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $body->weight }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">EYE COLOR</p>
            @if ($isEditing)
                <input wire:model="physical.eye_color" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('physical.eye_color')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $physical->eye_color }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">HAIR COLOR</p>
            @if ($isEditing)
                <input wire:model="physical.hair_color" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('physical.hair_color')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $physical->hair_color }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">COMPLEXION</p>
            @if ($isEditing)
                <input wire:model="physical.complexion" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('physical.complexion')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $physical->complexion }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">BLOOD GROUP</p>
            @if ($isEditing)
                <input wire:model="body.blood_group" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('body.blood_group')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $body->blood_group }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">BODY TYPE</p>
            @if ($isEditing)
                <input wire:model="physical.body_type" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('physical.body_type')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $physical->body_type }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">BODY ART</p>
            @if ($isEditing)
                <input wire:model="physical.body_art" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('physical.body_art')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $physical->body_art }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">ANY DISABILITY</p>
            @if ($isEditing)
                <input wire:model="physical.any_disability" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('physical.any_disability')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $physical->any_disability }}
                </p>
            @endif
        </div>
    </div>
</div>
