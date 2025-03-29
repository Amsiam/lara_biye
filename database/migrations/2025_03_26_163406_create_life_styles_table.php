<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLifeStylesTable extends Migration
{
    public function up()
    {
        Schema::create('life_styles', function (Blueprint $table) {
            $table->id();
            $table->string('smoking', 100)->nullable();
            $table->string('drinking', 100)->nullable();
            $table->string('diet', 100)->nullable();
            $table->string('living_with', 100)->nullable();
            $table->boolean('is_shown')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('life_styles');
    }
}
