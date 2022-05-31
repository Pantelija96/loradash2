<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VrstaSenzora
 *
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzora newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzora newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzora query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $naziv
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $prikazi
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzora whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzora whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzora whereNaziv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzora wherePrikazi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VrstaSenzora whereUpdatedAt($value)
 */
class VrstaSenzora extends Model
{
    use HasFactory;

    protected $table = "vrsta_senzora";

    protected $guarded = ['id'];
}
