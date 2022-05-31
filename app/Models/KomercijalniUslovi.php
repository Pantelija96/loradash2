<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\KomercijalniUslovi
 *
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_user
 * @property int $id_stavka_fakture
 * @property int $id_ugovor
 * @property int|null $id_vrsta_senzora
 * @property string $datum_pocetak
 * @property string $datum_kraj
 * @property float $naknada
 * @property int $status
 * @property int $min
 * @property int $max
 * @property int $obrisana
 * @property string $datum_brisanja
 * @property int $id_user_obrisao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $uredjaj
 * @property int $sim_kartica
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereDatumBrisanja($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereDatumKraj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereDatumPocetak($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereIdStavkaFakture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereIdUgovor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereIdUserObrisao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereIdVrstaSenzora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereNaknada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereObrisana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereSimKartica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KomercijalniUslovi whereUredjaj($value)
 */
class KomercijalniUslovi extends Model
{
    use HasFactory;

    protected $table = "komercijalni_uslovi";

    protected $guarded = ['id'];

    public function getStavkaFakture(){
        return $this->belongsTo(StavkaFakture::class, 'id_stavka_fakture');
    }

    public function getVrstaSenzora(){
        return $this->belongsTo(VrstaSenzora::class, 'id_vrsta_senzora');
    }

}
