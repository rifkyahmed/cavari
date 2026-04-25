<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_type', // percentage, fixed
        'discount_value',
        'expiry_date',
        'usage_limit',
        'user_email',
        'is_birthday_offer',
        'is_popup_seen',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'discount_value' => 'decimal:2',
        'is_birthday_offer' => 'boolean',
        'is_popup_seen' => 'boolean',
    ];
}
