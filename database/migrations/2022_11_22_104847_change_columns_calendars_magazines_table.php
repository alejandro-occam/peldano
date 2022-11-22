<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsCalendarsMagazinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calendars_magazines', function (Blueprint $table) {
            //
            $table->string('topics')->nullable()->change();
            $table->string('drafting')->nullable()->change();
            $table->string('commercial')->nullable()->change();
            $table->string('output')->nullable()->change();
            $table->string('billing')->nullable()->change();
            $table->string('front_page')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calendars_magazines', function (Blueprint $table) {
            //
        });
    }
}
