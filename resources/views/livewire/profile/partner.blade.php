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

    $this->partner->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->partner->is_shown = !$this->partner->is_shown;
    $this->partner->save();
};

?>



<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Partner Expectation</h3>
        <div>
            @if (auth()->user()?->id == $partner?->user_id)
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
        'GENERAL REQUIREMENT' => 'general_requirement',
        'AGE' => 'age',
        'HEIGHT FROM' => 'height_from',
        'HEIGHT TO' => 'height_to',
        'WEIGHT FROM' => 'weight_from',
        'WEIGHT TO' => 'weight_to',
        'MARITAL STATUS' => 'marital_status',
        'WITH CHILDREN ACCEPTABLES' => 'with_children_acceptables',
        'COUNTRY OF RESIDENCE' => 'country_of_residence',
        'RELIGION' => 'religion',
        'CASTE / SECT' => 'caste_sect',
        'SUB CASTE' => 'sub_caste',
        'EDUCATION' => 'education',
        'PROFESSION' => 'profession',
        'DRINKING HABITS' => 'drinking_habits',
        'SMOKING HABITS' => 'smoking_habits',
        'DIET' => 'diet',
        'BODY TYPE' => 'body_type',
        'PERSONAL VALUE' => 'personal_value',
        'MANGLIK' => 'manglik',
        'ANY DISABILITY' => 'any_disability',
        'MOTHER TONGUE' => 'mother_tongue',
        'FAMILY VALUE' => 'family_value',
        'PREFERED COUNTRY' => 'prefered_country',
        'PREFERED STATE' => 'prefered_state',
        'PREFERED STATUS' => 'prefered_status',
        'COMPLEXION' => 'complexion',
    ] as $label => $field)
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    @if ($field == 'manglik' || $field == 'any_disability')
                        <select wire:model="partner.{{ $field }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                            <option value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    @elseif (
                        $field == 'age' ||
                            $field == 'height_from' ||
                            $field == 'height_to' ||
                            $field == 'weight_from' ||
                            $field == 'weight_to')
                        <input type="number" wire:model="partner.{{ $field }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                    @elseif ($field == 'general_requirement')
                        <textarea wire:model="partner.{{ $field }}" class="w-full p-2 border border-gray-200 rounded-lg"></textarea>
                    @else
                        <input type="text" wire:model="partner.{{ $field }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                    @endif
                    @error('partner.' . $field)
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    @if ($field == 'manglik' && $partner->manglik)
                        <p>{{ $partner->manglik ? 'Yes' : 'No' }}</p>
                    @elseif ($field == 'any_disability' && $partner->any_disability)
                        <p>{{ $partner->any_disability ? 'Yes' : 'No' }}</p>
                    @elseif ($field == 'age' && $partner->age)
                        <p>{{ $partner->age . ' years' }}</p>
                    @else
                        <p>{{ $partner->{$field} ?? '-' }}</p>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
