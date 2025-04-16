<?php

use function Livewire\Volt\{state, rules};

state(['physical', 'user', 'isEditing' => false]);

rules([
    'physical.height' => 'nullable|numeric|min:50|max:250',
    'physical.weight' => 'nullable|numeric|min:30|max:300',
    'physical.eye_color' => 'nullable|string|max:50',
    'physical.hair_color' => 'nullable|string|max:50',
    'physical.complexion' => 'nullable|string|max:50',
    'physical.blood_group' => 'nullable|string|max:50',
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

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
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
        @foreach ([
        'HEIGHT' => 'height',
        'WEIGHT' => 'weight',
        'EYE COLOR' => 'eye_color',
        'HAIR COLOR' => 'hair_color',
        'COMPLEXION' => 'complexion',
        'BLOOD GROUP' => 'blood_group',
        'BODY TYPE' => 'body_type',
        'BODY ART' => 'body_art',
        'ANY DISABILITY' => 'any_disability',
    ] as $label => $field)
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    @if ($field == 'any_disability')
                        <select wire:model="physical.{{ $field }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                            <option value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    @elseif ($field == 'height' || $field == 'weight')
                        <input type="number" wire:model="physical.{{ $field }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                    @else
                        <input type="text" wire:model="physical.{{ $field }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                    @endif
                    @error('physical.' . $field)
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    @if ($field == 'any_disability' && !is_null($physical->any_disability))
                        <p>{{ $physical->any_disability ? 'Yes' : 'No' }}</p>
                    @elseif ($field == 'height' && $physical->height)
                        <p>{{ number_format($physical->height, 2) }} cm</p>
                    @elseif ($field == 'weight' && $physical->weight)
                        <p>{{ number_format($physical->weight, 2) }} kg</p>
                    @else
                        <p>{{ $physical->{$field} ?? '-' }}</p>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
