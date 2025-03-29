<?php

use function Livewire\Volt\{state, rules};

state(['hobby', 'user', 'isEditing' => false]);

rules([
    'address.country' => 'required|string|max:100',
    'address.division' => 'nullable|string|max:100',
    'address.district' => 'nullable|string|max:100',
    'address.upazilla' => 'nullable|string|max:100',
    'address.union' => 'nullable|string|max:100',
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
    <div class="flex justify-between items-center  bg-custom-red p-3 border-b">
        <h3 class="font-semibold text-white">Hobbies And Interests</h3>
        <div>
            <button class="text-white bg-custom-pink px-2 rounded mr-2">
                Show
            </button>
            <button class="text-white bg-custom-pink px-2 rounded">✎</button>
        </div>
    </div>
    <div class="p-4 grid grid-cols-2 gap-4">
        <div>
            <p class="text-gray-600 text-sm">HOBBY</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">INTEREST</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">MUSIC</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">BOOKS</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">MOVIE</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">TV SHOW</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">SPORTS SHOW</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">FITNESS ACTIVITY</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">CUISINE</p>
            <p>-</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">DRESS STYLE</p>
            <p>-</p>
        </div>
    </div>
</div>
