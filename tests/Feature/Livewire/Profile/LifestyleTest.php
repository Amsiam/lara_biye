<?php

use App\Models\User;
use App\Models\LifeStyle;
use Livewire\Volt\Volt;

test('lifestyle component renders', function () {
    $user = User::factory()->create();
    $lifestyle = LifeStyle::create([
        'user_id' => $user->id,
        'diet' => 'Veg',
    ]);

    Volt::test('profile.lifestyle', ['user' => $user, 'lifestyle' => $lifestyle])
        ->assertSee('Veg');
});
