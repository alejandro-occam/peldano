<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsBillsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills_orders', function (Blueprint $table) {
            //
            $table->string('observations')->nullable()->after('way_to_pay');
            $table->string('num_order')->nullable()->after('observations');
            $table->string('internal_observations')->nullable()->after('num_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bills_orders', function (Blueprint $table) {
            //
        });
    }
}
