<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_amount',
        'usage_limit',
        'expires_at',
        'status'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'status' => 'boolean',
        'value' => 'decimal:2'
    ];
}
