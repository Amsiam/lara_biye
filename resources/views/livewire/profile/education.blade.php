<?php

use function Livewire\Volt\{state, rules};

state(['education', 'user', 'isEditing' => false, 'university' => '']);

rules([
    'university' => 'nullable|string|max:100',
    'education.highest_education' => 'nullable|string|max:150',
    'education.occupation' => 'nullable|string|max:150',
    'education.annual_income' => 'nullable|numeric|min:0',
    'education.last_academic_background' => 'nullable|string',
]);

$enableEditing = function () {
    $this->isEditing = !$this->isEditing;
    if ($this->isEditing) {
        $this->university = $this->user->basicInfo->university;
    }
};

$save = function () {
    $this->validate();

    $this->education->save();
    $this->user->basicInfo->update(['university' => $this->university]);
    $this->dispatch('profile-section-saved');

    $this->isEditing = false;
};

$toggle = function () {
    $this->education->is_shown = !$this->education->is_shown;
    $this->education->save();
};

?>

<div
    class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Education And Career</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == $education?->user_id)
                <button wire:click="toggle"
                    class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                    @if ($education->is_shown)
                        <i class="ph-bold ph-eye-slash"></i>
                        <span>Hide</span>
                    @else
                        <i class="ph-bold ph-eye"></i>
                        <span>Show</span>
                    @endif
                </button>
                @if (!$isEditing)
                    <button wire:click="enableEditing"
                        class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <i class="ph-bold ph-pencil-simple"></i>
                        <span>Edit</span>
                    </button>
                @else
                    <button wire:click="save"
                        class="text-white bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 flex items-center gap-2">
                        <i class="ph-bold ph-floppy-disk"></i>
                        <span>Save</span>
                    </button>
                @endif
            @endif
        </div>
    </div>
    <div class="p-6 bg-white grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Highest Education -->
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Highest Education</p>
            @if ($isEditing)
                <input type="text" placeholder="e.g., Bachelor's in Computer Science" wire:model="education.highest_education"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                @error('education.highest_education')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                    <p class="text-gray-900 font-medium">{{ $education->highest_education ?? '-' }}</p>
                @endif
                </div>
                
                <!-- Occupation -->
                <div>
                    <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Occupation</p>
                    @if ($isEditing)
                        <input type="text" placeholder="e.g., Software Engineer" wire:model="education.occupation"
                            class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                        @error('education.occupation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    @else
                <p class="text-gray-900 font-medium">{{ $education->occupation ?? '-' }}</p>
            @endif
        </div>
        
        <!-- Annual Income -->
        <div>
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Annual Income</p>
            @if ($isEditing)
                <input type="number" step="0.01" placeholder="e.g., 50000" wire:model="education.annual_income"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                @error('education.annual_income')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                @if ($education->annual_income)
                    <p class="text-gray-900 font-medium">{{ number_format($education->annual_income, 2) }}</p>
                @else
                    <p class="text-gray-900 font-medium">-</p>
                @endif
            @endif
                    </div>
                    
                    <!-- University -->
                    <div>
                        <p class="text-gray-600 text-xs font-semibold uppercase mb-2">University</p>
                        @if ($isEditing)
                            <input type="text" placeholder="Enter university name" wire:model="university"
                                class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                            @error('university')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        @else
                            <p class="text-gray-900 font-medium">{{ $user->basicInfo->university ?? '-' }}</p>
                        @endif
        </div>
        
        <!-- Last Academic Background -->
        <div class="md:col-span-2">
            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">Last Academic Background</p>
            @if ($isEditing)
                <textarea placeholder="e.g., Graduated from XYZ University in 2020" wire:model="education.last_academic_background"
                    rows="4"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all"></textarea>
                @error('education.last_academic_background')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            @else
                <p class="text-gray-900 font-medium">{{ $education->last_academic_background ?? '-' }}</p>
            @endif
            </div>
    </div>
</div>