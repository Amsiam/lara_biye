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
        Schema::table('family_information', function (Blueprint $table) {
            $table->after('sister', function ($t) {
                $t->string('father_occupation', 255)->default('')->nullable();
                $t->string('mother_occupation', 255)->default('')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family', function (Blueprint $table) {
            //
        });
    }
};
