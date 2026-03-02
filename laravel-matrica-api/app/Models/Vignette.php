<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vignette extends Model
{
    protected $fillable = [
        'vehicle_id',
        'type',
        'category',
        'region',
        'year',
        'valid_from',
        'valid_to',
    ];
}
