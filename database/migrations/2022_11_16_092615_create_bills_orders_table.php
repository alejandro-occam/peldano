<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('date');
            $table->integer('expiration')->default(0);
            $table->integer('paid_out')->default(0);
            $table->double('amount')->default(0);
            $table->double('iva')->default(0);
            $table->string('id_sage')->nullable();
            $table->biginteger('id_order')->unsigned();
            $table->foreign('id_order')->references('id')->on('orders');
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
        Schema::dropIfExists('bills_orders');
    }
}
