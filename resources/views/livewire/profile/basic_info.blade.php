<?php

use function Livewire\Volt\{state, rules};

state(['bio', 'user', 'isEditing' => false]);

rules([
    'user.name' => 'required|string',
    'bio.dob' => 'required|date',
    'bio.gender' => 'required|string',
    'bio.marital_status' => 'required|string',
    'bio.noc' => 'required|integer',
    'bio.on_behalf' => 'required|string',
    'bio.blood_group' => 'required|string',
    'bio.height' => 'required|numeric',
    'bio.weight' => 'required|numeric',
    'bio.religion' => 'required|string',
    'bio.nid' => 'nullable|string|max:20',
    'bio.student_id' => 'nullable|string|max:20',
    'bio.university' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->user->save();
    $this->bio->save();

    $this->isEditing = false;
};

?>

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Basic Information</h3>
        @if (auth()->user()?->id == $bio?->user_id)
            @if (!$isEditing)
                <button wire:click="enableEditing" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    ✎ Edit
                </button>
            @else
                <button wire:click="save" class="text-white bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    ✓ Save
                </button>
            @endif
        @endif
    </div>
    <div class="p-6 bg-white grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Name</p>
            @if (auth()->user()?->id == $bio?->user_id || auth()->user()?->isConnected($bio?->user_id))
            @if ($isEditing)
                <input wire:model="user.name" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" />
                @error('user.name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $user->name }}
                </p>
            @endif
            @else
                <p class="text-gray-500 italic">Send connection to see name</p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Gender</p>
            @if ($isEditing)
                <x-select-input
                    wireModel="bio.gender"
                    placeholder="Select Gender"
                    :options="['MALE' => 'Male', 'FEMALE' => 'Female', 'UNSPECIFIED' => 'Unspecified']"
                />
                @error('bio.gender')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->gender }}
                </p>
            @endif
        </div>

        @if (auth()->user()?->id == $bio?->user_id)
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Email</p>
            <p class="text-gray-900 font-medium">
                {{ $user->email }}
            </p>
        </div>
        @endif

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Mobile</p>
            @if(auth()->user()?->id == $bio?->user_id || auth()->user()?->isConnected($bio?->user_id))
            @if ($isEditing)
                <input wire:model="user.mobile" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" />
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->mobile }}
                </p>
            @endif
            @else
                <p class="text-gray-500 italic">Send connection to see mobile</p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Age</p>
            <p class="text-gray-900 font-medium">
                {{ floor(-1 * now()->diffInYears($bio->dob)) }} years
            </p>
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Marital Status</p>
            @if ($isEditing)
                <x-select-input
                    wireModel="bio.marital_status"
                    placeholder="Select Marital Status"
                    :options="['UNMARRIED' => 'Unmarried', 'MARRIED' => 'Married', 'DIVORCED' => 'Divorced', 'WIDOWED' => 'Widowed']"
                />
                @error('bio.marital_status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->marital_status }}
                </p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Height</p>
            @if ($isEditing)
                <input wire:model="bio.height" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" placeholder="e.g., 170" />
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->height }} cm
                </p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Weight</p>
            @if ($isEditing)
                <input wire:model="bio.weight" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" placeholder="e.g., 70" />
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->weight }} kg
                </p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Blood Group</p>
            @if ($isEditing)
                <x-select-input
                    wireModel="bio.blood_group"
                    placeholder="Select Blood Group"
                    :options="['A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-']"
                />
                @error('bio.blood_group')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->blood_group }}
                </p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Religion</p>
            @if ($isEditing)
                <x-select-input
                    wireModel="bio.religion"
                    placeholder="Select Religion"
                    :options="['ISLAM' => 'Islam', 'HINDU' => 'Hindu', 'CHRISTIAN' => 'Christian', 'BUDDHIST' => 'Buddhist', 'OTHER' => 'Other']"
                />
                @error('bio.religion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->religion }}
                </p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Number of Children</p>
            @if ($isEditing)
                <input type="number" min="0" wire:model="bio.noc"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" placeholder="e.g., 0" />
                @error('bio.noc')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->noc }}
                </p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Area</p>
            @if ($isEditing)
                <input wire:model="bio.area" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" placeholder="e.g., Dhanmondi" />
                @error('bio.area')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->area }}
                </p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">On Behalf</p>
            @if ($isEditing)
                <x-select-input
                    wireModel="bio.on_behalf"
                    placeholder="Select On Behalf"
                    :options="['SELF' => 'Self', 'SON' => 'Son', 'DAUGHTER' => 'Daughter', 'BROTHER' => 'Brother', 'SISTER' => 'Sister', 'FRIEND' => 'Friend', 'RELATIVE' => 'Relative', 'OTHER' => 'Other']"
                />
                @error('bio.on_behalf')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->on_behalf }}
                </p>
            @endif
        </div>

        @if (auth()->user()?->id == $bio->user_id)
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Date of Birth</p>
            @if ($isEditing)
                <input wire:model="bio.dob" type="date" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" />
                @error('bio.dob')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->dob }}
                </p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">NID Number</p>
            @if ($isEditing)
                <input wire:model="bio.nid" type="text" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" placeholder="Enter NID number" />
                @error('bio.nid')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->nid ?? '-' }}
                </p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Student ID</p>
            @if ($isEditing)
                <input wire:model="bio.student_id" type="text" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" placeholder="Enter student ID" />
                @error('bio.student_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->student_id ?? '-' }}
                </p>
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">University</p>
            @if ($isEditing)
                <input wire:model="bio.university" type="text" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" placeholder="Enter university name" />
                @error('bio.university')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">
                    {{ $bio->university ?? '-' }}
                </p>
            @endif
        </div>
        @endif
    </div>
</div>
