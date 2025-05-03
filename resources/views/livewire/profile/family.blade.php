<?php

use function Livewire\Volt\{state, rules};

state(['family', 'user', 'isEditing' => false, 'siblingInfo' => []]);

rules([
    'family.father' => 'nullable|string|max:100',
    'family.father_occupation' => 'nullable|string|max:255',
    'family.mother' => 'nullable|string|max:100',
    'family.mother_occupation' => 'nullable|string|max:255',
    'family.brother' => 'nullable|string|max:100',
    'family.sister' => 'nullable|string|max:100',
    'siblingInfo.*' => 'nullable|max:100',
]);

$enableEditing = function () {
    $this->isEditing = !$this->isEditing;
    if ($this->isEditing) {
        $this->siblingInfo = $this->user->siblingInfo?->toArray();
    }
};

$addSibling = function () {
    $this->siblingInfo[] = [
        'occupation' => '',
        'academic_background' => '',
    ];
    // dd($this->siblingInfo);
};

$removeSibling = function ($index) {
    unset($this->siblingInfo[$index]);
};

$save = function () {
    $this->validate();

    $this->family->save();

    $this->user->siblingInfo()->delete();
    foreach ($this->siblingInfo as $sibling) {
        $this->user->siblingInfo()->create($sibling);
    }

    $this->user->load('siblingInfo');

    $this->isEditing = false;
};

$toggle = function () {
    $this->family->is_shown = !$this->family->is_shown;
    $this->family->save();
};

?>
<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">

    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Family Information</h3>
        <div>
            @if (auth()->user()?->id == $family?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $family->is_shown ? 'Hide' : 'Show' }}
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
        <div>
            <p class="text-gray-600 text-sm">FATHER Name</p>
            @if ($isEditing)
                <input type="text" wire:model="family.father" class="w-full p-2 border border-gray-200 rounded-lg">
                @error('family.father')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @else
                <p>{{ $family->father ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">FATHER OCCUPATION</p>
            @if ($isEditing)
                <input type="text" wire:model="family.father_occupation"
                    class="w-full p-2 border border-gray-200 rounded-lg">
                @error('family.father_occupation')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @else
                <p>{{ $family->father_occupation ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">MOTHER Name</p>
            @if ($isEditing)
                <input type="text" wire:model="family.mother" class="w-full p-2 border border-gray-200 rounded-lg">
                @error('family.mother')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @else
                <p>{{ $family->mother ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">MOTHER OCCUPATION</p>
            @if ($isEditing)
                <input type="text" wire:model="family.mother_occupation"
                    class="w-full p-2 border border-gray-200 rounded-lg">
                @error('family.mother_occupation')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @else
                <p>{{ $family->mother_occupation ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">BROTHER</p>
            @if ($isEditing)
                <input type="text" wire:model="family.brother" class="w-full p-2 border border-gray-200 rounded-lg">
                @error('family.brother')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @else
                <p>{{ $family->brother ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-sm">Sister</p>
            @if ($isEditing)
                <input type="text" wire:model="family.sister" class="w-full p-2 border border-gray-200 rounded-lg">
                @error('family.sister')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @else
                <p>{{ $family->sister ?? '-' }}</p>
            @endif
        </div>

        @if ($isEditing)
            <div>
                <button wire:click="addSibling" class="text-white bg-custom-pink px-2 rounded">Add Sibling</button>
            </div>
        @endif
    </div>
    <div class="p-4">
        <p class="text-gray-600 text-sm">Sibling Info</p>
        @if ($isEditing)
            @if (isset($siblingInfo))
                @foreach ($siblingInfo as $index => $sibling)
                    <div class="flex items-center mb-2">
                        <input type="text" wire:model="siblingInfo.{{ $index }}.occupation"
                            class="w-full p-2 border border-gray-200 rounded-lg mr-2" placeholder="Occupation">
                        <input type="text" wire:model="siblingInfo.{{ $index }}.academic_background"
                            class="w-full p-2 border border-gray-200 rounded-lg mr-2" placeholder="Academic Background">
                        <button wire:click="removeSibling({{ $index }})"
                            class="text-red-500 hover:text-red-700">Remove</button>
                    </div>
                @endforeach
                @error('siblingInfo.*')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            @endif
        @else
            @if ($user->siblingInfo?->count() == 0)
                <p class="text-gray-600 text-sm">No Sibling Info Available</p>
            @else
                <div class="flex items-center text-gray-600 text-sm">
                    <p class="w-full p-2  mr-2">Occupation</p>
                    <p class="w-full p-2  mr-2">Academic Background</p>
                </div>
                <hr class="border-gray-200 mb-2">
                @foreach ($user?->siblingInfo as $sibling)
                    <div class="flex items-center mb-2">
                        <p class="w-full p-2  mr-2">{{ $sibling?->occupation }}</p>
                        </p>
                        <p class="w-full p-2  mr-2">
                            {{ $sibling?->academic_background }}</p>
                    </div>
                @endforeach
            @endif
        @endif
    </div>

</div>
