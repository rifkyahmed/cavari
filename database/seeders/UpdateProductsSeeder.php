<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class UpdateProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        $colors = ['Red', 'Blue', 'Green', 'Yellow', 'White', 'Pink', 'Purple', 'Orange'];
        $shapes = ['Round', 'Oval', 'Cushion', 'Emerald', 'Pear', 'Marquise', 'Princess'];
        $treatments = ['Heated', 'Unheated', 'Oil Treated', 'Irradiated', 'None'];
        $metals = ['Gold', 'Silver', 'Platinum', 'Rose Gold', 'White Gold'];

        foreach ($products as $product) {
            $categorySlug = $product->category->slug ?? '';
            
            // Determine Product Type
            if ($categorySlug === 'loose-gems') {
                $product->product_type = 'gem';
            } else {
                $product->product_type = 'jewelry';
            }

            // Assign Random Attributes if null
            if (!$product->color) {
                // Try to infer from name or gemstone_type, else random
                if (stripos($product->name, 'Ruby') !== false) $product->color = 'Red';
                elseif (stripos($product->name, 'Sapphire') !== false) $product->color = 'Blue';
                elseif (stripos($product->name, 'Emerald') !== false) $product->color = 'Green';
                else $product->color = $colors[array_rand($colors)];
            }

            if (!$product->shape) {
                $product->shape = $shapes[array_rand($shapes)];
            }

            if (!$product->treatment) {
                $product->treatment = $treatments[array_rand($treatments)];
            }

            if (!$product->weight) {
                $product->weight = rand(50, 500) / 100; // 0.50 to 5.00 carats
            }

            if ($product->product_type === 'jewelry' && !$product->metal) {
                $product->metal = $metals[array_rand($metals)];
            }

            if (!$product->original_price) {
                // 30% chance of having a discount
                if (rand(1, 100) <= 30) {
                    $product->original_price = $product->price * 1.2;
                }
            }

            $product->save();
        }
    }
}
