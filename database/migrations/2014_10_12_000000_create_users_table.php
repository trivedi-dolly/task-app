<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('phone')->nullable();
            $table->enum('gender',['male','female'])->default('male')->nullable();
            $table->unsignedBigInteger('education_id')->nullable();
            $table->foreign('education_id')->references('id')->on('education');
            $table->longText('hobbies')->nullable();
            $table->longText('experience')->nullable();
            $table->string('image')->nullable();
            $table->longText('message')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
