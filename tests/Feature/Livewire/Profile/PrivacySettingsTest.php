<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('privacy settings can be toggled', function () {
    $user = User::factory()->create(['hide_from_search' => false]);
    $this->actingAs($user);

    Volt::test('profile.privacy-settings')
        ->set('hide_from_search', true)
        ->call('togglePrivacy');

    expect($user->fresh()->hide_from_search)->toBeTrue();

    Volt::test('profile.privacy-settings')
        ->set('hide_from_search', false)
        ->call('togglePrivacy');

    expect($user->fresh()->hide_from_search)->toBeFalse();
});
