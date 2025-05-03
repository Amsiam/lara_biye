<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasicInfosTable extends Migration
{
    public function up()
    {
        Schema::create('basic_infos', function (Blueprint $table) {
            $table->id();
            $table->date('dob');
            $table->enum('gender', ['MALE', 'FEMALE', 'UNSPECIFIED'])->default('UNSPECIFIED');
            $table->enum('marital_status', allowed: ['UNMARRIED', 'MARRIED', 'DIVORCED', 'WIDOWED'])->default('UNMARRIED');
            $table->integer('noc')->default(0);
            $table->float('height')->default(0);
            $table->float('weight')->default(0);
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->text('bio');
            $table->enum('on_behalf', ['SELF', 'SON', 'DAUGHTER', 'BROTHER', 'SISTER', 'FRIEND', 'RELATIVE', 'OTHER'])->default('SELF');
            $table->enum('religion', ['ISLAM', 'HINDU', 'CHRISTIAN', 'BUDDHIST', 'OTHER'])->default('ISLAM');
            $table->string('image')->default('default.jpg');
            $table->enum('image_privacy', ['ALL', 'FREE', 'PREMIUM', 'NONE'])->default('ALL');
            $table->string('nid')->nullable();
            $table->boolean('is_nid_verified')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('basic_infos');
    }
}
