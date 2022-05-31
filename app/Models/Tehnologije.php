<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tehnologije
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Tehnologije newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tehnologije newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tehnologije query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $naziv
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $prikazi
 * @method static \Illuminate\Database\Eloquent\Builder|Tehnologije whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tehnologije whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tehnologije whereNaziv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tehnologije wherePrikazi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tehnologije whereUpdatedAt($value)
 */
class Tehnologije extends Model
{
    use HasFactory;

    protected $table = "tehnologije";

    protected $guarded = ['id'];
}
