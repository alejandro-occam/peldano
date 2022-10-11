<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarsMagazinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars_magazines', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('title');
            $table->string('topics');
            $table->string('drafting');
            $table->string('commercial');
            $table->string('output');
            $table->string('billing');
            $table->string('front_page');
            $table->biginteger('id_calendar')->unsigned();
            $table->foreign('id_calendar')->references('id')->on('calendars')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('calendars_magazines');
    }
}
