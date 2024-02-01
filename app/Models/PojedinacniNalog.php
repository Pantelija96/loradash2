<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereIdUgovor($id)
 */
class PojedinacniNalog extends Model
{
    use HasFactory;

    protected $table = "pojedinacni_nalozi";

    protected $fillable = ['ime', 'prezime', 'email', 'broj_telefona'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $guarded = ['id'];
}
