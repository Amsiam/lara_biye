<?php

namespace App\Console\Commands;

use App\Mail\ProfileCompletionReminder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendProfileCompletionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:send-completion-reminders {--threshold=70 : Profile completion threshold percentage} {--delay=2 : Seconds to wait between emails} {--limit=100 : Maximum emails to send per run}';

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
        $delay = (int) $this->option('delay');
        $limit = (int) $this->option('limit');

        $this->info("Sending up to {$limit} profile completion reminders (below {$threshold}%, {$delay}s delay)...");
        Log::info("Profile Completion Reminders: Starting", compact('threshold', 'limit', 'delay'));

        $query = User::where('is_admin', false)
            ->whereNotNull('email_verified_at')
            ->where(function ($q) use ($threshold) {
                $q->whereNull('profile_completion')
                  ->orWhere('profile_completion', '<', $threshold);
            })
            ->orderByRaw('last_reminded_at IS NOT NULL, last_reminded_at ASC')
            ->select(['id', 'name', 'email', 'profile_completion', 'last_reminded_at']);

        $total = min($query->count(), $limit);
        $sentCount = 0;

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $query->chunk(50, function ($users) use (&$sentCount, $progressBar, $delay, $limit) {
                foreach ($users as $user) {
                    if ($sentCount >= $limit) {
                        return false;
                    }

                    $completion = $user->profile_completion ?? 0;
                    try {
                        Mail::to($user->email)->send(new ProfileCompletionReminder($user, $completion));
                        $user->updateQuietly(['last_reminded_at' => now()]);
                        $sentCount++;
                        Log::info("Profile Reminder: Sent to {$user->email} - {$completion}%");
                    } catch (\Exception $e) {
                        $this->newLine();
                        $this->error("✗ Failed to send to {$user->email}: {$e->getMessage()}");
                        Log::error("Profile Reminder: Failed for {$user->email}: {$e->getMessage()}");
                    }

                    $progressBar->advance();

                    if ($delay > 0 && $sentCount < $limit) {
                        sleep($delay);
                    }
                }
            });

        $progressBar->finish();
        $this->newLine(2);

        $this->info("═══════════════════════════════════════");
        $this->info("Profile Completion Reminder Summary");
        $this->info("═══════════════════════════════════════");
        $this->line("Emails sent: {$sentCount} / {$limit} (daily limit)");
        $this->line("Delay between emails: {$delay}s");
        $this->info("═══════════════════════════════════════");

        Log::info("Profile Completion Reminders Summary", [
            'emails_sent' => $sentCount,
            'daily_limit' => $limit,
            'threshold' => $threshold,
        ]);

        return Command::SUCCESS;
    }
}
