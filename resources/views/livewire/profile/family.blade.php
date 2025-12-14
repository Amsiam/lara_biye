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
                <button wire:click="toggle" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    {{ $family->is_shown ? '👁️ Hide' : '👁️‍🗨️ Show' }}
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
