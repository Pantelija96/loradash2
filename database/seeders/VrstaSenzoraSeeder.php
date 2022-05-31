<?php

namespace Database\Seeders;

use App\Models\VrstaSenzora;
use Illuminate\Database\Seeder;

class VrstaSenzoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VrstaSenzora::insert([
            ['naziv' => 'Meteo stanica', 'prikazi' => true],
            ['naziv' => 'Merenje protoka gasa', 'prikazi' => true],
            ['naziv' => 'Pametna brava', 'prikazi' => true],
            ['naziv' => 'Parking senzor', 'prikazi' => true],
            ['naziv' => 'Senzor za merenje buke', 'prikazi' => true],
        ]);
    }
}
