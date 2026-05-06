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
    $this->dispatch('profile-section-saved');

    $this->isEditing = false;
};

?>



<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-3 sm:p-4 border-b">
        <h3 class="font-bold text-white text-sm sm:text-base md:text-lg truncate mr-2">Introduction</h3>

        @if (auth()->user()?->id == $bio?->user_id)
            @if (!$isEditing)
                <button wire:click="enableEditing"
                    class="text-white bg-custom-pink hover:bg-pink-600 px-2 sm:px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-1 sm:gap-2">
                    <i class="ph-bold ph-pencil-simple"></i>
                    <span class="hidden sm:inline">Edit</span>
                </button>
            @else
                <button wire:click="save"
                    class="text-white bg-green-500 hover:bg-green-600 px-2 sm:px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-1 sm:gap-2">
                    <i class="ph-bold ph-floppy-disk"></i>
                    <span class="hidden sm:inline">Save</span>
                </button>
            @endif
        @endif
    </div>
    <div class="p-6 bg-white">
        @if ($isEditing)
            <textarea wire:model="bio.bio" rows="5"
                class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white"
                placeholder="Write a brief introduction about yourself...">{{ $bio?->bio }}</textarea>
            @error('bio.bio')
                <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                    <i class="ph-bold ph-warning-circle"></i>
                    {{ $message }}
                </p>
            @enderror
        @else
            <p class="text-gray-700 leading-relaxed">
                {{ $bio?->bio ?? 'No introduction provided yet.' }}
            </p>
        @endif
    </div>
</div>
