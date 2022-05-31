<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUredjajAndSimFromStavkaFakture extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stavka_fakture', function (Blueprint $table) {
            $table->dropColumn('uredjaj');
            $table->dropColumn('sim_kartica');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stavka_fakture', function (Blueprint $table) {
            //
        });
    }
}
