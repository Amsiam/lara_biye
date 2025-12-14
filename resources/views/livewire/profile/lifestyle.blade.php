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

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Life Style</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == $lifestyle?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    {{ $lifestyle->is_shown ? '👁️ Hide' : '👁️‍🗨️ Show' }}
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
                'DIET' => ['field' => 'diet', 'placeholder' => 'e.g., Vegetarian, Non-Veg'],
                'DRINK' => ['field' => 'drinking', 'placeholder' => 'e.g., Occasionally, Never'],
                'SMOKE' => ['field' => 'smoking', 'placeholder' => 'e.g., No, Occasionally'],
                'LIVING WITH' => ['field' => 'living_with', 'placeholder' => 'e.g., Parents, Alone'],
            ] as $label => $data)
                        <div>
                            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">{{ $label }}</p>
                            @if ($isEditing)
                                <input type="text" wire:model="lifestyle.{{ $data['field'] }}"
                                    placeholder="{{ $data['placeholder'] }}" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                                @error('lifestyle.' . $data['field'])
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            @else
                                <p class="text-gray-900 font-medium">{{ $lifestyle->{$data['field']} ?? '-' }}</p>
                            @endif
                        </div>
        @endforeach
    </div>
</div>
