<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TehnologijeUgovor
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TehnologijeUgovor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TehnologijeUgovor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TehnologijeUgovor query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_tehnologije
 * @property int $id_ugovor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TehnologijeUgovor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TehnologijeUgovor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TehnologijeUgovor whereIdTehnologije($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TehnologijeUgovor whereIdUgovor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TehnologijeUgovor whereUpdatedAt($value)
 */
class TehnologijeUgovor extends Model
{
    use HasFactory;

    protected $table = "tehnologije_ugovor";

    protected $guarded = ['id', 'id_tehnologije', 'id_ugovor'];
}
