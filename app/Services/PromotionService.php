<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Promotion;

class PromotionService
{
    public static function ensureSynced()
    {
        $lastSync = cache('last_promotion_sync_time');
        // Sync if never synced or if it's been more than 60 minutes
        // This prevents the site from hanging on every page load
        if (!$lastSync || now()->diffInMinutes($lastSync) >= 60) {
            self::sync();
            cache(['last_promotion_sync_time' => now()]);
        }
    }

    public static function sync()
    {
        \Illuminate\Support\Facades\DB::transaction(function() {
            // 1. Reset all products to their base price
            $discountedProducts = Product::whereNotNull('original_price')->get();
            foreach ($discountedProducts as $product) {
                $product->price = $product->original_price;
                $product->original_price = null;
                $product->save();
            }

            $activePromotions = Promotion::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where('is_active', true)
                ->get();

            // 3. Apply the best discounts
            foreach ($activePromotions as $promo) {
                $query = Product::query();
                
                if ($promo->type === 'category' && !empty($promo->target_ids)) {
                    $query->whereIn('category_id', $promo->target_ids);
                } elseif ($promo->type === 'product' && !empty($promo->target_ids)) {
                    $query->whereIn('id', $promo->target_ids);
                }
                
                $products = $query->get();
                
                foreach ($products as $product) {
                    if (is_null($product->original_price)) {
                        $basePrice = $product->price;
                        $product->original_price = $basePrice;
                    } else {
                        $basePrice = $product->original_price;
                    }

                    $discountAmount = ($basePrice * $promo->discount_percentage) / 100;
                    $newPrice = max(0, $basePrice - $discountAmount);
                    
                    if ($newPrice < $product->price || $product->price == $basePrice) {
                        $product->price = $newPrice;
                        $product->save();
                    }
                }
            }
        });
    }
}
