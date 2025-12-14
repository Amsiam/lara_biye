<?php

use App\Models\User;
use App\Models\BasicInfo;
use Livewire\Volt\Volt;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function createWithBasicInfo(array $attributes = [], array $basicInfoAttributes = [])
{
    $userDefaults = ['is_admin' => false, 'hide_from_search' => false];
    $user = User::factory()->create(array_merge($userDefaults, $attributes));
    $defaults = [
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
    ];

    BasicInfo::create(array_merge($defaults, $basicInfoAttributes));
    return $user;
}

test('search page renders successfully', function () {
    $response = $this->get('/search');
    $response->assertStatus(200);
});

test('search page displays profiles', function () {
    $user = createWithBasicInfo(['is_admin' => false, 'hide_from_search' => false], ['gender' => 'MALE']);

    Volt::test('search')
        ->assertSee(route('profile', $user->id), false);
});

test('pagination works', function () {
    for ($i = 0; $i < 35; $i++) {
        createWithBasicInfo(['is_admin' => false, 'hide_from_search' => false], ['gender' => 'MALE']);
    }

    Volt::test('search')
        ->assertSee('Next')
        ->call('nextPage')
        ->assertSee('Previous');
});

test('can filter by gender', function () {
    $male = createWithBasicInfo(['name' => 'Male User'], ['gender' => 'MALE']);
    $female = createWithBasicInfo(['name' => 'Female User'], ['gender' => 'FEMALE']);

    Volt::test('search')
        ->set('gender', 'MALE')
        ->assertSee(route('profile', $male->id), false)
        ->assertDontSee(route('profile', $female->id), false)
        ->set('gender', 'FEMALE')
        ->assertSee(route('profile', $female->id), false)
        ->assertDontSee(route('profile', $male->id), false);
});

test('can filter by age range', function () {
    // 20 years old
    $young = createWithBasicInfo(['name' => 'Young User'], [
        'dob' => now()->subYears(20)->format('Y-m-d'),
        'gender' => 'MALE'
    ]);

    // 40 years old
    $old = createWithBasicInfo(['name' => 'Old User'], [
        'dob' => now()->subYears(40)->format('Y-m-d'),
        'gender' => 'MALE'
    ]);

    Volt::test('search')
        ->set('age', '18-25')
        ->assertSee(route('profile', $young->id), false)
        ->assertDontSee(route('profile', $old->id), false)
        ->set('age', '36-45')
        ->assertSee(route('profile', $old->id), false)
        ->assertDontSee(route('profile', $young->id), false);
});

test('can filter by religion', function () {
    $muslim = createWithBasicInfo(['name' => 'Muslim User'], ['religion' => 'ISLAM']);
    $hindu = createWithBasicInfo(['name' => 'Hindu User'], ['religion' => 'HINDU']);

    Volt::test('search')
        ->set('religion', 'Islam')
        ->assertSee(route('profile', $muslim->id), false)
        ->assertDontSee(route('profile', $hindu->id), false);
});

test('can filter by height', function () {
    $short = createWithBasicInfo(['name' => 'Short User'], ['height' => 150]);
    $tall = createWithBasicInfo(['name' => 'Tall User'], ['height' => 180]);

    Volt::test('search')
        ->set('min_height', 170)
        ->tap(function ($component) use ($tall, $short) {
            $ids = $component->profiles->pluck('id');
            expect($ids)->toContain($tall->id)
                ->not->toContain($short->id);
        })
        ->set('min_height', null) // Clear previous filter
        ->set('max_height', 160)
        ->tap(function ($component) use ($tall, $short) {
            $ids = $component->profiles->pluck('id');
            expect($ids)->toContain($short->id)
                ->not->toContain($tall->id);
        });
});

test('can filter by marital status', function () {
    $single = createWithBasicInfo(['name' => 'Single User'], ['marital_status' => 'UNMARRIED']);
    $married = createWithBasicInfo(['name' => 'Married User'], ['marital_status' => 'MARRIED']);

    Volt::test('search')
        ->set('marital_status', 'UNMARRIED')
        ->assertSee(route('profile', $single->id), false)
        ->assertDontSee(route('profile', $married->id), false);
});

test('can filter by income', function () {
    // Monthly income 50k -> Annual 600k
    $rich = createWithBasicInfo(['name' => 'Rich User']);
    $rich->education()->create(['annual_income' => 600000]);

    // Monthly income 20k -> Annual 240k
    $poor = createWithBasicInfo(['name' => 'Poor User']);
    $poor->education()->create(['annual_income' => 240000]);

    // Search for min monthly 40k (Annual 480k)
    Volt::test('search')
        ->set('min_income', 40000)
        ->assertSee(route('profile', $rich->id), false)
        ->assertDontSee(route('profile', $poor->id), false);
});

test('can filter by location', function () {
    $dhaka = createWithBasicInfo(['name' => 'Dhaka User']);
    $dhaka->location()->create(['district' => 'Dhaka', 'upazilla' => 'Mirpur']);

    $chittagong = createWithBasicInfo(['name' => 'Chittagong User']);
    $chittagong->location()->create(['district' => 'Chittagong', 'upazilla' => 'Pahartali']);

    Volt::test('search')
        ->set('district', 'Dhaka')
        ->tap(function ($component) use ($dhaka, $chittagong) {
            $ids = $component->profiles->pluck('id');
            expect($ids)->toContain($dhaka->id)
                ->not->toContain($chittagong->id);
        })
        ->set('district', null) // Clear previous filter
        ->set('city', 'Pahartali')
        ->tap(function ($component) use ($dhaka, $chittagong) {
            $ids = $component->profiles->pluck('id');
            expect($ids)->toContain($chittagong->id)
                ->not->toContain($dhaka->id);
        });
});

test('can clear filters', function () {
    $user = createWithBasicInfo(['name' => 'Test User'], ['gender' => 'MALE']);

    Volt::test('search')
        ->set('gender', 'FEMALE')
        ->call('clearFilters')
        ->assertSet('gender', null);
});
