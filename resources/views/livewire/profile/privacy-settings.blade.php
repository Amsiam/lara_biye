<?php

use function Livewire\Volt\{state, mount};

state(['hide_from_search']);

mount(function () {
    $this->hide_from_search = auth()->user()->hide_from_search ?? false;
});

$togglePrivacy = function () {
    $user = auth()->user();
    $user->hide_from_search = $this->hide_from_search;
    $user->save();

    session()->flash('message', $this->hide_from_search ? 'You are now hidden from search results' : 'You are now visible in search results');
};

?>

<div class="bg-white p-6 rounded-xl shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-custom-red">Privacy Settings</h2>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="space-y-4">
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900">Hide from Search Results</h3>
                <p class="text-sm text-gray-600 mt-1">
                    When enabled, your profile will not appear in search results. Other users will not be able to find
                    you.
                </p>
            </div>
            <div class="ml-4">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="hide_from_search" wire:change="togglePrivacy"
                        class="sr-only peer">
                    <div
                        class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-custom-pink">
                    </div>
                </label>
            </div>
        </div>

        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start">
                <i class="ph-bold ph-info text-blue-600 mt-0.5 mr-2 text-lg"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Important:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Users who already have your connection can still view your profile</li>
                        <li>Your profile is still visible via share link</li>
                        <li>Your existing connections remain unaffected</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
