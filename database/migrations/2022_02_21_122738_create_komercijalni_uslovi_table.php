<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomercijalniUsloviTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komercijalni_uslovi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_stavka_fakture');
            $table->unsignedBigInteger('id_ugovor');
            $table->unsignedBigInteger('id_vrsta_senzora')->nullable();

            $table->date('datum_pocetak');
            $table->date('datum_kraj');
            $table->float('naknada');
            $table->integer('status'); // 1 - na | 2 - Aktivni | 3 - Neaktivni
            $table->integer('min');
            $table->integer('max');
            $table->boolean('obrisana')->default(false);
            $table->date('datum_brisanja')->default(date('Y-m-d H:i:s'));
            $table->unsignedBigInteger('id_user_obrisao');

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
        Schema::dropIfExists('komercijalni_uslovi');
    }
}
