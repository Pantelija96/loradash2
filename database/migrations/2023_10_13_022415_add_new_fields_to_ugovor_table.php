<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToUgovorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ugovor', function (Blueprint $table) {
            $table->string('ipAdreseZaPropustanjeSaPortovima')->nullable(true);
            $table->integer('brojDodeljenihLicenci')->nullable(true)->default(1);
            $table->boolean('pojedinacniNalozi')->nullable(true);
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
