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
        Schema::create('connected', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('connected_user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->enum('status', ['PENDING', 'ACCEPTED', 'REJECTED'])
                ->default('PENDING')
                ->comment('PENDING: Connection request sent, ACCEPTED: Connection request accepted, REJECTED: Connection request rejected');

            $table->primary(['user_id', 'connected_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connected');
    }
};
