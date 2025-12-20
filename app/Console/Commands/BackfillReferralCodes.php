<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\User;

class BackfillReferralCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referrals:backfill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate referral codes for users who do not have one.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = \App\Models\User::whereNull('referral_code')->get();
        $count = 0;

        $this->info("Found {$users->count()} users without referral codes.");

        foreach ($users as $user) {
            $prefix = strtoupper(substr(str_replace(' ', '', $user->name ?? 'USER'), 0, 3));
            if (strlen($prefix) < 3) {
                $prefix = str_pad($prefix, 3, 'X');
            }

            // Try to generate a unique code
            do {
                $code = $prefix . rand(100, 999) . strtoupper(Str::random(2));
            } while (\App\Models\User::where('referral_code', $code)->exists());

            $user->update(['referral_code' => $code]);
            $count++;
            $this->line("Generated code {$code} for user ID: {$user->id}");
        }

        $this->info("Successfully backfilled {$count} referral codes.");
    }
}
