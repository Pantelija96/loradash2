<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePojedinacniNaloziTableAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pojedinacni_nalozi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ugovor');
            $table->string('ime')->nullable();
            $table->string('prezime')->nullable();
            $table->string('email')->nullable();
            $table->string('broj_telefona')->nullable();
            $table->timestamps();

            $table->foreign('id_ugovor')->references('id')->on('ugovor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pojedinacni_nalozi_table_again');
    }
}
