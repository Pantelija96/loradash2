<?php

namespace Database\Seeders;

use App\Models\LokacijaApp;
use Illuminate\Database\Seeder;

class LokacijaAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LokacijaApp::insert([
            ['naziv' => 'Ts Cloud', 'prikazi' => true],
            ['naziv' => 'Cloud Partner', 'prikazi' => true]
        ]);
    }
}
