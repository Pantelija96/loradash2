<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereIdUgovor($id)
 */
class IP extends Model
{
    use HasFactory;

    protected $table = "ips";

    protected $guarded = ['id'];
}
