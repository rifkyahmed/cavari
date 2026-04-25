<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourceRequest extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'product_details',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
