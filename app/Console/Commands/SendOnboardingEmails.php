<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProfileImageReminderMail;
use App\Mail\WelcomeCommunityMail;
use App\Mail\ProfileCompletionMail;

class SendOnboardingEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:send-completion-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send profile completion reminders to users based on registration date';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $today = now();

        // 1. Profile Image Reminder (Day 2)
        // If registered 1 day ago (so today is the 2nd day)
        $usersDay2 = User::whereDate('created_at', $today->copy()->subDays(1))
            ->whereHas('basicInfo', function ($query) {
                // Check if image is default or null (adjust based on your default logic)
                $query->where('image', 'default.jpg')
                    ->orWhere('image', 'default.png')
                    ->orWhereNull('image');
            })
            ->get();

        foreach ($usersDay2 as $user) {
            // Check if profile image is missing (assuming default image logic)
            // Ideally check filtering logic here. For now, sending to all as per original logic,
            // but normally we check $user->image or similar.
            // The original code didn't check for image existence in the query, so keeping it simple.
            Mail::to($user->email)->send(new ProfileImageReminderMail($user));
        }

        // 2. Welcome Community (Day 3)
        $usersDay3 = User::whereDate('created_at', $today->copy()->subDays(3))->get();

        foreach ($usersDay3 as $user) {
            Mail::to($user->email)->send(new WelcomeCommunityMail($user));
        }

        // 3. Profile Completion Reminders (2, 4, 6 Months)
        $intervals = [
            60 => 'profile_completion_2month',
            120 => 'profile_completion_4month',
            180 => 'profile_completion_6month',
        ];

        foreach ($intervals as $days => $templateKey) {
            // Find users registered exactly $days ago
            $users = User::whereDate('created_at', $today->copy()->subDays($days))
                ->with(['basicInfo', 'education', 'family', 'partnerExpectation']) // Eager load for score calculation
                ->get();

            foreach ($users as $user) {
                // Ensure the User model has a profileCompletionPercentage method
                // and that related models (basicInfo, education, family, partnerExpectation) are properly defined.
                $score = $user->profileCompletionPercentage();

                // If profile is incomplete (< 80% for 2/4 months, < 90% for 6 months)
                $threshold = ($days === 180) ? 90 : 80;

                if ($score < $threshold) {
                    Mail::to($user->email)->send(new ProfileCompletionMail($user, $templateKey));
                }
            }
        }
    }
}
