<?php

namespace Database\Seeders;

use App\Models\Tehnologije;
use Illuminate\Database\Seeder;

class TehnologijeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tehnologije::insert([
            ['naziv' => 'LoRa', 'prikazi' => true],
            ['naziv' => 'NB IoT', 'prikazi' => true]
        ]);
    }
}
