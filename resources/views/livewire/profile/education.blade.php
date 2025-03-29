<?php

use function Livewire\Volt\{state, rules};

state(['education', 'user', 'isEditing' => false]);

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

    $this->education->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->education->is_shown = !$this->education->is_shown;
    $this->education->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center  bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Education And Career</h3>
        <div>
            @if (auth()->user()?->id == $education?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $education->is_shown ? 'Hide' : 'Show' }}
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
            <p class="text-gray-600 text-sm">HIGHEST EDUCATION</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">OCCUPATION</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">ANNUAL INCOME</p>
            <p>-</p>
        </div>
    </div>
</div>
