<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'gemstone_type',
        'images',
        'video',
        'is_featured',
        'product_type',
        'color', 
        'weight', 
        'shape', 
        'treatment', 
        'metal', 
        'gold_weight',
        'gem_weight',
        'original_price',
        'is_hidden',
        'origin',
        'special_comments',
        'clarity',
        'size',
        'cost_price',
        'is_atelier',
        'caret_range',
        'gold_cost_price',
        'gem_cost_price',
        'certificate'

    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_hidden' => 'boolean',
        'is_atelier' => 'boolean',

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeDeadStock($query)
    {
        return $query->where('stock', '>', 0)
            ->where('created_at', '<', now()->subDays(90))
            ->whereNotExists(function ($q) {
                $q->select(\Illuminate\Support\Facades\DB::raw(1))
                    ->from('order_items')
                    ->whereRaw('order_items.product_id = products.id')
                    ->where('created_at', '>', now()->subDays(90));
            });
    }

    public function isDeadStock()
    {
        if ($this->stock <= 0) return false;
        if ($this->created_at > now()->subDays(90)) return false;
        
        return !$this->orderItems()
            ->where('created_at', '>', now()->subDays(90))
            ->exists();
    }

    public function isWellMoving()
    {
        // Define well moving as 2 or more sales in the last 30 days
        return $this->orderItems()
            ->where('created_at', '>', now()->subDays(30))
            ->sum('quantity') >= 2;
    }
}
