<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelStock extends Model
{
    protected $fillable = [
        'fuel_type',
        'quantity',
    ];
}
