<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_name',
        'location',
        'rating',
        'comment',
        'is_approved',
        'is_fake',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_fake' => 'boolean',
        'rating' => 'integer',
    ];
}
