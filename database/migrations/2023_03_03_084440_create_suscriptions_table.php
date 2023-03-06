<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suscriptions', function (Blueprint $table) {
            $table->id();
            $table->biginteger('id_contact')->unsigned();
            $table->foreign('id_contact')->references('id')->on('contacts');
            $table->biginteger('id_article')->unsigned();
            $table->foreign('id_article')->references('id')->on('articles');
            $table->biginteger('id_calendar')->unsigned();
            $table->foreign('id_calendar')->references('id')->on('calendars_magazines');
            $table->string('num');
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
        Schema::dropIfExists('suscriptions');
    }
}
