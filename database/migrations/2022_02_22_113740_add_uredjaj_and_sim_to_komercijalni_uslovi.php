<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUredjajAndSimToKomercijalniUslovi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('komercijalni_uslovi', function (Blueprint $table) {
            $table->boolean('uredjaj')->default(false);
            $table->boolean('sim_kartica')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('komercijalni_sulovi', function (Blueprint $table) {
            //
        });
    }
}
