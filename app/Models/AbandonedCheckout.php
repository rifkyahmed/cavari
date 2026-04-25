<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonedCheckout extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'cart_data',
        'cart_total',
        'reminder_sent_at',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'reminder_sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getReminderSentAttribute(): bool
    {
        return !is_null($this->reminder_sent_at);
    }
}
