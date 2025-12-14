<?php

use App\Models\User;
use App\Models\EducationCareer;
use Livewire\Volt\Volt;

test('education component renders', function () {
    $user = User::factory()->create();
    $education = EducationCareer::create([
        'user_id' => $user->id,
        'highest_education' => 'BSc',
        'occupation' => 'Engineer',
    ]);

    Volt::test('profile.education', ['user' => $user, 'education' => $education])
        ->assertSee('BSc')
        ->assertSee('Engineer');
});
