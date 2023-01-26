<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesBillsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_bills_orders', function (Blueprint $table) {
            $table->id();
            $table->biginteger('id_service')->unsigned();
            $table->foreign('id_service')->references('id')->on('services');
            $table->biginteger('id_bill_order')->unsigned();
            $table->foreign('id_bill_order')->references('id')->on('bills_orders');
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
        Schema::dropIfExists('services_bills_orders');
    }
}
