<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Uloga
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Uloga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Uloga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Uloga query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $naziv
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $prikazi
 * @method static \Illuminate\Database\Eloquent\Builder|Uloga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Uloga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Uloga whereNaziv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Uloga wherePrikazi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Uloga whereUpdatedAt($value)
 */
class Uloga extends Model
{
    use HasFactory;

    protected $table = "uloga";

    protected $guarded = ['id'];
}
