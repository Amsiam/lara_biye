<?php

use App\Models\User;
use App\Models\SpiritualAndSocialBackground;
use Livewire\Volt\Volt;

test('spiritual component renders', function () {
    $user = User::factory()->create();
    $spiritual = SpiritualAndSocialBackground::create([
        'user_id' => $user->id,
        'caste' => 'Sunni',
    ]);

    Volt::test('profile.spiritual', ['user' => $user, 'spiritualSocial' => $spiritual])
        ->assertSee('Sunni');
});
