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

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Personal Attitude And Behavior</h3>
        <div>
            @if (auth()->user()?->id == $personal?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $personal->is_shown ? 'Hide' : 'Show' }}
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
        'AFFECTION' => 'affection',
        'HUMOR' => 'humor',
        'POLITICAL VIEW' => 'political_view',
        'RELIGIOUS SERVICE' => 'religious_service',
    ] as $label => $field)
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    <input type="text" wire:model="personal.{{ $field }}"
                        class="w-full p-2 border border-gray-200 rounded-lg">
                    @error('personal.' . $field)
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    <p>{{ $personal->{$field} ?? '-' }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
