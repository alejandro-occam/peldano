<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealHubspotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_hubspot', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('id_hubspot');
            $table->biginteger('id_contact')->unsigned();
            $table->foreign('id_contact')->references('id')->on('contacts');
            $table->biginteger('id_company')->unsigned();
            $table->foreign('id_company')->references('id')->on('companies');
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
        Schema::dropIfExists('deal_hubspot');
    }
}
