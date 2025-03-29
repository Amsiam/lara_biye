<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationCareersTable extends Migration
{
    public function up()
    {
        Schema::create('education_careers', function (Blueprint $table) {
            $table->id();
            $table->string('highest_education', 100)->nullable();
            $table->string('employed_in', 100)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->integer('annual_income')->nullable();
            $table->boolean('is_shown')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('education_careers');
    }
}
