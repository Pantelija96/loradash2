<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usluga extends Model
{
    private $tabela = 'usluga';

    public $idUsluga;
    public $idKorisnikSistema;
    public $pib;
    public $mb;
    public $nazivFirmeDirekcije;
    public $naziv;
    public $jednokratnaCena;
    public $placenaJednokratnaCena;
    public $placeniUredjajiJednokratno;
    public $placenaAppJednokratno;
    public $ugovornaObaveza;
    public $probniPeriod;
    public $probniPeriodMeseci;
    public $probniPeriodDana;
    public $datumPotpisaUgovora;
    public $datumPocetkaNaplate;
    public $datumKrajNaplate;
    public $datumAktivacijeSenzora;
    public $garantniRok;
    public $istekaoGarantniRok;

    public function getAll()
    {
        return DB::table($this->tabela)
            ->get();
    }

    public function insertId()
    {
        try {
            return DB::table($this->tabela)
                ->insertGetId([
                    'idKorisnikSistema' => $this->idKorisnikSistema,
                    'cloudIdKupca' => $this->pib,
                    'pib' => $this->pib,
                    'mb' => $this->mb,
                    'nazivFirmeDirekcije' => $this->nazivFirmeDirekcije,
                    'naziv' => $this->naziv,
                    'jednokratnaCena' => $this->jednokratnaCena,
                    'placenaJednokratnaCena' => $this->placenaJednokratnaCena,
                    'placeniUredjajiJednokratno' => $this->placeniUredjajiJednokratno,
                    'placenaAppJednokratno' => $this->placenaAppJednokratno,
                    'ugovornaObaveza' => $this->ugovornaObaveza,
                    'probniPeriod' => $this->probniPeriod,
                    'probniPeriodMeseci' => $this->probniPeriodMeseci,
                    'probniPeriodDana' => $this->probniPeriodDana,
                    'datumPotpisaUgovora' => $this->datumPotpisaUgovora,
                    'datumPocetkaNaplate' => $this->datumPocetkaNaplate,
                    'datumKrajNaplate' => $this->datumKrajNaplate,
                    'datumAktivacijeSenzora' => $this->datumAktivacijeSenzora,
                    'garantniRok' => $this->garantniRok,
                    'istekaoGarantniRok' => $this->istekaoGarantniRok
                ]);
        } catch (\Exception $e) {
            \Log::error('Greska pri ubacivanju nove usluge, greska: ' . $e->getMessage());
        }
    }

    public static function getNumberOfUsers()
    {
        try {
            return DB::table('usluga')
                ->groupBy('pib')
                ->count();
        } catch (\Exception $e) {
            \Log::error('Greska pri dohvatanju broja kupaca, greska: ' . $e->getMessage());
        }
    }

    public static function getNumberOfActiveServices(){
        try {
            return DB::select("SELECT COUNT(*) as brojAktivnihUsluga FROM usluga WHERE NOW() >= `datumPotpisaUgovora` AND NOW() <= `datumKrajNaplate`");
        } catch (\Exception $e) {
            \Log::error('Greska pri brojanju ukupnog broja usluga, greska: ' . $e->getMessage());
        }
    }
}
