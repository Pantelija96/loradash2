<?php

namespace Database\Seeders;

use App\Models\TipServisa;
use Illuminate\Database\Seeder;

class TipServisaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipServisa::insert([
            ['naziv' => 'Telemetrija', 'prikazi' => true],
            ['naziv' => 'Poljoprivreda', 'prikazi' => true]
        ]);
    }
}
