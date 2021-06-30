<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class CDR extends Model
{
    private $datumKreiranjaUsluge;
    private $userKojiJeKreirao;
    private $pib;
    private $mb;
    private $nazivFirme; //+direkcija
    private $idSenzora;
    private $nazivSenzora;
    private $kolicinaSenzora;
    private $ukupnaCenaSenzora;

    public function getCdrData(){

    }
}
