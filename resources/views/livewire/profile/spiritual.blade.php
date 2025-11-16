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

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Spiritual And Social Background</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == $spiritualSocial?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    {{ $spiritualSocial->is_shown ? '👁️ Hide' : '👁️‍🗨️ Show' }}
                </button>
                @if (!$isEditing)
                    <button wire:click="enableEditing" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">✎ Edit</button>
                @else
                    <button wire:click="save" class="text-white bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">✓ Save</button>
                @endif
            @endif
        </div>
    </div>
    <div class="p-6 bg-white grid grid-cols-1 md:grid-cols-2 gap-6">
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
                <p class="text-gray-600 text-xs font-semibold uppercase mb-2">{{ $label }}</p>
                @if ($isEditing)
                    @if ($data['field'] === 'manglik')
                        <x-select-input
                            wireModel="spiritualSocial.{{ $data['field'] }}"
                            placeholder="Select Yes or No"
                            :options="[1 => 'Yes', 0 => 'No']"
                        />
                    @else
                        <input type="text" wire:model="spiritualSocial.{{ $data['field'] }}"
                            placeholder="e.g., {{ $data['example'] }}"
                            class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" />
                    @endif

                    @error('spiritualSocial.' . $data['field'])
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                @else
                    @if ($data['field'] === 'manglik' && $spiritualSocial->manglik !== null)
                        <p class="text-gray-900 font-medium">{{ $spiritualSocial->manglik ? 'Yes' : 'No' }}</p>
                    @else
                        <p class="text-gray-900 font-medium">{{ $spiritualSocial->{$data['field']} ?? '-' }}</p>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
