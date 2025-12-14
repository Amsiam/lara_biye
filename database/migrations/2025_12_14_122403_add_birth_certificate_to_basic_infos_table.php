<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('basic_infos', function (Blueprint $table) {
            $table->string('birth_certificate')->nullable()->after('nid');
            $table->boolean('is_birth_certificate_verified')->default(false)->after('is_nid_verified');
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
