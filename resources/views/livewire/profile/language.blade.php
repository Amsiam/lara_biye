<?php

use function Livewire\Volt\{state, rules};

state(['lang', 'user', 'isEditing' => false]);

rules([
    'address.country' => 'required|string|max:100',
    'address.division' => 'nullable|string|max:100',
    'address.district' => 'nullable|string|max:100',
    'address.upazilla' => 'nullable|string|max:100',
    'address.union' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->lang->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->lang->is_shown = !$this->lang->is_shown;
    $this->lang->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center  bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Language</h3>
        <div>
            @if (auth()->user()?->id == $lang?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $lang->is_shown ? 'Hide' : 'Show' }}
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
            <p class="text-gray-600 text-sm">MOTHER TONGUE</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">LANGUAGE</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">SPEAK</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">READ</p>
            <p>-</p>
        </div>
    </div>
</div>
