<?php

namespace App\Console\Commands;

use App\Mail\ProfileCompletionReminder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendProfileCompletionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:send-completion-reminders {--threshold=70 : Profile completion threshold percentage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders to users whose profile completion is below the specified threshold';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = (float) $this->option('threshold');

        $this->info("Searching for users with profile completion below {$threshold}%...");

        // Get all non-admin users with verified email
        $users = User::where('is_admin', false)
            ->whereNotNull('email_verified_at')
            ->with([
                'basicInfo',
                'education',
                'physical_attr',
                'location',
                'family',
                'partnerExpectation',
                'personal',
                'lifestyle',
                'hobby',
                'language',
                'spiritualSocial',
                'parmanent',
                'siblingInfo'
            ])
            ->get();

        $sentCount = 0;
        $skippedCount = 0;

        $progressBar = $this->output->createProgressBar($users->count());
        $progressBar->start();

        foreach ($users as $user) {
            $completionPercentage = $user->profileCompletionPercentage();

            if ($completionPercentage < $threshold) {
                try {
                    Mail::to($user->email)->send(new ProfileCompletionReminder($user, $completionPercentage));
                    $sentCount++;
                    $this->newLine();
                    $this->line("✓ Email sent to {$user->name} ({$user->email}) - {$completionPercentage}% complete");
                } catch (\Exception $e) {
                    $this->newLine();
                    $this->error("✗ Failed to send email to {$user->email}: {$e->getMessage()}");
                }
            } else {
                $skippedCount++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("═══════════════════════════════════════");
        $this->info("Profile Completion Reminder Summary");
        $this->info("═══════════════════════════════════════");
        $this->line("Total users checked: {$users->count()}");
        $this->line("Emails sent: {$sentCount}");
        $this->line("Users skipped (>{$threshold}%): {$skippedCount}");
        $this->info("═══════════════════════════════════════");

        return Command::SUCCESS;
    }
}
