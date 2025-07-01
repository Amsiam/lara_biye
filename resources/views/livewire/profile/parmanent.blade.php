<?php

use function Livewire\Volt\{state, rules};

state(['parmanent', 'user', 'isEditing' => false, 'view' => 'parmanent']);

rules([
    'parmanent.birth_country' => 'nullable|string|max:100',
    'parmanent.residency_country' => 'nullable|string|max:100',
    'parmanent.citizenship_country' => 'nullable|string|max:100',
    'parmanent.grow_up_country' => 'nullable|string|max:100',
    'parmanent.immigration_status' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();
    $this->{$this->view}->save();
    $this->isEditing = false;
};

$toggle = function () {
    $this->{$this->view}->is_shown = !$this->{$this->view}->is_shown;
    $this->{$this->view}->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Residency Information</h3>
        <div>
            @if (auth()->user()?->id == ${$view}?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ ${$view}->is_shown ? 'Hide' : 'Show' }}
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
        'BIRTH COUNTRY' => ['field' => 'birth_country', 'placeholder' => 'e.g., Bangladesh'],
        'RESIDENCY COUNTRY' => ['field' => 'residency_country', 'placeholder' => 'e.g., Canada'],
        'CITIZENSHIP COUNTRY' => ['field' => 'citizenship_country', 'placeholder' => 'e.g., United States'],
        'GROW UP COUNTRY' => ['field' => 'grow_up_country', 'placeholder' => 'e.g., United Kingdom'],
        'IMMIGRATION STATUS' => ['field' => 'immigration_status', 'placeholder' => 'e.g., Permanent Resident'],
    ] as $label => $data)
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    <input type="text" wire:model="{{ $view }}.{{ $data['field'] }}"
                        placeholder="{{ $data['placeholder'] }}" class="w-full p-2 border border-gray-200 rounded-lg">
                    @error($view . '.' . $data['field'])
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    <p>{{ ${$view}->{$data['field']} ?? '-' }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
