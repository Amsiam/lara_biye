<?php

use function Livewire\Volt\{state, rules};

state(['residencyInfo', 'user', 'isEditing' => false]);

rules([
    'residencyInfo.birth_country' => 'nullable|string|max:100',
    'residencyInfo.residency_country' => 'nullable|string|max:100',
    'residencyInfo.citizenship_country' => 'nullable|string|max:100',
    'residencyInfo.grow_up_country' => 'nullable|string|max:100',
    'residencyInfo.immigration_status' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->residencyInfo->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->residencyInfo->is_shown = !$this->residencyInfo->is_shown;
    $this->residencyInfo->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Residency Information</h3>
        <div>
            @if (auth()->user()?->id == $residencyInfo?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $residencyInfo->is_shown ? 'Hide' : 'Show' }}
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
        'BIRTH COUNTRY' => 'birth_country',
        'RESIDENCY COUNTRY' => 'residency_country',
        'CITIZENSHIP COUNTRY' => 'citizenship_country',
        'GROW UP COUNTRY' => 'grow_up_country',
        'IMMIGRATION STATUS' => 'immigration_status',
    ] as $label => $field)
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    <input type="text" wire:model="residencyInfo.{{ $field }}"
                        class="w-full p-2 border border-gray-200 rounded-lg">
                    @error('residencyInfo.' . $field)
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    <p>{{ $residencyInfo->{$field} ?? '-' }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
