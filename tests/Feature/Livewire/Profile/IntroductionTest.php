<?php

use App\Models\User;
use App\Models\BasicInfo;
use Livewire\Volt\Volt;

test('introduction component renders', function () {
    $user = User::factory()->create();
    $basicInfo = BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'blood_group' => 'A+',
        'bio' => 'Hello World', // 'about_me' is likely 'bio' in database or handled differently, strictly speaking 'bio' column exists.
    ]);

    Volt::test('profile.introduction', ['bio' => $basicInfo])
        ->assertSee('Hello World');
});
