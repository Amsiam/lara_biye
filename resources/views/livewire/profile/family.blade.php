<?php

use function Livewire\Volt\{state, rules};

state(['family', 'user', 'isEditing' => false]);

rules([
    'family.father' => 'nullable|string|max:100',
    'family.mother' => 'nullable|string|max:100',
    'family.brother' => 'nullable|string|max:100',
    'family.sister' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->family->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->family->is_shown = !$this->family->is_shown;
    $this->family->save();
};

?>
<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Family Information</h3>
        <div>
            @if (auth()->user()?->id == $family?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $family->is_shown ? 'Hide' : 'Show' }}
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
            <p class="text-gray-600 text-sm">FATHER</p>
            @if ($isEditing)
                <input type="text" wire:model="family.father" class="w-full p-2 border border-gray-200 rounded-lg">
                @error('family.father')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @else
                <p>{{ $family->father ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">MOTHER</p>
            @if ($isEditing)
                <input type="text" wire:model="family.mother" class="w-full p-2 border border-gray-200 rounded-lg">
                @error('family.mother')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @else
                <p>{{ $family->mother ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">BROTHER</p>
            @if ($isEditing)
                <input type="text" wire:model="family.brother" class="w-full p-2 border border-gray-200 rounded-lg">
                @error('family.brother')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @else
                <p>{{ $family->brother ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">Sister</p>
            @if ($isEditing)
                <input type="text" wire:model="family.sister" class="w-full p-2 border border-gray-200 rounded-lg">
                @error('family.sister')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @else
                <p>{{ $family->sister ?? '-' }}</p>
            @endif
        </div>
    </div>

</div>
