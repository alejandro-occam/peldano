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
            $table->string('surname')->nullable()->after('name');
            $table->string('user')->nullable()->after('surname');
            $table->unsignedBigInteger('id_position')->nullable()->after('user');
            $table->foreign('id_position')->references('id')->on('positions');
            $table->string('extension')->nullable()->after('id_position');
            $table->string('mobile')->nullable()->after('extension');
            $table->double('commission')->nullable()->after('mobile');
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
