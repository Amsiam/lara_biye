<?php

use function Livewire\Volt\{state, rules, computed};

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
    $this->dispatch('profile-section-saved');

    $this->isEditing = false;
};

$toggle = function () {
    $this->family->is_shown = !$this->family->is_shown;
    $this->family->save();
};

$canViewContact = computed(function () {
    if (!auth()->check()) {
        return false;
    }
    return auth()->id() === $this->family?->user_id || auth()->user()->isConnected($this->family?->user_id);
});

?>

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">

    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Family Information</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == $family?->user_id)
                <button wire:click="toggle"
                    class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                    @if ($family->is_shown)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a10.029 10.029 0 003.3-4.383.75.75 0 000-.651c-1.048-2.618-4.513-5.34-8.337-5.34-1.332 0-2.584.329-3.71.912L3.28 2.22zm6.72 2.973a9.98 9.98 0 011.837-.193c3.082 0 5.865 1.77 8.01 4.5-.47.6-1.01 1.147-1.615 1.639l-1.898-1.898A2.5 2.5 0 0011.85 5.51l-1.85-1.85-.357.36.357-.36zM7.221 8.281l2.454 2.454a.972.972 0 001.045 1.045l2.454 2.454a2.463 2.463 0 01-1.174.266 2.5 2.5 0 01-2.5-2.5 2.463 2.463 0 01.266-1.174l-2.545-2.545zM2.872 7.828l2.64 2.64A9.993 9.993 0 001 10.5c1.554 3.737 5.258 6.5 9 6.5.918 0 1.8-.166 2.633-.474l1.396 1.396a11.535 11.535 0 01-4.029.578C5.259 18.5 1.411 15.612.33 10.999a.75.75 0 010-.651c.712-1.791 1.815-3.324 2.542-4.52z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Hide</span>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                            <path fill-rule="evenodd"
                                d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 8.201 2.372 9.336 6.404.332.66.332 1.42 0 1.186A10.004 10.004 0 0110 17c-4.257 0-8.201-2.372-9.336-6.406zM10 15a5 5 0 100-10 5 5 0 000 10z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Show</span>
                    @endif
                </button>
                @if (!$isEditing)
                    <button wire:click="enableEditing"
                        class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path
                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                            <path
                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                        </svg>
                        <span>Edit</span>
                    </button>
                @else
                    <button wire:click="save"
                        class="text-white bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Save</span>
                    </button>
                @endif
            @endif
        </div>
    </div>

    <div class="p-6 bg-white grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Father Name</p>
            @if ($this->canViewContact)
                @if ($isEditing)
                    <input type="text" wire:model="family.father" placeholder="e.g., Md. Rahim Uddin"
                        class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                    @error('family.father')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                @else
                    <p class="text-gray-900 font-medium">{{ $family->father ?? '-' }}</p>
                @endif
            @else
                <p class="text-gray-500 italic">Send connection to see father name</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Father Occupation</p>
            @if ($isEditing)
                <input type="text" wire:model="family.father_occupation" placeholder="e.g., Retired Govt. Officer"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                @error('family.father_occupation')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            @else
                <p class="text-gray-900 font-medium">{{ $family->father_occupation ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Mother Name</p>
            @if ($this->canViewContact)
                @if ($isEditing)
                    <input type="text" wire:model="family.mother" placeholder="e.g., Jahanara Begum"
                        class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                    @error('family.mother')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                @else
                    <p class="text-gray-900 font-medium">{{ $family->mother ?? '-' }}</p>
                @endif
            @else
                <p class="text-gray-500 italic">Send connection to see mother name</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Mother Occupation</p>
            @if ($isEditing)
                <input type="text" wire:model="family.mother_occupation" placeholder="e.g., Homemaker"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                @error('family.mother_occupation')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            @else
                <p class="text-gray-900 font-medium">{{ $family->mother_occupation ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Brother</p>
            @if ($isEditing)
                <input type="text" wire:model="family.brother" placeholder="e.g., 2 (1 married)"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                @error('family.brother')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            @else
                <p class="text-gray-900 font-medium">{{ $family->brother ?? '-' }}</p>
            @endif
        </div>
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Sister</p>
            @if ($isEditing)
                <input type="text" wire:model="family.sister" placeholder="e.g., 1 (unmarried)"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                @error('family.sister')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            @else
                <p class="text-gray-900 font-medium">{{ $family->sister ?? '-' }}</p>
            @endif
        </div>
    </div>

    <div class="px-6 pb-6">
        <div class="flex justify-between items-center mb-4">
            <p class="text-gray-600 text-sm font-semibold uppercase">Sibling Information</p>
            @if ($isEditing)
                <button wire:click="addSibling" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    + Add Sibling
                </button>
            @endif
        </div>

        @if ($isEditing)
            @if (isset($siblingInfo) && count($siblingInfo) > 0)
                <div class="space-y-3">
                    @foreach ($siblingInfo as $index => $sibling)
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <input type="text" wire:model="siblingInfo.{{ $index }}.occupation"
                                class="flex-1 p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" placeholder="Occupation">
                            <input type="text" wire:model="siblingInfo.{{ $index }}.academic_background"
                                class="flex-1 p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all" placeholder="Academic Background">
                            <button wire:click="removeSibling({{ $index }})"
                                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
                @error('siblingInfo.*')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            @else
                <p class="text-gray-500 italic text-sm">No siblings added yet. Click "Add Sibling" to add one.</p>
            @endif
        @else
            @if ($user->siblingInfo?->count() == 0)
                <p class="text-gray-500 italic text-sm">No sibling information available</p>
            @else
                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <div class="grid grid-cols-2 bg-gray-50 p-3 font-semibold text-sm text-gray-700">
                        <p>Occupation</p>
                        <p>Academic Background</p>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach ($user?->siblingInfo as $sibling)
                            <div class="grid grid-cols-2 p-3 text-sm">
                                <p class="text-gray-900 font-medium">{{ $sibling?->occupation ?? '-' }}</p>
                                <p class="text-gray-900 font-medium">{{ $sibling?->academic_background ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>

</div>
