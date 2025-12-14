<?php

use App\Models\User;
use App\Models\Language;
use Livewire\Volt\Volt;

test('language component renders', function () {
    $user = User::factory()->create();
    $language = Language::create([
        'user_id' => $user->id,
        'mother_tongue' => 'Bengali',
        'language' => 'English',
    ]);

    Volt::test('profile.language', ['user' => $user, 'lang' => $language])
        ->assertSee('Bengali')
        ->assertSee('English');
});
