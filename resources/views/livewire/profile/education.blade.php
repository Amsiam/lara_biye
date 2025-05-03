<?php

use function Livewire\Volt\{state, rules};

state(['education', 'user', 'isEditing' => false]);

rules([
    'education.highest_education' => 'nullable|string|max:150',
    'education.occupation' => 'nullable|string|max:150',
    'education.annual_income' => 'nullable|numeric|min:0',
    'education.last_academic_background' => 'nullable|string',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->education->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->education->is_shown = !$this->education->is_shown;
    $this->education->save();
};

?>

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Education And Career</h3>
        <div>
            @if (auth()->user()?->id == $education?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $education->is_shown ? 'Hide' : 'Show' }}
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
        'HIGHEST EDUCATION' => 'highest_education',
        'OCCUPATION' => 'occupation',
        'ANNUAL INCOME' => 'annual_income',
        'Last Academic Background' => 'last_academic_background',
    ] as $label => $field)
            <div>
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    @if ($field == 'annual_income')
                        <input type="number" step="0.01" wire:model="education.{{ $field }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                    @elseif ($field == 'last_academic_background')
                        <textarea wire:model="education.{{ $field }}" class="w-full p-2 border border-gray-200 rounded-lg"></textarea>
                    @else
                        <input type="text" wire:model="education.{{ $field }}"
                            class="w-full p-2 border border-gray-200 rounded-lg">
                    @endif
                    @error('education.' . $field)
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    @if ($field == 'annual_income' && $education->annual_income)
                        <p>{{ number_format($education->annual_income, 2) }}</p>
                    @else
                        <p>{{ $education->{$field} ?? '-' }}</p>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
