<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHobbiesAndInterestsTable extends Migration
{
    public function up()
    {
        Schema::create('hobbies_and_interests', function (Blueprint $table) {
            $table->id();
            $table->string('hobby')->nullable();
            $table->string('interest')->nullable();
            $table->string('music')->nullable();
            $table->string('books')->nullable();
            $table->string('movie')->nullable();
            $table->string('tv_show')->nullable();
            $table->string('sports_show')->nullable();
            $table->string('fitness_activity')->nullable();
            $table->string('cuisine')->nullable();
            $table->string('dress_style')->nullable();
            $table->boolean('is_shown')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hobbies_and_interests');
    }
}
