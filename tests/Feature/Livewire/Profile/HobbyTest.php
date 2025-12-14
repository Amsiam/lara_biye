<?php

use App\Models\User;
use App\Models\HobbiesAndInterest;
use Livewire\Volt\Volt;

test('hobby component renders', function () {
    $user = User::factory()->create();
    $hobby = HobbiesAndInterest::create([
        'user_id' => $user->id,
        'hobby' => 'Coding, Reading',
    ]);

    Volt::test('profile.hobby', ['user' => $user, 'hobby' => $hobby])
        ->assertSee('Coding')
        ->assertSee('Reading');
});
