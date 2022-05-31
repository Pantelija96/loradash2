<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VrstaSenzoraUgovor
 *
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzoraUgovor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzoraUgovor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzoraUgovor query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_vrsta_senzora
 * @property int $id_ugovor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzoraUgovor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzoraUgovor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzoraUgovor whereIdUgovor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzoraUgovor whereIdVrstaSenzora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzoraUgovor whereUpdatedAt($value)
 */
class VrstaSenzoraUgovor extends Model
{
    use HasFactory;

    protected $table = "vrsta_senzora_ugovor";

    protected $guarded = ['id', 'id_vrsta_senzora', 'id_ugovor'];
}
