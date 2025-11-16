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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('payment_method')->default('bkash');
            $table->string('status')->default('completed');
            $table->integer('connections_purchased');
            $table->text('payment_response')->nullable(); // Store full bKash response as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
