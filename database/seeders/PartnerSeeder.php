<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Partner::insert([
            ['naziv' => 'Teri', 'prikazi' => true],
            ['naziv' => 'BitGear', 'prikazi' => true],
            ['naziv' => 'DunavNet', 'prikazi' => true],
            ['naziv' => 'Comtrade', 'prikazi' => true],
            ['naziv' => 'Konvex', 'prikazi' => true],
            ['naziv' => 'Telegrup', 'prikazi' => true],
        ]);
    }
}
