<?php

namespace Database\Seeders;

use App\Models\TipUgovora;
use Illuminate\Database\Seeder;

class TipUgovoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipUgovora::insert([
            ['naziv' => 'PoC', 'prikazi' => true],
            ['naziv' => 'Pilot projekat', 'prikazi' => true],
            ['naziv' => 'Komercijalni', 'prikazi' => true]
        ]);
    }
}
