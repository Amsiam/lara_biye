<?php

use Livewire\Volt\Volt;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $component = Volt::test('auth.register');
    $captcha = session('captcha_code');

    $response = $component
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->set('dob', '1990-01-01')
        ->set('gender', 'MALE')
        ->set('religion', 'ISLAM')
        ->set('verification_type', 'nid')
        ->set('nid', '1234567890')
        ->set('student_id', 'S12345')
        ->set('university', 'Dhaka University')
        ->set('captcha', $captcha)
        ->call('register');

    $user = User::where('email', 'test@example.com')->first();
    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('profile', ['profileId' => $user->id], absolute: false));

    $this->assertAuthenticated();
});