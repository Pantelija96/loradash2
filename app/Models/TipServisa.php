<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TipServisa
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TipServisa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipServisa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipServisa query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $naziv
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $prikazi
 * @method static \Illuminate\Database\Eloquent\Builder|TipServisa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipServisa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipServisa whereNaziv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipServisa wherePrikazi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipServisa whereUpdatedAt($value)
 */
class TipServisa extends Model
{
    use HasFactory;

    protected $table = "tip_servisa";

    protected $guarded = ['id'];
}
