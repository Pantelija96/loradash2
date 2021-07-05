<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kategorija extends Model
{
    private $tabela = "kategorija";

    public $idKategorija;
    public $nazivKategorije;

    public function getAll(){
        return DB::table($this->tabela)
            ->get();
    }
}
