<?php

use function Livewire\Volt\{state, rules};

state(['spiritualSocial', 'user', 'isEditing' => false]);

rules([
    'spiritualSocial.caste' => 'nullable|string|max:100',
    'spiritualSocial.sub_caste' => 'nullable|string|max:100',
    'spiritualSocial.ethnicity' => 'nullable|string|max:100',
    'spiritualSocial.personal_value' => 'nullable|string|max:100',
    'spiritualSocial.family_value' => 'nullable|string|max:100',
    'spiritualSocial.community_value' => 'nullable|string|max:100',
    'spiritualSocial.family_status' => 'nullable|string|max:100',
    'spiritualSocial.manglik' => 'nullable|boolean',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->spiritualSocial->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->spiritualSocial->is_shown = !$this->spiritualSocial->is_shown;
    $this->spiritualSocial->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Spiritual And Social Background</h3>
        <div>
            @if (auth()->user()?->id == $spiritualSocial?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $spiritualSocial->is_shown ? 'Hide' : 'Show' }}
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
        'CASTE / SECT' => 'caste',
        'SUB-CASTE' => 'sub_caste',
        'ETHNICITY' => 'ethnicity',
        'PERSONAL VALUE' => 'personal_value',
        'FAMILY VALUE' => 'family_value',
        'COMMUNITY VALUE' => 'community_value',
        'FAMILY STATUS' => 'family_status',
        'MANGLIK' => 'manglik',
    ] as $label => $field)
            @if ($field == 'manglik' && $user?->basicInfo?->religion != 'HINDU')
                @continue
            @endif
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    @if ($field == 'manglik')
                        <select wire:model="spiritualSocial.{{ $field }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                            <option value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    @else
                        <input type="text" wire:model="spiritualSocial.{{ $field }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                    @endif
                    @error('spiritualSocial.' . $field)
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    @if ($field == 'manglik' && !is_null($spiritualSocial->manglik))
                        <p>{{ $spiritualSocial->manglik ? 'Yes' : 'No' }}</p>
                    @else
                        <p>{{ $spiritualSocial->{$field} ?? '-' }}</p>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
