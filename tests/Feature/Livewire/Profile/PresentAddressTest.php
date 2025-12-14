<?php

use App\Models\User;
use App\Models\Location;
use Livewire\Volt\Volt;

test('present address component renders', function () {
    $user = User::factory()->create();
    $address = Location::create([
        'user_id' => $user->id,
        'country' => 'Bangladesh',
    ]);

    Volt::test('profile.present_address', ['user' => $user, 'address' => $address])
        ->assertSee('Bangladesh');
});
