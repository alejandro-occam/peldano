<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batchs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('english_name')->nullable();
            $table->string('nomenclature');
            $table->double('pvp')->default(0);
            $table->boolean('is_exempt')->default(1);
            $table->biginteger('id_chapter')->unsigned();
            $table->foreign('id_chapter')->references('id')->on('chapters');
            $table->string('id_sage')->nullable();
            $table->string('id_family_sage')->nullable();
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
        Schema::dropIfExists('batchs');
    }
}
