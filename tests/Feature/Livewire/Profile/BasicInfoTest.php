<?php

use App\Models\User;
use App\Models\BasicInfo;
use Livewire\Volt\Volt;

test('basic info can be updated', function () {
    $user = User::factory()->create();
    $basicInfo = BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'gender' => 'MALE',
        'marital_status' => 'UNMARRIED',
        'noc' => 0,
        'on_behalf' => 'SELF',
        'blood_group' => 'A+',
        'height' => 170,
        'weight' => 70,
        'religion' => 'ISLAM',
        'verification_type' => 'nid',
        'nid' => '1234567890',
        'bio' => 'Bio',
    ]);

    $this->actingAs($user);

    $component = Volt::test('profile.basic_info', ['user' => $user, 'bio' => $basicInfo])
        ->set('isEditing', true)
        ->set('user.name', 'New Name')
        ->set('bio.height', 175)
        ->call('save');

    $component->assertHasNoErrors();

    expect($user->fresh()->name)->toBe('New Name');
    expect($basicInfo->fresh()->height)->toBe(175.0);
});

test('nid and birth certificate are mutually exclusive', function () {
    $user = User::factory()->create();
    $basicInfo = BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'gender' => 'MALE',
        'marital_status' => 'UNMARRIED',
        'noc' => 0,
        'on_behalf' => 'SELF',
        'blood_group' => 'A+',
        'height' => 170,
        'weight' => 70,
        'religion' => 'ISLAM',
        'verification_type' => 'nid',
        'nid' => '11111',
        'bio' => 'Bio',
    ]);

    $this->actingAs($user);

    // Switch to Birth Certificate
    Volt::test('profile.basic_info', ['user' => $user, 'bio' => $basicInfo])
        ->set('isEditing', true)
        ->set('verification_type', 'birth_certificate')
        ->set('bio.birth_certificate', '999999')
        ->call('save')
        ->assertHasNoErrors();

    $basicInfo->refresh();
    expect($basicInfo->nid)->toBeNull();
    expect($basicInfo->birth_certificate)->toBe('999999');

    // Switch back to NID
    Volt::test('profile.basic_info', ['user' => $user, 'bio' => $basicInfo])
        ->set('isEditing', true)
        ->set('verification_type', 'nid')
        ->set('bio.nid', '88888')
        ->call('save')
        ->assertHasNoErrors();

    $basicInfo->refresh();
    expect($basicInfo->birth_certificate)->toBeNull();
    expect($basicInfo->nid)->toBe('88888');
});

test('validation requires verification document', function () {
    $user = User::factory()->create();
    $basicInfo = BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'gender' => 'MALE',
        // ... fill required fields to avoid other errors
        'marital_status' => 'UNMARRIED',
        'noc' => 0,
        'on_behalf' => 'SELF',
        'blood_group' => 'A+',
        'height' => 170,
        'weight' => 70,
        'religion' => 'ISLAM',
        'bio' => 'Bio',
    ]);

    $this->actingAs($user);

    // Try to save with updated verification_type but empty field
    Volt::test('profile.basic_info', ['user' => $user, 'bio' => $basicInfo])
        ->set('isEditing', true)
        ->set('verification_type', 'nid')
        ->set('bio.nid', '')
        ->call('save')
        ->assertHasErrors(['bio.nid' => 'required_if']);
});

test('unauthorized user cannot update basic info', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $basicInfo = BasicInfo::create([
        'user_id' => $owner->id,
        'dob' => '1990-01-01',
        'gender' => 'MALE',
        'marital_status' => 'UNMARRIED',
        'noc' => 0,
        'on_behalf' => 'SELF',
        'blood_group' => 'A+',
        'height' => 170,
        'weight' => 70,
        'religion' => 'ISLAM',
        'verification_type' => 'nid',
        'nid' => '1234567890',
        'bio' => 'Original Bio',
    ]);

    $this->actingAs($attacker);

    Volt::test('profile.basic_info', ['user' => $owner, 'bio' => $basicInfo])
        ->set('isEditing', true)
        ->set('bio.height', 180)
        ->call('save')
        ->assertStatus(403);

    expect($basicInfo->fresh()->height)->toBe(170.0);
});

test('age must be at least 17 years', function () {
    $user = User::factory()->create();
    $basicInfo = BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'gender' => 'MALE',
        'marital_status' => 'UNMARRIED',
        'noc' => 0,
        'on_behalf' => 'SELF',
        'blood_group' => 'A+',
        'height' => 170,
        'weight' => 70,
        'religion' => 'ISLAM',
        'verification_type' => 'nid',
        'nid' => '1234567890',
        'bio' => 'Bio',
    ]);

    $this->actingAs($user);

    // Try with today's date (definitely invalid)
    Volt::test('profile.basic_info', ['user' => $user, 'bio' => $basicInfo])
        ->set('isEditing', true)
        ->set('bio.dob', now()->subYears(16)->format('Y-m-d'))
        ->call('save')
        ->assertHasErrors(['bio.dob']);
});

test('nid cannot exceed 20 characters', function () {
    $user = User::factory()->create();
    $basicInfo = BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'gender' => 'MALE',
        'marital_status' => 'UNMARRIED',
        'noc' => 0,
        'on_behalf' => 'SELF',
        'blood_group' => 'A+',
        'height' => 170,
        'weight' => 70,
        'religion' => 'ISLAM',
        'verification_type' => 'nid',
        'nid' => '1234567890',
        'bio' => 'Bio',
    ]);

    $this->actingAs($user);

    Volt::test('profile.basic_info', ['user' => $user, 'bio' => $basicInfo])
        ->set('isEditing', true)
        ->set('verification_type', 'nid')
        ->set('bio.nid', str_repeat('1', 21))
        ->call('save')
        ->assertHasErrors(['bio.nid']);
});
