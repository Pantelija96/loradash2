<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUgovorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ugovor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_tip_ugovora');
            $table->unsignedBigInteger('id_tip_servisa');
            $table->unsignedBigInteger('id_naziv_servisa');
            $table->unsignedBigInteger('id_lokacija_app');
            $table->unsignedBigInteger('connectivity_plan');

            $table->string('ip_adresa')->nullable();
            $table->string('naziv_servera')->nullable();
            $table->string('naziv_ugovra');
            $table->string('broj_ugovora');
            $table->dateTime('datum_potpisivanja')->default(date('Y-m-d H:i:s'));
            $table->integer('ugovorna_obaveza');
            $table->string('zbirni_racun');
            $table->text('napomena')->nullable();
            $table->string('id_kupac');
            $table->string('naziv_kupac');
            $table->string('pib');
            $table->string('mb');
            $table->string('segment');
            $table->string('email');
            $table->string('telefon');

            $table->boolean('dekativiran')->default(false);

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
        Schema::dropIfExists('ugovor');
    }
}
