<?php

namespace Database\Seeders;

use App\Models\Uloga;
use Illuminate\Database\Seeder;

class UlogaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Uloga::insert([
            ['naziv'=>'Administrator'],
            ['naziv'=>'Administrator podrške'],
            ['naziv'=>'Operater']
        ]);
    }
}
