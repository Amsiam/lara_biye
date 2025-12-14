<?php

use App\Models\User;
use App\Models\FamilyInformation;
use Livewire\Volt\Volt;

test('family component renders', function () {
    $user = User::factory()->create();
    $family = FamilyInformation::create([
        'user_id' => $user->id,
        'father' => 'John Doe',
        'mother' => 'Jane Doe',
    ]);

    $this->actingAs($user);
    Volt::test('profile.family', ['user' => $user, 'family' => $family])
        ->assertSee('John Doe')
        ->assertSee('Jane Doe');
});
