<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class ReferralRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_gets_three_connections_by_default()
    {
        $component = Volt::test('auth.register');
        $captcha = session('captcha_code');

        $component
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('mobile', '01712345678')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('dob', '1990-01-01')
            ->set('gender', 'MALE')
            ->set('religion', 'ISLAM')
            ->set('student_id', '12345')
            ->set('university', 'Test Uni')
            ->set('verification_type', 'nid')
            ->set('nid', '1234567890')
            ->set('captcha', $captcha)
            ->call('register')
            ->assertHasNoErrors();

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->connection);
        $this->assertEquals(3, $user->connection->connection);
    }

    public function test_referred_user_gets_five_connections_and_referrer_gets_two()
    {
        $referrer = User::factory()->create([
            'referral_code' => 'REF123',
        ]);
        // Ensure referrer has 0 connections initially (or create connection record)
        $referrer->connection()->create(['connection' => 0]);

        $component = Volt::test('auth.register');
        $captcha = session('captcha_code');

        $component
            ->set('name', 'Referred User')
            ->set('email', 'referred@example.com')
            ->set('mobile', '01712345678')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('dob', '1990-01-01')
            ->set('gender', 'FEMALE')
            ->set('religion', 'ISLAM')
            ->set('student_id', '54321')
            ->set('university', 'Test Uni')
            ->set('verification_type', 'nid')
            ->set('nid', '0987654321')
            ->set('referral_code', 'REF123')
            ->set('captcha', $captcha)
            ->call('register')
            ->assertHasNoErrors();

        $newUser = User::where('email', 'referred@example.com')->first();
        $this->assertNotNull($newUser);

        // Check New User Connections (3 base + 2 bonus = 5)
        $this->assertNotNull($newUser->connection);
        $this->assertEquals(5, $newUser->connection->connection);
        $this->assertEquals($referrer->id, $newUser->referrer_id);

        // Check Referrer Connections (0 + 2 = 2)
        $referrer->refresh();
        $this->assertEquals(2, $referrer->connection->connection);
    }
}
