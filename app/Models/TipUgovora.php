<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TipUgovora
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TipUgovora newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipUgovora newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipUgovora query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $naziv
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $prikazi
 * @method static \Illuminate\Database\Eloquent\Builder|TipUgovora whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipUgovora whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipUgovora whereNaziv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipUgovora wherePrikazi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipUgovora whereUpdatedAt($value)
 */
class TipUgovora extends Model
{
    use HasFactory;

    protected $table = "tip_ugovora";

    protected $guarded = ['id'];
}
