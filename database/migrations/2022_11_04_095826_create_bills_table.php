<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->integer('id_bill_internal')->default(0);
            $table->double('amount')->default(0);
            $table->string('date');
            $table->string('observations')->nullable();
            $table->string('num_order')->nullable();
            $table->string('internal_observations')->nullable();
            $table->integer('way_to_pay')->default(0);
            $table->integer('expiration')->default(0);
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
        Schema::dropIfExists('bills');
    }
}
