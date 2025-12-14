<?php

use function Livewire\Volt\{state, rules};

state(['hobby', 'user', 'isEditing' => false]);

rules([
    'hobby.hobby' => 'nullable|string|max:100',
    'hobby.interest' => 'nullable|string|max:100',
    'hobby.music' => 'nullable|string|max:100',
    'hobby.books' => 'nullable|string|max:100',
    'hobby.movie' => 'nullable|string|max:100',
    'hobby.tv_show' => 'nullable|string|max:100',
    'hobby.sports_show' => 'nullable|string|max:100',
    'hobby.fitness_activity' => 'nullable|string|max:100',
    'hobby.cuisine' => 'nullable|string|max:100',
    'hobby.dress_style' => 'nullable|string|max:100',
]);

$enableEditing = fn() => ($this->isEditing = !$this->isEditing);

$save = function () {
    $this->validate();

    $this->hobby->save();

    $this->isEditing = false;
};

$toggle = function () {
    $this->hobby->is_shown = !$this->hobby->is_shown;
    $this->hobby->save();
};

?>

<div class="mt-6 border border-gray-200 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
    <div class="flex justify-between items-center bg-custom-red p-4 border-b">
        <h3 class="font-bold text-white text-lg">Hobbies And Interests</h3>
        <div class="flex gap-2">
            @if (auth()->user()?->id == $hobby?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink hover:bg-pink-600 px-4 py-2 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    {{ $hobby->is_shown ? '👁️ Hide' : '👁️‍🗨️ Show' }}
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
                'HOBBY' => ['field' => 'hobby', 'placeholder' => 'e.g., Painting, Gardening'],
                'INTEREST' => ['field' => 'interest', 'placeholder' => 'e.g., Philosophy, History'],
                'MUSIC' => ['field' => 'music', 'placeholder' => 'e.g., Classical, Rock'],
                'BOOKS' => ['field' => 'books', 'placeholder' => 'e.g., Fiction, Biographies'],
                'MOVIE' => ['field' => 'movie', 'placeholder' => 'e.g., Sci-Fi, Drama'],
                'TV SHOW' => ['field' => 'tv_show', 'placeholder' => 'e.g., Breaking Bad'],
                'SPORTS SHOW' => ['field' => 'sports_show', 'placeholder' => 'e.g., Football, Cricket'],
                'FITNESS ACTIVITY' => ['field' => 'fitness_activity', 'placeholder' => 'e.g., Yoga, Gym'],
                'CUISINE' => ['field' => 'cuisine', 'placeholder' => 'e.g., Italian, Bengali'],
                'DRESS STYLE' => ['field' => 'dress_style', 'placeholder' => 'e.g., Casual, Traditional'],
            ] as $label => $data)
                        <div>
                            <p class="text-gray-600 text-xs font-semibold uppercase mb-2">{{ $label }}</p>
                            @if ($isEditing)
                                <input type="text" placeholder="{{ $data['placeholder'] }}"
                                    wire:model="hobby.{{ $data['field'] }}" class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-custom-pink focus:ring-2 focus:ring-custom-pink/20 transition-all text-gray-900 bg-white">
                                @error('hobby.' . $data['field'])
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            @else
                                <p class="text-gray-900 font-medium">{{ $hobby->{$data['field']} ?? '-' }}</p>
                            @endif
                        </div>
        @endforeach
    </div>
</div>
