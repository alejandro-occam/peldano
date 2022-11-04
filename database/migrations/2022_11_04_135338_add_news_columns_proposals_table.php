<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewsColumnsProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposals', function (Blueprint $table) {
            //
            $table->string('commercial_name')->nullable()->after('discount');
            $table->integer('language')->nullable()->after('commercial_name');
            $table->integer('type_proyect')->nullable()->after('language');
            $table->string('name_proyect')->nullable()->after('type_proyect');
            $table->string('date_proyect')->nullable()->after('name_proyect');
            $table->longText('objetives')->nullable()->after('date_proyect');
            $table->longText('proposal')->nullable()->after('objetives');
            $table->longText('actions')->nullable()->after('proposal');
            $table->longText('observations')->nullable()->after('actions');
            $table->boolean('show_discounts')->default(0)->after('observations');
            $table->boolean('show_inserts')->default(0)->after('show_discounts');
            $table->boolean('show_invoices')->default(0)->after('show_inserts');
            $table->boolean('show_pvp')->default(0)->after('show_invoices');
            $table->integer('sales_possibilities')->nullable()->after('show_pvp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposals', function (Blueprint $table) {
            //
        });
    }
}
