<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ugovor
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_user
 * @property int $id_tip_ugovora
 * @property int $id_tip_servisa
 * @property int $id_naziv_servisa
 * @property int $id_lokacija_app
 * @property int $connectivity_plan
 * @property string|null $ip_adresa
 * @property string|null $naziv_servera
 * @property string $naziv_ugovra
 * @property string $broj_ugovora
 * @property string $datum_potpisivanja
 * @property int $ugovorna_obaveza
 * @property string $zbirni_racun
 * @property string|null $napomena
 * @property string $id_kupac
 * @property string $naziv_kupac
 * @property string $pib
 * @property string $mb
 * @property string $segment
 * @property string $email
 * @property string $telefon
 * @property int $dekativiran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $kam
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereBrojUgovora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereConnectivityPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereDatumPotpisivanja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereDekativiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereIdKupac($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereIdLokacijaApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereIdNazivServisa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereIdTipServisa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereIdTipUgovora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereIpAdresa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereKam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereMb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereNapomena($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereNazivKupac($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereNazivServera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereNazivUgovra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor wherePib($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereSegment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereTelefon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereUgovornaObaveza($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ugovor whereZbirniRacun($value)
 */
class Ugovor extends Model
{
    use HasFactory;

    protected $table = "ugovor";
    protected $guarded = ['id'];

    public function getUser(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getTipUgovora(){
        return $this->belongsTo(TipUgovora::class, 'id_tip_ugovora');
    }

    public function getTipServisa(){
        return $this->belongsTo(TipServisa::class, 'id_tip_servisa');
    }

    public function getNazivServisa(){
        return $this->belongsTo(NazivServisa::class, 'id_naziv_servisa');
    }

    public function getLokacijaApp(){
        return $this->belongsTo(LokacijaApp::class,'id_lokacija_app');
    }

    public function getTehnologije(){
        return $this->hasMany(TehnologijeUgovor::class,'id_ugovor');
    }
}
