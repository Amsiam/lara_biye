<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Payment stage tracking
            $table->string('payment_stage')->after('status')->default('initiated')->comment('initiated, pending, completed, failed, refunded, cancelled');

            // Connection update tracking
            $table->boolean('connections_applied')->after('connections_purchased')->default(false)->comment('Whether connections were added to user account');
            $table->timestamp('connections_applied_at')->nullable()->after('connections_applied');

            // Refund tracking
            $table->boolean('is_refunded')->after('connections_applied_at')->default(false);
            $table->timestamp('refunded_at')->nullable()->after('is_refunded');
            $table->string('refund_transaction_id')->nullable()->after('refunded_at');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('refund_transaction_id');

            // Error tracking
            $table->text('error_message')->nullable()->after('refund_amount')->comment('Error message if payment failed');

            // Payment gateway response tracking
            $table->timestamp('payment_initiated_at')->nullable()->after('error_message');
            $table->timestamp('payment_completed_at')->nullable()->after('payment_initiated_at');

            // Update existing status column default
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'payment_stage',
                'connections_applied',
                'connections_applied_at',
                'is_refunded',
                'refunded_at',
                'refund_transaction_id',
                'refund_amount',
                'error_message',
                'payment_initiated_at',
                'payment_completed_at',
            ]);
        });
    }
};
