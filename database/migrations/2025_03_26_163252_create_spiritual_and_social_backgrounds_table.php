<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpiritualAndSocialBackgroundsTable extends Migration
{
    public function up()
    {
        Schema::create('spiritual_and_social_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->string('caste', 100)->nullable();
            $table->string('sub_caste', 100)->nullable();
            $table->string('ethnicity', 100)->nullable();
            $table->string('personal_value', 100)->nullable();
            $table->string('family_value', 100)->nullable();
            $table->string('community_value', 100)->nullable();
            $table->string('family_status', 100)->nullable();
            $table->boolean('manglik')->default(false)->nullable();
            $table->boolean('is_shown')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spiritual_and_social_backgrounds');
    }
}
