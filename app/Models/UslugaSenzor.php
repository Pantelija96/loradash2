<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UslugaSenzor extends Model
{
    private $tabela = "uslugasenzor";

    public $idUslugaSenzor;
    public $idUsluga;
    public $idSenzor;
    public $nabavnaCena;
    public $cenaSenzoraGR;
    public $cenaSenzoraVanGR;
    public $cenaAppGR;
    public $cenaAppVanGR;
    public $cenaServisaAktivnih;
    public $cenaServisaNeaktivnih;
    public $cenaTehnickePodrske;
    public $brojAktivnihSenzora;
    public $brojPovremenoNeaktivnih;
    public $ukupanBrojSenzora;
    public $brojNeaktivnihMeseci;
    public $neaktivniMeseci;

    public function getAll(){
        return DB::table($this->tabela)
            ->get();
    }

    public function insert(){
        try{
            return DB::table($this->tabela)
                ->insert([
                   'idUsluga' => $this->idUsluga,
                   'idSenzor' => $this->idSenzor,
                   'nabavnaCena' => $this->nabavnaCena,
                   'cenaSenzoraGR' => $this->cenaSenzoraGR,
                   'cenaSenzoraVanGR' => $this->cenaSenzoraVanGR,
                   'cenaAppGR' => $this->cenaAppGR,
                   'cenaAppVanGR' => $this->cenaAppVanGR,
                   'cenaServisaAktivnih' => $this->cenaServisaAktivnih,
                   'cenaServisaNeaktivnih' => $this->cenaServisaNeaktivnih,
                   'cenaTehnickePodrske' => $this->cenaTehnickePodrske,
                   'brojAktivnihSenzora' => $this->brojAktivnihSenzora,
                   'brojPovremenoNeaktivnih' => $this->brojPovremenoNeaktivnih,
                   'ukupanBrojSenzora' => $this->ukupanBrojSenzora,
                   'brojNeaktivnihMeseci' => $this->brojNeaktivnihMeseci,
                   'neaktivniMeseci' => $this->neaktivniMeseci
                ]);
        }
        catch(\Exception $e){
            \Log::error('Greska pri insertu Usluga Senzor, greska: '.$e->getMessage());
        }
    }
}
