<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackfillProfileCompletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:backfill-completion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill profile_completion for all users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = \App\Models\User::all();
        $count = 0;

        $this->info("Found {$users->count()} users. Starting backfill...");

        foreach ($users as $user) {
            $percentage = $user->updateProfileCompletion();
            $this->line("User {$user->id}: {$percentage}%");
            $count++;
        }

        $this->info("Completed. Backfilled {$count} users.");
    }
}
