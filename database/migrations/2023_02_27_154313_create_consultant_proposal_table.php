<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultantProposalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultants_proposals', function (Blueprint $table) {
            $table->id();
            $table->biginteger('id_consultant')->unsigned();
            $table->foreign('id_consultant')->references('id')->on('users');
            $table->biginteger('id_proposal')->unsigned();
            $table->foreign('id_proposal')->references('id')->on('proposals');
            $table->string('percentage');
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
        Schema::dropIfExists('consultants_proposals');
    }
}
