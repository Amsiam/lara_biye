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
        Schema::table('basic_infos', function (Blueprint $table) {
            $table->string('student_id')->nullable()->after('nid');
            $table->string('university')->nullable()->after('student_id');
            $table->boolean('is_student_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basic_infos', function (Blueprint $table) {
            //
        });
    }
};
