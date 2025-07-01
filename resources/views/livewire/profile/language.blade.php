<?php

use function Livewire\Volt\{state, rules};

state(['lang', 'user', 'isEditing' => false]);

rules([
    'lang.mother_tongue' => 'nullable|string|max:100',
    'lang.language' => 'nullable|string|max:100',
    'lang.speak' => 'nullable|string|max:100',
    'lang.read' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->lang->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->lang->is_shown = !$this->lang->is_shown;
    $this->lang->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Language</h3>
        <div>
            @if (auth()->user()?->id == $lang?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $lang->is_shown ? 'Hide' : 'Show' }}
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
        'MOTHER TONGUE' => ['field' => 'mother_tongue', 'placeholder' => 'e.g., Bengali'],
        'LANGUAGE' => ['field' => 'language', 'placeholder' => 'e.g., English, Hindi'],
        'SPEAK' => ['field' => 'speak', 'placeholder' => 'e.g., Fluent'],
        'READ' => ['field' => 'read', 'placeholder' => 'e.g., Moderate'],
    ] as $label => $data)
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    <input type="text" wire:model="lang.{{ $data['field'] }}"
                        placeholder="{{ $data['placeholder'] }}" class="w-full p-2 border border-gray-200 rounded-lg">
                    @error('lang.' . $data['field'])
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    <p>{{ $lang->{$data['field']} ?? '-' }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
