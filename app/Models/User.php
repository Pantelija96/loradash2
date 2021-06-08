<?php

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
}
