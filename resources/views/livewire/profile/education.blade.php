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

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Education And Career</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == $education?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    {{ $education->is_shown ? '👁️ Hide' : '👁️‍🗨️ Show' }}
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
        @foreach ([
        'HIGHEST EDUCATION' => ['field' => 'highest_education', 'placeholder' => "e.g., Bachelor's in Computer Science", 'type' => 'text'],
        'OCCUPATION' => ['field' => 'occupation', 'placeholder' => 'e.g., Software Engineer', 'type' => 'text'],
        'ANNUAL INCOME' => ['field' => 'annual_income', 'placeholder' => 'e.g., 50000', 'type' => 'number'],
        'Last Academic Background' => ['field' => 'last_academic_background', 'placeholder' => 'e.g., Graduated from XYZ University in 2020', 'type' => 'textarea'],
    ] as $label => $data)
            @php $field = $data['field']; @endphp
            <div class="{{ $data['type'] === 'textarea' ? 'md:col-span-2' : '' }}">
                <p class="text-gray-600 text-xs font-semibold uppercase mb-2">{{ $label }}</p>
                @if ($isEditing)
                    @if ($data['type'] === 'number')
                        <input type="number" step="0.01" placeholder="{{ $data['placeholder'] }}"
                            wire:model="education.{{ $field }}"
                            class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all">
                    @elseif ($data['type'] === 'textarea')
                        <textarea placeholder="{{ $data['placeholder'] }}" wire:model="education.{{ $field }}" rows="4"
                            class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all"></textarea>
                    @else
                        <input type="text" placeholder="{{ $data['placeholder'] }}"
                            wire:model="education.{{ $field }}"
                            class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all">
                    @endif
                    @error('education.' . $field)
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                @else
                    @if ($data['type'] === 'number' && $education->annual_income)
                        <p class="text-gray-900 font-medium">{{ number_format($education->annual_income, 2) }}</p>
                    @else
                        <p class="text-gray-900 font-medium">{{ $education->{$field} ?? '-' }}</p>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>
