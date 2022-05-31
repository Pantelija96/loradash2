<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUgovorKeyToVrstaSenzoraUgovor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vrsta_senzora_ugovor', function (Blueprint $table) {
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
        Schema::table('vrsta_senzora_ugovor', function (Blueprint $table) {
            //
        });
    }
}
