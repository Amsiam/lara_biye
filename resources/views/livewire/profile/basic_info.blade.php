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
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->user->save();
    $this->bio->save();

    $this->isEditing = false;
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center  bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Basic Information</h3>
        @if (auth()->user()?->id == $bio?->user_id)
            @if (!$isEditing)
                <button wire:click="enableEditing" class="text-white bg-custom-pink px-2 rounded">✎</button>
            @else
                <button wire:click="save" class="text-white bg-custom-pink px-2 rounded">Save</button>
            @endif
        @endif
    </div>
    <div class="p-4 grid grid-cols-2 gap-4">


        <div>
            <p class="text-gray-600 text-sm">NAME</p>
            @if (auth()->user()?->id == $bio?->user_id || auth()->user()?->isConnected($bio?->user_id))
            @if ($isEditing)
                <input wire:model="user.name" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('user.name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $user->name }}
                </p>
            @endif
            @else
            Send Connection to see name.
            @endif
        </div>

        <div>
            <p class="text-gray-600 text-sm">GENDER</p>
            @if ($isEditing)
                <select wire:model="bio.gender" class="w-full p-2 border border-gray-200 rounded-lg">
                    <option>MALE</option>
                    <option>FEMALE</option>
                    <option>UNSPECIFIED</option>
                </select>
                @error('bio.gender')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $bio->gender }}
                </p>
            @endif
        </div>
        @if (auth()->user()?->id == $bio?->user_id)
        <div>
            <p class="text-gray-600 text-sm">EMAIL</p>
            <p>
                {{ $user->email }}
            </p>
        </div>
        @endif

        <div>
            <p class="text-gray-600 text-sm">MOBILE</p>
            @if(auth()->user()?->id == $bio?->user_id || auth()->user()?->isConnected($bio?->user_id))
            @if ($isEditing)
                <input wire:model="user.mobile" class="w-full p-2 border border-gray-200 rounded-lg" />
            @else
                <p>
                    {{ $bio->mobile }}
                </p>
            @endif
            @else
            Send Connection to see mobile no.
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">AGE</p>
            <p>
                {{ floor(-1 * now()->diffInYears($bio->dob)) }}
            </p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">MARITAL STATUS</p>
            @if ($isEditing)
                <select wire:model="bio.marital_status" class="w-full p-2 border border-gray-200 rounded-lg">
                    @foreach (['UNMARRIED', 'MARRIED', 'DIVORCED', 'WIDOWED'] as $matital_status)
                        <option>{{ $matital_status }}</option>
                    @endforeach
                </select>
                @error('bio.marital_status')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $bio->marital_status }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">Height(cm)</p>
            @if ($isEditing)
                <input wire:model="bio.height" class="w-full p-2 border border-gray-200 rounded-lg" />
            @else
                <p>
                    {{ $bio->height }} CM
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">Weight(kg)</p>
            @if ($isEditing)
                <input wire:model="bio.weight" class="w-full p-2 border border-gray-200 rounded-lg" />
            @else
                <p>
                    {{ $bio->weight }} KG
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">Blood Group</p>
            @if ($isEditing)
                <select wire:model="bio.blood_group" class="w-full p-2 border border-gray-200 rounded-lg">
                    @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $blood)
                        <option>{{ $blood }}</option>
                    @endforeach
                </select>
                @error('bio.blood_group')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $bio->blood_group }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">Religion</p>
            @if ($isEditing)
                <select wire:model="bio.religion" class="w-full p-2 border border-gray-200 rounded-lg">
                    @foreach (['ISLAM', 'HINDU', 'CHRISTIAN', 'BUDDHIST', 'OTHER'] as $religion)
                        <option>{{ $religion }}</option>
                    @endforeach
                </select>
                @error('bio.religion')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $bio->religion }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">NUMBER OF CHILDREN</p>
            @if ($isEditing)
                <input type="number" min="0" wire:model="bio.noc"
                    class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('bio.noc')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $bio->noc }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">AREA</p>
            @if ($isEditing)
                <input wire:model="bio.area" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('bio.area')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $bio->area }}
                </p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">ON BEHALF</p>
            @if ($isEditing)
                <select wire:model="bio.on_behalf" class="w-full p-2 border border-gray-200 rounded-lg">
                    @foreach (['SELF', 'SON', 'DAUGHTER', 'BROTHER', 'SISTER', 'FRIEND', 'RELATIVE', 'OTHER'] as $on_behalf)
                        <option>{{ $on_behalf }}</option>
                    @endforeach
                </select>
                @error('bio.on_behalf')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $bio->on_behalf }}
                </p>
            @endif
        </div>

        @if (auth()->user()?->id == $bio->user_id)

        <div>
            <p class="text-gray-600 text-sm">DATE OF BIRTH</p>
            @if ($isEditing)
                <input wire:model="bio.dob" type="date" class="w-full p-2 border border-gray-200 rounded-lg" />
                @error('bio.dob')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            @else
                <p>
                    {{ $bio->dob }}
                </p>
            @endif
        </div>

        @endif
    </div>
</div>
