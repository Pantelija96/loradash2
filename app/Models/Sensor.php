<?php

/*catch(\Exception $e){
  \Log::error('Greska pri , greska: '.$e->getMessage());
}*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Sensor extends Model
{
    private $tabela = 'senzor';

    public $idSenzor;
    public $idKorisnikSistema;
    public $naziv;
    public $opis;
    public $komadaNaLageru;
    public $nabavnaCena;
    public $prodajnaCena;
    public $cenaSenzoraGR;
    public $cenaSenzoraVanGR;
    public $cenaAppGR;
    public $cenaAppVanGR;
    public $cenaServisaAktivan;
    public $cenaServisaNeaktivan;
    public $tehnickaPodrska;
    public $idKategorija;

    public static function getAll(){
      try{
        return DB::table('senzor')
          ->get();
      }
      catch(\Exception $e){
        \Log::error('Greska pri dohvatanju svih senzora, greska: '.$e->getMessage());
      }
    }

    public function insertSenzor(){
      try{
        return DB::table($this->tabela)
          ->insert([
            'idKorisnikSistema' => $this->idKorisnikSistema,
            'naziv' => $this->naziv,
            'opis' => $this->opis,
            'komadaNaLageru' => $this->komadaNaLageru,
            'nabavnaCena' => $this->nabavnaCena,
            'prodajnaCena' => $this->prodajnaCena,
            'cenaSenzoraGR' => $this->cenaSenzoraGR,
            'cenaSenzoraVanGR' => $this->cenaSenzoraVanGR,
            'cenaAppGR' => $this->cenaAppGR,
            'cenaAppVanGR'=> $this->cenaAppVanGR,
            'cenaServisaAktivan' => $this->cenaServisaAktivan,
            'cenaServisaNeaktivan' => $this->cenaServisaNeaktivan,
            'tehnickaPodrska' => $this->tehnickaPodrsa,
            'idKategorija' => $this->idKategorija
          ]);
      }
      catch(\Exception $e){
        \Log::error('Greska pri insertu novog senzora, greska: '.$e->getMessage());
      }
    }
}
