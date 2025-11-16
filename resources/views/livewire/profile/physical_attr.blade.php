<?php

use function Livewire\Volt\{state, rules};

state(['physical', 'user', 'isEditing' => false]);

rules([
    'physical.eye_color' => 'nullable|string|max:50',
    'physical.hair_color' => 'nullable|string|max:50',
    'physical.complexion' => 'nullable|string|max:50',
    'physical.body_type' => 'nullable|string|max:50',
    'physical.body_art' => 'nullable|string|max:100',
    'physical.any_disability' => 'nullable|boolean',
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

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Physical Attributes</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == $physical?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    {{ $physical->is_shown ? '👁️ Hide' : '👁️‍🗨️ Show' }}
                </button>
                @if (!$isEditing)
                    <button wire:click="enableEditing" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">✎ Edit</button>
                @else
                    <button wire:click="save" class="text-white bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">✓ Save</button>
                @endif
            @endif
        </div>
    </div>
    <div class="p-6 bg-white grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ([
        'EYE COLOR' => ['field' => 'eye_color', 'placeholder' => 'e.g., Brown'],
        'HAIR COLOR' => ['field' => 'hair_color', 'placeholder' => 'e.g., Black'],
        'COMPLEXION' => ['field' => 'complexion', 'placeholder' => 'e.g., Fair'],
        'BODY TYPE' => ['field' => 'body_type', 'placeholder' => 'e.g., Athletic'],
        'BODY ART' => ['field' => 'body_art', 'placeholder' => 'e.g., Tattoo on arm'],
        'ANY DISABILITY' => ['field' => 'any_disability', 'placeholder' => 'Select Yes or No'],
    ] as $label => $data)
            <div>
                <p class="text-gray-600 text-xs font-semibold uppercase mb-2">{{ $label }}</p>
                @if ($isEditing)
                    @if ($data['field'] == 'any_disability')
                        <x-select-input
                            wireModel="physical.{{ $data['field'] }}"
                            placeholder="Select Yes or No"
                            :options="[1 => 'Yes', 0 => 'No']"
                        />
                    @else
                        <input type="text" wire:model="physical.{{ $data['field'] }}"
                            class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all"
                            placeholder="{{ $data['placeholder'] }}">
                    @endif
                    @error('physical.' . $data['field'])
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                @else
                    @if ($data['field'] == 'any_disability' && !is_null($physical->any_disability))
                        <p class="text-gray-900 font-medium">{{ $physical->any_disability ? 'Yes' : 'No' }}</p>
                    @else
                        <p class="text-gray-900 font-medium">{{ $physical->{$data['field']} ?? '-' }}</p>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
