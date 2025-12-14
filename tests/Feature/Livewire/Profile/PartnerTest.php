<?php

use App\Models\User;
use App\Models\PartnerExpectation;
use Livewire\Volt\Volt;

test('partner component renders', function () {
    $user = User::factory()->create();
    $partner = PartnerExpectation::create([
        'user_id' => $user->id,
        'age_min' => 20,
        'age_max' => 25,
    ]);

    Volt::test('profile.partner', ['user' => $user, 'partner' => $partner])
        ->assertSee('20');
});
