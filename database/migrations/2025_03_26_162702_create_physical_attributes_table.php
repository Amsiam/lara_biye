<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalAttributesTable extends Migration
{
    public function up()
    {
        Schema::create('physical_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('eye_color', 100)->nullable();
            $table->string('hair_color', 100)->nullable();
            $table->string('complexion', 100)->nullable();
            $table->string('body_type', 100)->nullable();
            $table->string('body_art', 100)->nullable();
            $table->string('any_disability', 100)->nullable();
            $table->boolean('is_shown')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('physical_attributes');
    }
}
