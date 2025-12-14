<?php

namespace App\Console\Commands;

use App\Models\Purchase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelPendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:cancel-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel payments that have been pending for more than 2 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting checks for pending payments...');

        $stalePayments = Purchase::query()
            ->whereIn('payment_stage', [Purchase::STAGE_INITIATED, Purchase::STAGE_PENDING])
            ->where('created_at', '<', now()->subDays(2))
            ->get();

        $count = $stalePayments->count();

        if ($count === 0) {
            $this->info('No stale pending payments found.');
            return;
        }

        $this->info("Found {$count} pending payments older than 2 days. Cancelling...");

        foreach ($stalePayments as $payment) {
            try {
                $payment->update([
                    'payment_stage' => Purchase::STAGE_CANCELLED,
                    'status' => Purchase::STATUS_FAILED,
                    'error_message' => 'Automatically cancelled due to timeout (2 days)',
                ]);
                $this->info("Cancelled Payment ID: {$payment->id}");
            } catch (\Exception $e) {
                $this->error("Failed to cancel Payment ID: {$payment->id}. Error: " . $e->getMessage());
                Log::error("Failed to auto-cancel payment {$payment->id}: " . $e->getMessage());
            }
        }

        $this->info('Pending payments check completed.');
    }
}
