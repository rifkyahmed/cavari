<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    protected $fillable = [
        'code', 'balance', 'initial_balance', 'sender_name', 'sender_email',
        'recipient_name', 'recipient_email', 'message', 'expiry_date', 'is_active', 'user_id'
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
        'balance' => 'decimal:2',
        'initial_balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function transactions()
    {
        return $this->hasMany(GiftCardTransaction::class);
    }
}
