<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalariesTable extends Migration
{
    public function up()
    {
        Schema::create('galaries', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->enum('image_privacy', ['ALL', 'FREE', 'PREMIUM', 'NONE'])->default('PREMIUM');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('galaries');
    }
}
