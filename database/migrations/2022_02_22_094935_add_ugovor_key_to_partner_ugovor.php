<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUgovorKeyToPartnerUgovor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partner_ugovor', function (Blueprint $table) {
            $table->foreign('id_ugovor')->references('id')->on('ugovor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner_ugovor', function (Blueprint $table) {
            //
        });
    }
}
