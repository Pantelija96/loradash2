<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVrstaSenzoraUgovorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vrsta_senzora_ugovor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_vrsta_senzora');
            $table->unsignedBigInteger('id_ugovor');

            $table->timestamps();

            $table->foreign('id_vrsta_senzora')->references('id')->on('vrsta_senzora');
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
        Schema::dropIfExists('vrsta_senzora_ugovor');
    }
}
