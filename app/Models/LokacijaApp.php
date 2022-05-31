<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LokacijaApp
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LokacijaApp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LokacijaApp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LokacijaApp query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $naziv
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $prikazi
 * @method static \Illuminate\Database\Eloquent\Builder|LokacijaApp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LokacijaApp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LokacijaApp whereNaziv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LokacijaApp wherePrikazi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LokacijaApp whereUpdatedAt($value)
 */
class LokacijaApp extends Model
{
    use HasFactory;

    protected $table = "lokacija_app";

    protected $guarded = ['id'];
}
