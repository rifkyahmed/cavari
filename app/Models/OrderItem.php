<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'custom_name',
        'custom_details',
    ];

    protected $casts = [
        'custom_details' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
