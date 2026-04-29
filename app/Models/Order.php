<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Tugotan ang Laravel nga mo-save niini nga mga columns
    protected $fillable = [
        'order_id',
        'customer_name',
        'customer_email',
        'fuel_type',
        'liters',
        'pickup_time',
        'status'
    ];
}
