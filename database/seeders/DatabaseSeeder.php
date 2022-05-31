<?php

namespace Database\Seeders;

use App\Models\LokacijaApp;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LokacijaAppSeeder::class,
            NazivServisaSeeder::class,
            PartnerSeeder::class,
            StavkaFaktureSeeder::class,
            TehnologijeSeeder::class,
            TipServisaSeeder::class,
            TipUgovoraSeeder::class,
            UlogaSeeder::class,
            VrstaSenzoraSeeder::class
        ]);
    }
}
