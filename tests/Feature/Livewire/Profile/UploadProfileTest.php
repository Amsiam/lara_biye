<?php

use App\Models\User;
use App\Models\BasicInfo;
use Livewire\Volt\Volt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('upload profile component renders', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'blood_group' => 'A+',
        'bio' => 'Bio',
    ]);

    Volt::test('profile.upload-profile', ['user' => $user])
        ->assertSee('Profile Image');
});

test('can upload profile photo', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $basicInfo = BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'blood_group' => 'A+',
        'bio' => 'Bio',
    ]);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $this->actingAs($user);

    Volt::test('profile.upload-profile', ['user' => $user])
        ->set('photo', $file)
        ->assertHasNoErrors();

    // Re-fetch basicInfo to check update
    $basicInfo->refresh();

    // Check if image path is not null
    expect($basicInfo->image)->not->toBeNull();
});

test('cannot upload non-image file', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'blood_group' => 'A+',
        'bio' => 'Bio',
    ]);

    $this->actingAs($user);

    $file = UploadedFile::fake()->create('document.pdf', 100);

    Volt::test('profile.upload-profile', ['user' => $user])
        ->set('photo', $file)
        ->call('uploadPhoto')
        ->assertSet('errorMessage', 'Please select a photo first.');
});

test('cannot upload file larger than 5MB', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'blood_group' => 'A+',
        'bio' => 'Bio',
    ]);

    $this->actingAs($user);

    $file = UploadedFile::fake()->image('large.jpg')->size(6000); // 6MB

    Volt::test('profile.upload-profile', ['user' => $user])
        ->set('photo', $file)
        ->call('uploadPhoto')
        ->assertSet('errorMessage', 'The photo field must not be greater than 5120 kilobytes.');
});

test('cannot upload image with small dimensions', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'blood_group' => 'A+',
        'bio' => 'Bio',
    ]);

    $this->actingAs($user);

    $file = UploadedFile::fake()->image('small.jpg', 100, 100);

    Volt::test('profile.upload-profile', ['user' => $user])
        ->set('photo', $file)
        ->call('uploadPhoto')
        ->assertSet('errorMessage', 'The photo field has invalid image dimensions.');
});
