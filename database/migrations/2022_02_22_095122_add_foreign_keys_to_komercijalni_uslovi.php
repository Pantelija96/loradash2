<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToKomercijalniUslovi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('komercijalni_uslovi', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_stavka_fakture')->references('id')->on('stavka_fakture');
            $table->foreign('id_ugovor')->references('id')->on('ugovor');
            $table->foreign('id_vrsta_senzora')->references('id')->on('vrsta_senzora');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('komercijalni_uslovi', function (Blueprint $table) {
            //
        });
    }
}
