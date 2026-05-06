<?php

use function Livewire\Volt\{state, rules};

state(['lang', 'user', 'isEditing' => false]);

rules([
    'lang.mother_tongue' => 'nullable|string|max:100',
    'lang.language' => 'nullable|string|max:100',
    'lang.speak' => 'nullable|string|max:100',
    'lang.read' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->lang->save();
    $this->dispatch('profile-section-saved');

    $this->isEditing = false;
};

$toggle = function () {
    $this->lang->is_shown = !$this->lang->is_shown;
    $this->lang->save();
};

?>

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-3 sm:p-4 border-b">
        <h3 class="font-bold text-white text-sm sm:text-base md:text-lg truncate mr-2">Language</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == $lang?->user_id)
                <button wire:click="toggle"
                    class="text-white bg-custom-pink hover:bg-pink-600 px-2 sm:px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-1 sm:gap-2">
                    @if ($lang->is_shown)
                        <i class="ph-bold ph-eye-slash"></i>
                        <span class="hidden sm:inline">Hide</span>
                    @else
                        <i class="ph-bold ph-eye"></i>
                        <span class="hidden sm:inline">Show</span>
                    @endif
                </button>
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
    </div>
    <div class="p-6 bg-white grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ([
    'MOTHER TONGUE' => ['field' => 'mother_tongue', 'placeholder' => 'e.g., Bengali'],
    'LANGUAGE' => ['field' => 'language', 'placeholder' => 'e.g., English, Hindi'],
    'SPEAK' => ['field' => 'speak', 'placeholder' => 'e.g., Fluent'],
    'READ' => ['field' => 'read', 'placeholder' => 'e.g., Moderate'],
] as $label => $data)
                        <div>
                            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">{{ $label }}</p>
                            @if ($isEditing)
                                <input type="text" wire:model="lang.{{ $data['field'] }}"
                                    placeholder="{{ $data['placeholder'] }}" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                                @error('lang.' . $data['field'])
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            @else
                                <p class="text-gray-900 font-medium">{{ $lang->{$data['field']} ?? '-' }}</p>
                            @endif
                        </div>
        @endforeach
    </div>
</div>
