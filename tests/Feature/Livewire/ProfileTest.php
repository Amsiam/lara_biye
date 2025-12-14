<?php

use App\Models\User;
use App\Models\BasicInfo;
use Livewire\Volt\Volt;

test('profile page renders for owner', function () {
    $user = User::factory()->create();
    $basicInfo = BasicInfo::create([
        'user_id' => $user->id,
        'dob' => '1990-01-01',
        'blood_group' => 'A+',
        'bio' => 'Bio',
    ]);
    \App\Models\Connection::create(['user_id' => $user->id, 'connection' => 10]);
    \App\Models\PartnerExpectation::create(['user_id' => $user->id]);
    \App\Models\EducationCareer::create(['user_id' => $user->id]);
    \App\Models\Location::create(['user_id' => $user->id]);
    \App\Models\HobbiesAndInterest::create(['user_id' => $user->id]);
    \App\Models\ResidencyInformation::create(['user_id' => $user->id]);
    \App\Models\SpiritualAndSocialBackground::create(['user_id' => $user->id]);
    \App\Models\LifeStyle::create(['user_id' => $user->id]);
    \App\Models\Language::create(['user_id' => $user->id]);
    \App\Models\PersonalAttitude::create(['user_id' => $user->id]);
    \App\Models\PhysicalAttribute::create(['user_id' => $user->id]);
    \App\Models\FamilyInformation::create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('profile', ['profileId' => $user->id]))
        ->assertOk()
        ->assertSee($user->name);
});

test('profile page renders for other user', function () {
    $viewer = User::factory()->create();
    $target = User::factory()->create();
    $basicInfo = BasicInfo::create([
        'user_id' => $target->id,
        'dob' => '1990-01-01',
        'blood_group' => 'A+',
        'bio' => 'Bio',
    ]);
    \App\Models\Connection::create(['user_id' => $viewer->id, 'connection' => 10]);
    \App\Models\Connection::create(['user_id' => $target->id, 'connection' => 10]);
    \App\Models\PartnerExpectation::create(['user_id' => $target->id]);
    \App\Models\EducationCareer::create(['user_id' => $target->id]);
    \App\Models\Location::create(['user_id' => $target->id]);
    \App\Models\HobbiesAndInterest::create(['user_id' => $target->id]);
    \App\Models\ResidencyInformation::create(['user_id' => $target->id]);
    \App\Models\SpiritualAndSocialBackground::create(['user_id' => $target->id]);
    \App\Models\LifeStyle::create(['user_id' => $target->id]);
    \App\Models\Language::create(['user_id' => $target->id]);
    \App\Models\PersonalAttitude::create(['user_id' => $target->id]);
    \App\Models\PhysicalAttribute::create(['user_id' => $target->id]);
    \App\Models\FamilyInformation::create(['user_id' => $target->id]);

    $this->actingAs($viewer)
        ->get(route('profile', ['profileId' => $target->id]))
        ->assertOk()
        ->assertSee($target->name);
});

test('profile completion widget visible only to owner', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    BasicInfo::create([
        'user_id' => $owner->id,
        'dob' => '1990-01-01',
        'blood_group' => 'A+',
        'bio' => 'Bio',
    ]);
    BasicInfo::create([
        'user_id' => $other->id,
        'dob' => '1990-01-01',
        'blood_group' => 'A+',
        'bio' => 'Bio',
    ]);

    \App\Models\Connection::create(['user_id' => $owner->id, 'connection' => 10]);
    \App\Models\Connection::create(['user_id' => $other->id, 'connection' => 10]);

    foreach ([$owner, $other] as $u) {
        \App\Models\PartnerExpectation::create(['user_id' => $u->id]);
        \App\Models\EducationCareer::create(['user_id' => $u->id]);
        \App\Models\Location::create(['user_id' => $u->id]);
        \App\Models\HobbiesAndInterest::create(['user_id' => $u->id]);
        \App\Models\ResidencyInformation::create(['user_id' => $u->id]);
        \App\Models\SpiritualAndSocialBackground::create(['user_id' => $u->id]);
        \App\Models\LifeStyle::create(['user_id' => $u->id]);
        \App\Models\Language::create(['user_id' => $u->id]);
        \App\Models\PersonalAttitude::create(['user_id' => $u->id]);
        \App\Models\PhysicalAttribute::create(['user_id' => $u->id]);
        \App\Models\FamilyInformation::create(['user_id' => $u->id]);
    }

    $this->actingAs($owner)
        ->get(route('profile', ['profileId' => $owner->id]))
        ->assertSee('Profile Completion');

    $this->actingAs($other)
        ->get(route('profile', ['profileId' => $owner->id]))
        ->assertDontSee('Profile Completion');
});
