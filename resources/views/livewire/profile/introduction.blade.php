<?php

use function Livewire\Volt\{state, rules};

state(['bio', 'isEditing' => false]);

rules([
    'bio.bio' => 'required|string',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();
    $this->bio->save();

    $this->isEditing = false;
};

?>



<div class="mt-4  border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Introduction</h3>

        @if (auth()->user()?->id == $bio?->user_id)
            @if (!$isEditing)
                <button wire:click="enableEditing" class="text-white bg-custom-pink px-2 rounded">✎</button>
            @else
                <button wire:click="save" class="text-white bg-custom-pink px-2 rounded">Save</button>
            @endif
        @endif
    </div>
    <div class="p-4">
        @if ($isEditing)
            <textarea wire:model="bio.bio" class="w-full p-2 border border-gray-200 rounded-lg">
                {{ $bio?->bio }}
            </textarea>
            @error('bio.bio')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        @else
            <p>
                {{ $bio?->bio }}
            </p>
        @endif
    </div>
</div>
