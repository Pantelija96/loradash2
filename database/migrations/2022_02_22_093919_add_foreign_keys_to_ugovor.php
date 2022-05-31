<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUgovor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ugovor', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_tip_ugovora')->references('id')->on('tip_ugovora');
            $table->foreign('id_tip_servisa')->references('id')->on('tip_servisa');
            $table->foreign('id_naziv_servisa')->references('id')->on('naziv_servisa');
            $table->foreign('id_lokacija_app')->references('id')->on('lokacija_app');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ugovor', function (Blueprint $table) {
            //
        });
    }
}
