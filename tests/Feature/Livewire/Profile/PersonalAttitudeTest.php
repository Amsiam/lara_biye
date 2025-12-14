<?php

use App\Models\User;
use App\Models\PersonalAttitude;
use Livewire\Volt\Volt;

test('personal attitude component renders', function () {
    $user = User::factory()->create();
    $personal = PersonalAttitude::create([
        'user_id' => $user->id,
        'political_view' => 'Liberal',
    ]);

    Volt::test('profile.personal_attitude', ['user' => $user, 'personal' => $personal])
        ->assertSee('Liberal');
});
