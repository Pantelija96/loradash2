<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTehnologijeUgovorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tehnologije_ugovor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tehnologije');
            $table->unsignedBigInteger('id_ugovor');

            $table->timestamps();

            $table->foreign('id_tehnologije')->references('id')->on('tehnologije');
            //$table->foreign('id_ugovor')->references('id')->on('ugovor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tehnologije_ugovor');
    }
}
