<?php

/*catch(\Exception $e){
  \Log::error('Greska pri loginu korisnika, greska: '.$e->getMessage());
}*/


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class User extends Model
{
    private $tabela = 'korisniksistema';

    public $idKorisnikSistema;
    public $idUloga;
    public $ime;
    public $prezime;
    public $lozinka;
    public $email;
    public $datumRegistracije;
    public $datumPoslednjegLogovanja;

    public static function getAll(){
      try{
        return DB::table('korisniksistema')
          ->join('uloga', 'korisniksistema.idUloga', '=', 'uloga.idUloga')
          ->get();
      }
      catch(\Exception $e){
        \Log::error('Greska pri dohvatanju svih korisnika, greska: '.$e->getMessage());
      }
    }

    private function updateLastLogin($idKorisnikSistema){
      try{
        $resultUpdateLastLogin = DB::table($this->tabela)
          ->where("idKorisnikSistema", '=', $idKorisnikSistema)
          ->update([
            "datumPoslednjegLogovanja" => date('Y-m-d H:i:s', time())
          ]);

          if($resultUpdateLastLogin){
            return true;
          }
          else{
            return false;
          }
      }
      catch(\Exception $e){
        \Log::error('Greska pri update-u poslednjeg logovanja, greska: '.$e->getMessage());
      }
    }

    public function login(){
      try{
        $result = DB::table($this->tabela)
          ->join('uloga', 'korisniksistema.idUloga', '=', 'uloga.idUloga')
          ->where([
            'email' => $this->email,
            'lozinka' => md5($this->lozinka)
          ])
          ->get();
      }
      catch(\Exception $e){
        \Log::error('Greska pri loginu korisnika, greska: '.$e->getMessage());
      }

      if(count($result) == 1){
        if($this->updateLastLogin($result[0]->idKorisnikSistema)){
          return $result[0];
        }
      }
    }

    public function changePassword(){
      try{
        $result = DB::table($this->tabela)
          ->where('idKorisnikSistema', $this->idKorisnikSistema)
          ->update([
            'lozinka' => md5($this->lozinka)
          ]);

          return $result;
      }
      catch(\Exception $e){
        \Log::error('Greska pri izmeni lozinke korisnika, greska: '.$e->getMessage());
      }
    }

    public function insertNewUser(){
      try{
        return DB::table($this->tabela)
          ->insert([
            'idUloga' => 2,
            'ime' => $this->ime,
            'prezime' => $this->prezime,
            'lozinka' => md5($this->lozinka),
            'email' => $this->email,
            'datumRegistracije' => date('Y-m-d h:i:s', time()),
            'datumPoslednjegLogovanja' => date('Y-m-d H:i:s', time())
          ]);
      }
      catch(\Exception $e){
        \Log::error('Greska pri insertu novog korisnika, greska: '.$e->getMessage());
      }
    }

}
