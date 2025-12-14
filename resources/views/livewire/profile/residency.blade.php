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
                'BIRTH COUNTRY' => ['field' => 'birth_country', 'example' => 'Bangladesh'],
                'RESIDENCY COUNTRY' => ['field' => 'residency_country', 'example' => 'United States'],
                'CITIZENSHIP COUNTRY' => ['field' => 'citizenship_country', 'example' => 'Bangladesh'],
                'GROW UP COUNTRY' => ['field' => 'grow_up_country', 'example' => 'Bangladesh'],
                'IMMIGRATION STATUS' => ['field' => 'immigration_status', 'example' => 'Permanent Resident'],
            ] as $label => $data)
                        <div>
                            <p class="text-gray-600 text-sm">{{ $label }}</p>
                            @if ($isEditing)
                                <input type="text" wire:model="residencyInfo.{{ $data['field'] }}"
                                    placeholder="e.g., {{ $data['example'] }}" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                                @error('residencyInfo.' . $data['field'])
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-400 text-xs italic mt-1">Example: {{ $data['example'] }}</p>
                            @else
                                <p>{{ $residencyInfo->{$data['field']} ?? '-' }}</p>
                            @endif
                        </div>
        @endforeach
    </div>
</div>
