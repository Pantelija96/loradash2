<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerUgovorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_ugovor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_partner');
            $table->unsignedBigInteger('id_ugovor');

            $table->timestamps();

            $table->foreign('id_partner')->references('id')->on('partner');
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
        Schema::dropIfExists('partner_ugovor');
    }
}
