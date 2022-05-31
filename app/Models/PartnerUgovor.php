<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PartnerUgovor
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PartnerUgovor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PartnerUgovor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PartnerUgovor query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_partner
 * @property int $id_ugovor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PartnerUgovor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartnerUgovor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartnerUgovor whereIdPartner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartnerUgovor whereIdUgovor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PartnerUgovor whereUpdatedAt($value)
 */
class PartnerUgovor extends Model
{
    use HasFactory;

    protected $table = "partner_ugovor";

    protected $guarded = ['id', 'id_partner', 'id_ugovor'];
}
