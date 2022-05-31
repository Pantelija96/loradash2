<?php

namespace Database\Seeders;

use App\Models\StavkaFakture;
use Illuminate\Database\Seeder;

class StavkaFaktureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StavkaFakture::insert([
            [
                'naziv' => 'Mesečna naknada za aplikaciju - ',
                'tip_naknade' => '1',
                'naknada' => 0.0,
                'zavisi_od_vrste_senzora' => true,
                'prikazi' => true
            ],
            [
                'naziv' => 'Mesečna naknada za uređaj - ',
                'tip_naknade' => '1',
                'naknada' => 0.0,
                'zavisi_od_vrste_senzora' => true,
                'prikazi' => true
            ],
            [
                'naziv' => 'Mesečna naknada za tehničku podršku',
                'tip_naknade' => '1',
                'naknada' => 0.0,
                'zavisi_od_vrste_senzora' => false,
                'prikazi' => true
            ],
            [
                'naziv' => 'Mesečna naknada za servis - ',
                'tip_naknade' => '1',
                'naknada' => 0.0,
                'zavisi_od_vrste_senzora' => true,
                'prikazi' => true
            ],
            [
                'naziv' => 'Mesečna naknada za instalaciju - ',
                'tip_naknade' => '2',
                'naknada' => 0.0,
                'zavisi_od_vrste_senzora' => true,
                'prikazi' => true
            ],
            [
                'naziv' => 'Mesečna naknada za uređaj - ',
                'tip_naknade' => '2',
                'naknada' => 0.0,
                'zavisi_od_vrste_senzora' => true,
                'prikazi' => true
            ],
        ]);
    }
}
