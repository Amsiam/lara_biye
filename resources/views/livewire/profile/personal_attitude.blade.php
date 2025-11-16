<?php

use function Livewire\Volt\{state, rules};

state(['personal', 'user', 'isEditing' => false]);

rules([
    'personal.affection' => 'nullable|string|max:100',
    'personal.humor' => 'nullable|string|max:100',
    'personal.political_view' => 'nullable|string|max:100',
    'personal.religious_service' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->personal->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->personal->is_shown = !$this->personal->is_shown;
    $this->personal->save();
};

?>

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Personal Attitude And Behavior</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == $personal?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    {{ $personal->is_shown ? '👁️ Hide' : '👁️‍🗨️ Show' }}
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
        'AFFECTION' => ['field' => 'affection', 'placeholder' => 'e.g., Loving and caring'],
        'HUMOR' => ['field' => 'humor', 'placeholder' => 'e.g., Good sense of humor'],
        'POLITICAL VIEW' => ['field' => 'political_view', 'placeholder' => 'e.g., Moderate'],
        'RELIGIOUS SERVICE' => ['field' => 'religious_service', 'placeholder' => 'e.g., Attends weekly'],
    ] as $label => $data)
            <div>
                <p class="text-gray-600 text-xs font-semibold uppercase mb-2">{{ $label }}</p>
                @if ($isEditing)
                    <input type="text" wire:model="personal.{{ $data['field'] }}"
                        class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" placeholder="{{ $data['placeholder'] }}">
                    @error('personal.' . $data['field'])
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                @else
                    <p class="text-gray-900 font-medium">{{ $personal->{$data['field']} ?? '-' }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
