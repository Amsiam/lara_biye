<?php

use App\Models\User;
use App\Models\ResidencyInformation;
use Livewire\Volt\Volt;

test('parmanent component renders', function () {
    $user = User::factory()->create();
    $parmanent = ResidencyInformation::create([
        'user_id' => $user->id,
        'residency_country' => 'Bangladesh',
    ]);

    Volt::test('profile.parmanent', ['user' => $user, 'parmanent' => $parmanent])
        ->assertSee('Bangladesh');
});
