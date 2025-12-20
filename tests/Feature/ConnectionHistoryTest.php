<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ConnectionHistory;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class ConnectionHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_connection_history()
    {
        // Set referral reward setting
        Setting::set('referral_reward', 2);

        $referralCode = 'REF12345';
        $referrer = User::factory()->create(['referral_code' => $referralCode]);
        $referrer->connection()->create(['connection' => 10]);

        Volt::test('auth.register')
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('mobile', '01712345678')
            ->set('dob', now()->subYears(20)->format('Y-m-d'))
            ->set('gender', 'male')
            ->set('religion', 'ISLAM')
            ->set('nid', '1234567890')
            ->set('student_id', 'ST12345')
            ->set('university', 'Dhaka University')
            ->set('captcha', session('captcha_code')) // Use session captcha
            ->set('referral_code', $referralCode) // Using referral code
            ->call('register');

        $newUser = User::where('email', 'test@example.com')->first();

        // Check New User History (Signup + Referral Bonus)
        $this->assertDatabaseHas('connection_histories', [
            'user_id' => $newUser->id,
            'type' => 'signup_bonus',
            'amount' => 3,
        ]);

        $this->assertDatabaseHas('connection_histories', [
            'user_id' => $newUser->id,
            'type' => 'referral_bonus',
            'amount' => 2,
        ]);

        // Check Referrer History
        $this->assertDatabaseHas('connection_histories', [
            'user_id' => $referrer->id,
            'type' => 'referral_bonus',
            'amount' => 2,
        ]);
    }

    public function test_sending_connection_request_debits_balance_and_logs_history()
    {
        $sender = User::factory()->create();
        $sender->connection()->updateOrCreate(['user_id' => $sender->id], ['connection' => 5]);
        $receiver = User::factory()->create();

        // Act
        $sender->sendConnectionRequest($receiver);

        // Assert Balance -1
        $this->assertEquals(4, $sender->connection->fresh()->connection);

        // Assert History Log
        $this->assertDatabaseHas('connection_histories', [
            'user_id' => $sender->id,
            'amount' => -1,
            'type' => 'connection_request_sent',
        ]);
    }

    public function test_accepting_connection_request_debits_balance_and_logs_history()
    {
        $sender = User::factory()->create();
        $sender->connection()->updateOrCreate(['user_id' => $sender->id], ['connection' => 10]);

        $receiver = User::factory()->create();
        $receiver->connection()->updateOrCreate(['user_id' => $receiver->id], ['connection' => 10]);

        // Sender sends request (creates PENDING)
        $sender->sendConnectionRequest($receiver);

        // Reset history for clarity/isolation or just check specifically
        ConnectionHistory::query()->delete();

        // Receiver accepts request (technically calls sendConnectionRequest back)
        $receiver->sendConnectionRequest($sender);

        // Assert Receiver Balance -1 (10 -> 9)
        $this->assertEquals(9, $receiver->connection->fresh()->connection);

        // Assert History Log for Accepter
        $this->assertDatabaseHas('connection_histories', [
            'user_id' => $receiver->id,
            'amount' => -1,
            'type' => 'connection_request_accepted',
        ]);

        // Verify status is ACCEPTED
        $this->assertTrue($sender->isConnected($receiver->id));
    }

    public function test_insufficient_balance_prevents_action()
    {
        $sender = User::factory()->create();
        // Ensure connection exists and is 0 (User observer adds one by default usually)
        $sender->connection()->updateOrCreate(['user_id' => $sender->id], ['connection' => 0]);

        $receiver = User::factory()->create();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient connections balance');

        $sender->sendConnectionRequest($receiver);
    }

    public function test_connection_history_page_renders()
    {
        $user = User::factory()->create();
        $user->connectionHistory()->create([
            'amount' => 5,
            'type' => 'test',
            'description' => 'test desc'
        ]);

        $this->actingAs($user)
            ->get(route('connection.history'))
            ->assertOk()
            ->assertSee('test desc');
    }
}
