<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('id_position')->after('name');
            $table->foreign('id_position')->references('id')->on('positions');
            $table->string('extension')->nullable()->after('id_position');
            $table->string('mobile')->nullable()->after('extension');
            $table->string('discharge_date')->nullable()->after('mobile');
            $table->string('commission')->nullable()->after('discharge_date');
            $table->boolean('active')->default(false)->after('commission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
