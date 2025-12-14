<?php

use App\Models\User;
use App\Models\PhysicalAttribute;
use Livewire\Volt\Volt;

test('physical attr component renders', function () {
    $user = User::factory()->create();
    $physical = PhysicalAttribute::create([
        'user_id' => $user->id,
        'eye_color' => 'Black',
        'hair_color' => 'Brown',
    ]);

    Volt::test('profile.physical_attr', ['user' => $user, 'physical' => $physical])
        ->assertSee('Black')
        ->assertSee('Brown');
});
