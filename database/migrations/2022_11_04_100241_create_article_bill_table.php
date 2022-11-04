<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_bills', function (Blueprint $table) {
            $table->id();
            $table->biginteger('id_service')->unsigned();
            $table->foreign('id_service')->references('id')->on('services');
            $table->biginteger('id_bill')->unsigned();
            $table->foreign('id_bill')->references('id')->on('bills');
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
        Schema::dropIfExists('articles_bills');
    }
}
