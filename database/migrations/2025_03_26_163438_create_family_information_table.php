<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyInformationTable extends Migration
{
    public function up()
    {
        Schema::create('family_information', function (Blueprint $table) {
            $table->id();
            $table->string('father')->nullable();
            $table->string('mother')->nullable();
            $table->string('brother')->nullable();
            $table->string('sister')->nullable();
            $table->boolean('is_shown')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('family_information');
    }
}
