<?php

use App\Models\User;
use Livewire\Volt\Volt as LivewireVolt;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $component = LivewireVolt::test('auth.login');
    $captcha = session('captcha_code');

    $response = $component
        ->set('email', $user->email)
        ->set('password', 'password')
        ->set('captcha', $captcha)
        ->call('login');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('profile', ['profileId' => $user->id], absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $component = LivewireVolt::test('auth.login');
    $captcha = session('captcha_code');

    $response = $component
        ->set('email', $user->email)
        ->set('password', 'wrong-password')
        ->set('captcha', $captcha)
        ->call('login');

    $response->assertHasErrors('email');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');

    $this->assertGuest();
});