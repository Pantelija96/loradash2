<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StavkaFakture
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $naziv
 * @property int $tip_naknade
 * @property float $naknada
 * @property int $zavisi_od_vrste_senzora
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $prikazi
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture whereNaknada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture whereNaziv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture wherePrikazi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture whereTipNaknade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StavkaFakture whereZavisiOdVrsteSenzora($value)
 */
class StavkaFakture extends Model
{
    use HasFactory;

    protected $table = "stavka_fakture";

    protected $guarded = ['id'];
}
