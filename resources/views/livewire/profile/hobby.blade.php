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

<div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
    <div class="flex justify-between items-center bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Hobbies And Interests</h3>
        <div>
            @if (auth()->user()?->id == $hobby?->user_id)
                <button wire:click="toggle" class="text-white bg-custom-pink px-2 rounded mr-2">
                    {{ $hobby->is_shown ? 'Hide' : 'Show' }}
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
                <p class="text-gray-600 text-sm">{{ $label }}</p>
                @if ($isEditing)
                    <input type="text" placeholder="{{ $data['placeholder'] }}"
                        wire:model="hobby.{{ $data['field'] }}" class="w-full p-2 border border-gray-200 rounded-lg">
                    @error('hobby.' . $data['field'])
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                @else
                    <p>{{ $hobby->{$data['field']} ?? '-' }}</p>
                @endif
            </div>
        @endforeach
    </div>
</div>
