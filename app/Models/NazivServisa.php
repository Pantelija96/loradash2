<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NazivServisa
 *
 * @method static \Illuminate\Database\Eloquent\Builder|NazivServisa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NazivServisa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NazivServisa query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $naziv
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $prikazi
 * @method static \Illuminate\Database\Eloquent\Builder|NazivServisa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NazivServisa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NazivServisa whereNaziv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NazivServisa wherePrikazi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NazivServisa whereUpdatedAt($value)
 */
class NazivServisa extends Model
{
    use HasFactory;

    protected $table = "naziv_servisa";

    protected $guarded = ['id'];
}
