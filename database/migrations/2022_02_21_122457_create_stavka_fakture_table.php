<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStavkaFaktureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stavka_fakture', function (Blueprint $table) {
            $table->id();
            $table->string('naziv');
            $table->integer('tip_naknade');//1 -> mesecna, 2 -> jednokratna
            $table->float('naknada');
            $table->boolean('zavisi_od_vrste_senzora');
            $table->boolean('uredjaj');
            $table->boolean('sim_kartica');
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
        Schema::dropIfExists('stavka_fakture');
    }
}
