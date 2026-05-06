<?php

use function Livewire\Volt\{state, rules};

state(['partner', 'user', 'isEditing' => false]);

rules([
    'partner.general_requirement' => 'nullable|string|max:255',
    'partner.age' => 'nullable|integer|min:18|max:100',
    'partner.height_from' => 'nullable|numeric',
    'partner.height_to' => 'nullable|numeric',
    'partner.weight_from' => 'nullable|numeric',
    'partner.weight_to' => 'nullable|numeric',
    'partner.marital_status' => 'nullable|string|max:50',
    'partner.with_children_acceptables' => 'nullable|string|max:50',
    'partner.country_of_residence' => 'nullable|string|max:100',
    'partner.religion' => 'nullable|string|max:100',
    'partner.caste_sect' => 'nullable|string|max:100',
    'partner.sub_caste' => 'nullable|string|max:100',
    'partner.education' => 'nullable|string|max:150',
    'partner.profession' => 'nullable|string|max:150',
    'partner.drinking_habits' => 'nullable|string|max:50',
    'partner.smoking_habits' => 'nullable|string|max:50',
    'partner.diet' => 'nullable|string|max:50',
    'partner.body_type' => 'nullable|string|max:50',
    'partner.personal_value' => 'nullable|string|max:100',
    'partner.manglik' => 'nullable|boolean',
    'partner.any_disability' => 'nullable|string|max:100',
    'partner.mother_tongue' => 'nullable|string|max:100',
    'partner.family_value' => 'nullable|string|max:100',
    'partner.prefered_country' => 'nullable|string|max:100',
    'partner.prefered_state' => 'nullable|string|max:100',
    'partner.prefered_status' => 'nullable|string|max:100',
    'partner.complexion' => 'nullable|string|max:50',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();
    foreach (['height_from','manglik' ,'height_to', 'weight_from', 'weight_to', 'age'] as $field) {
        if ($this->partner->{$field} === '') {
            $this->partner->{$field} = null;
        }
    }
    $this->partner->save();
    $this->dispatch('profile-section-saved');

    $this->isEditing = false;
};

$toggle = function () {
    $this->partner->is_shown = !$this->partner->is_shown;
    $this->partner->save();
};

?>

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-3 sm:p-4 border-b">
        <h3 class="font-bold text-white text-sm sm:text-base md:text-lg truncate mr-2">Partner Expectation</h3>
        <div>
            @if (auth()->user()?->id == $partner?->user_id)
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
    'GENERAL REQUIREMENT' => ['field' => 'general_requirement', 'placeholder' => 'Describe general expectations...'],
    'AGE' => ['field' => 'age', 'placeholder' => 'e.g., 25'],
    'HEIGHT FROM(cm)' => ['field' => 'height_from', 'placeholder' => 'e.g., 150'],
    'HEIGHT TO(cm)' => ['field' => 'height_to', 'placeholder' => 'e.g., 180'],
    'WEIGHT FROM(kg)' => ['field' => 'weight_from', 'placeholder' => 'e.g., 50'],
    'WEIGHT TO(kg)' => ['field' => 'weight_to', 'placeholder' => 'e.g., 75'],
    'MARITAL STATUS' => ['field' => 'marital_status', 'placeholder' => 'e.g., Never Married'],
    'WITH CHILDREN ACCEPTABLES' => ['field' => 'with_children_acceptables', 'placeholder' => 'Yes or No'],
    'COUNTRY OF RESIDENCE' => ['field' => 'country_of_residence', 'placeholder' => 'e.g., Bangladesh'],
    'RELIGION' => ['field' => 'religion', 'placeholder' => 'e.g., Islam'],
    'CASTE / SECT' => ['field' => 'caste_sect', 'placeholder' => 'e.g., Sunni'],
    'SUB CASTE' => ['field' => 'sub_caste', 'placeholder' => 'e.g., Hanafi'],
    'EDUCATION' => ['field' => 'education', 'placeholder' => 'e.g., Bachelor\'s Degree'],
    'PROFESSION' => ['field' => 'profession', 'placeholder' => 'e.g., Engineer'],
    'DRINKING HABITS' => ['field' => 'drinking_habits', 'placeholder' => 'e.g., Occasionally'],
    'SMOKING HABITS' => ['field' => 'smoking_habits', 'placeholder' => 'e.g., No'],
    'DIET' => ['field' => 'diet', 'placeholder' => 'e.g., Vegetarian'],
    'BODY TYPE' => ['field' => 'body_type', 'placeholder' => 'e.g., Athletic'],
    'PERSONAL VALUE' => ['field' => 'personal_value', 'placeholder' => 'e.g., Family Oriented'],
    'MANGLIK' => ['field' => 'manglik'],
    'ANY DISABILITY' => ['field' => 'any_disability', 'placeholder' => 'Specify if any'],
    'MOTHER TONGUE' => ['field' => 'mother_tongue', 'placeholder' => 'e.g., Bengali'],
    'FAMILY VALUE' => ['field' => 'family_value', 'placeholder' => 'e.g., Traditional'],
    'PREFERED COUNTRY' => ['field' => 'prefered_country', 'placeholder' => 'e.g., Bangladesh'],
    'PREFERED STATE' => ['field' => 'prefered_state', 'placeholder' => 'e.g., Dhaka'],
    'PREFERED STATUS' => ['field' => 'prefered_status', 'placeholder' => 'e.g., Citizen'],
    'COMPLEXION' => ['field' => 'complexion', 'placeholder' => 'e.g., Fair'],
] as $label => $data)
                        <div>
                            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">{{ $label }}</p>
                            @if ($isEditing)
                                @if ($data['field'] == 'manglik')
                                    <x-select-input
                                        wireModel="partner.manglik"
                                        placeholder="Select Yes or No"
                                        :options="[1 => 'Yes', 0 => 'No']"
                                    />
                                @elseif ($data['field'] == 'any_disability')
                                    <input type="text" wire:model="partner.any_disability"
                                        placeholder="{{ $data['placeholder'] }}"
                                        class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                                @elseif (in_array($data['field'], ['age', 'height_from', 'height_to', 'weight_from', 'weight_to']))
                                            <input type="number" step="0.01" wire:model="partner.{{ $data['field'] }}"
                                        placeholder="{{ $data['placeholder'] }}"
                                        class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                                    @elseif ($data['field'] == 'general_requirement')
                                    <textarea wire:model="partner.general_requirement" placeholder="{{ $data['placeholder'] }}" rows="4"
                                        class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white"></textarea>
                                @else
                                    <input type="text" wire:model="partner.{{ $data['field'] }}"
                                        placeholder="{{ $data['placeholder'] }}"
                                        class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                                @endif
                                @error('partner.' . $data['field'])
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            @else
                                @if ($data['field'] == 'manglik')
                                    <p class="text-gray-900 font-medium">{{ $partner->manglik === null ? '-' : ($partner->manglik ? 'Yes' : 'No') }}</p>
                                @elseif ($data['field'] == 'any_disability')
                                    <p class="text-gray-900 font-medium">{{ $partner->any_disability ?? '-' }}</p>
                                @elseif ($data['field'] == 'age' && $partner->age)
                                    <p class="text-gray-900 font-medium">{{ $partner->age . ' years' }}</p>
                                @else
                                    <p class="text-gray-900 font-medium">{{ $partner->{$data['field']} ?? '-' }}</p>
                                @endif
                            @endif
                        </div>
        @endforeach
    </div>
</div>
