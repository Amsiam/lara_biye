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
        'CASTE / SECT' => ['field' => 'caste', 'example' => 'Sunni'],
        'SUB-CASTE' => ['field' => 'sub_caste', 'example' => 'Barelvi'],
        'ETHNICITY' => ['field' => 'ethnicity', 'example' => 'South Asian'],
        'PERSONAL VALUE' => ['field' => 'personal_value', 'example' => 'Religious'],
        'FAMILY VALUE' => ['field' => 'family_value', 'example' => 'Conservative'],
        'COMMUNITY VALUE' => ['field' => 'community_value', 'example' => 'Community-Oriented'],
        'FAMILY STATUS' => ['field' => 'family_status', 'example' => 'Middle Class'],
        'MANGLIK' => ['field' => 'manglik', 'example' => 'Yes / No'],
    ] as $label => $data)
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    @if ($data['field'] === 'manglik')
                        <select wire:model="spiritualSocial.{{ $data['field'] }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                            <option value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    @else
                        <input type="text" wire:model="spiritualSocial.{{ $data['field'] }}"
                            placeholder="e.g., {{ $data['example'] }}"
                            class="w-full p-2 border border-gray-200 rounded-lg" />
                        <p class="text-gray-400 text-xs italic mt-1">Example: {{ $data['example'] }}</p>
                    @endif

                    @error('spiritualSocial.' . $data['field'])
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    @if ($data['field'] === 'manglik' && !is_null($spiritualSocial->manglik))
                        <p>{{ $spiritualSocial->manglik ? 'Yes' : 'No' }}</p>
                    @else
                        <p>{{ $spiritualSocial->{$data['field']} ?? '-' }}</p>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
