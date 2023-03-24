<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersObjetivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_objetives', function (Blueprint $table) {
            $table->id();
            $table->biginteger('id_user')->unsigned();
            $table->foreign('id_user')->references('id')->on('users');
            $table->string('obj_print')->nullable();
            $table->string('obj_dig')->nullable();
            $table->string('obj_eve')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_objetives');
    }
}
