<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidencyInformationTable extends Migration
{
    public function up()
    {
        Schema::create('residency_information', function (Blueprint $table) {
            $table->id();
            $table->string('birth_country')->default('Bangladesh')->nullable();
            $table->string('residency_country')->default('Bangladesh')->nullable();
            $table->string('citizenship_country')->nullable();
            $table->string('grow_up_country')->nullable();
            $table->string('immigration_status')->nullable();
            $table->boolean('is_shown')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('residency_information');
    }
}
