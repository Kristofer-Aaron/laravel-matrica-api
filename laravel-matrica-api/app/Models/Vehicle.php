<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'country_code',
        'plate_number',
    ];

    public function vignettes()
    {
        return $this->hasMany(Vignette::class);
    }
}
