<?php

namespace Database\Seeders;

use App\Models\NazivServisa;
use Illuminate\Database\Seeder;

class NazivServisaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NazivServisa::insert([
            ['naziv' => 'Daljinsko merenje potrošnje gasa', 'prikazi' => true],
            ['naziv' => 'Digitalni voćnjaci i vinogradi', 'prikazi' => true],
        ]);
    }
}
