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
    $this->dispatch('profile-section-saved');
    $this->isEditing = false;
};

$toggle = function () {
    $this->{$this->view}->is_shown = !$this->{$this->view}->is_shown;
    $this->{$this->view}->save();
};

?>

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Residency Information</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == ${$view}?->user_id)
                <button wire:click="toggle"
                    class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                    @if (${$view}->is_shown)
                        <i class="ph-bold ph-eye-slash"></i>
                        <span>Hide</span>
                    @else
                        <i class="ph-bold ph-eye"></i>
                        <span>Show</span>
                    @endif
                </button>
                @if (!$isEditing)
                    <button wire:click="enableEditing"
                        class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <i class="ph-bold ph-pencil-simple"></i>
                        <span>Edit</span>
                    </button>
                @else
                    <button wire:click="save"
                        class="text-white bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <i class="ph-bold ph-floppy-disk"></i>
                        <span>Save</span>
                    </button>
                @endif
            @endif
        </div>
    </div>
    <div class="p-6 bg-white grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ([
    'BIRTH COUNTRY' => ['field' => 'birth_country', 'placeholder' => 'e.g., Bangladesh'],
    'RESIDENCY COUNTRY' => ['field' => 'residency_country', 'placeholder' => 'e.g., Canada'],
    'CITIZENSHIP COUNTRY' => ['field' => 'citizenship_country', 'placeholder' => 'e.g., United States'],
    'GROW UP COUNTRY' => ['field' => 'grow_up_country', 'placeholder' => 'e.g., United Kingdom'],
    'IMMIGRATION STATUS' => ['field' => 'immigration_status', 'placeholder' => 'e.g., Permanent Resident'],
] as $label => $data)
                        <div>
                            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">{{ $label }}</p>
                            @if ($isEditing)
                                <input type="text" wire:model="{{ $view }}.{{ $data['field'] }}"
                                    placeholder="{{ $data['placeholder'] }}" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                                @error($view . '.' . $data['field'])
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            @else
                                <p class="text-gray-900 font-medium">{{ ${$view}->{$data['field']} ?? '-' }}</p>
                            @endif
                        </div>
        @endforeach
    </div>
</div>
