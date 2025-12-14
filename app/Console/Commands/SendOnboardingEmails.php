<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProfileImageReminderMail;
use App\Mail\WelcomeCommunityMail;

class SendOnboardingEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-onboarding-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send onboarding emails based on user registration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Profile Image Reminder (1 day after registration)
        $reminderUsers = User::whereDate('created_at', now()->subDay()->toDateString())
            ->whereHas('basicInfo', function ($query) {
                // Check if image is default or null (adjust based on your default logic)
                $query->where('image', 'default.jpg')
                    ->orWhere('image', 'default.png')
                    ->orWhereNull('image');
            })
            ->get();

        foreach ($reminderUsers as $user) {
            Mail::to($user->email)->send(new ProfileImageReminderMail($user));
            $this->info("Sent Profile Image Reminder to: {$user->email}");
        }

        // 2. Community Welcome (3 days after registration)
        $welcomeUsers = User::whereDate('created_at', now()->subDays(3)->toDateString())->get();

        foreach ($welcomeUsers as $user) {
            Mail::to($user->email)->send(new WelcomeCommunityMail($user));
            $this->info("Sent Community Welcome to: {$user->email}");
        }
    }
}
