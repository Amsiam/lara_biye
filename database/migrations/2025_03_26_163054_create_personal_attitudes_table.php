<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalAttitudesTable extends Migration
{
    public function up()
    {
        Schema::create('personal_attitudes', function (Blueprint $table) {
            $table->id();
            $table->string('affection', 100)->nullable();
            $table->string('humor', 100)->nullable();
            $table->string('political_view', 100)->nullable();
            $table->string('religious_service', 100)->nullable();
            $table->boolean('is_shown')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personal_attitudes');
    }
}
