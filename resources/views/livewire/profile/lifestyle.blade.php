<?php

use function Livewire\Volt\{state, rules};

state(['lifestyle', 'user', 'isEditing' => false]);

rules([
    'lifestyle.diet' => 'nullable|string|max:50',
    'lifestyle.drinking' => 'nullable|string|max:50',
    'lifestyle.smoking' => 'nullable|string|max:50',
    'lifestyle.living_with' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->lifestyle->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->lifestyle->is_shown = !$this->lifestyle->is_shown;
    $this->lifestyle->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Life Style</h3>
        <div>
            @if (auth()->user()?->id == $lifestyle?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $lifestyle->is_shown ? 'Hide' : 'Show' }}
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
        'DIET' => ['field' => 'diet', 'placeholder' => 'e.g., Vegetarian, Non-Veg'],
        'DRINK' => ['field' => 'drinking', 'placeholder' => 'e.g., Occasionally, Never'],
        'SMOKE' => ['field' => 'smoking', 'placeholder' => 'e.g., No, Occasionally'],
        'LIVING WITH' => ['field' => 'living_with', 'placeholder' => 'e.g., Parents, Alone'],
    ] as $label => $data)
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    <input type="text" wire:model="lifestyle.{{ $data['field'] }}"
                        placeholder="{{ $data['placeholder'] }}" class="w-full p-2 border border-gray-200 rounded-lg">
                    @error('lifestyle.' . $data['field'])
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    <p>{{ $lifestyle->{$data['field']} ?? '-' }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
