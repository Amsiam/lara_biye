<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerExpectationsTable extends Migration
{
    public function up()
    {
        Schema::create('partner_expectations', function (Blueprint $table) {
            $table->id();
            $table->string('general_requirement')->nullable();
            $table->string('age')->nullable();
            $table->float('height_from')->nullable();
            $table->float('height_to')->nullable();
            $table->float('weight_from')->nullable();
            $table->float('weight_to')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('with_children_acceptables')->nullable();
            $table->string('country_of_residence')->nullable();
            $table->string('religion')->nullable();
            $table->string('caste_sect')->nullable();
            $table->string('sub_caste')->nullable();
            $table->string('education')->nullable();
            $table->string('profession')->nullable();
            $table->string('drinking_habits')->nullable();
            $table->string('smoking_habits')->nullable();
            $table->string('diet')->nullable();
            $table->string('body_type')->nullable();
            $table->string('personal_value')->nullable();
            $table->boolean('manglik')->default(false)->nullable();
            $table->string('any_disability')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->string('family_value')->nullable();
            $table->string('prefered_country')->nullable();
            $table->string('prefered_state')->nullable();
            $table->string('prefered_status')->nullable();
            $table->string('complexion')->nullable();
            $table->boolean('is_shown')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('partner_expectations');
    }
}
