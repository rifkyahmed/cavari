<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('ADMIN_NAME', 'Admin User'),
                'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
                'role' => 'admin'
            ]
        );

        // Customer
        User::updateOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Doe',
                'password' => bcrypt('password'),
                'role' => 'customer'
            ]
        );

        // Categories
        $categories = [
            ['name' => 'Rings', 'image' => 'images/ring-1.png'],
            ['name' => 'Necklaces', 'image' => 'images/necklace-1.png'],
            ['name' => 'Earrings', 'image' => 'images/earrings-1.png'],
            ['name' => 'Bracelets', 'image' => 'images/bracelet-1.png'],
            ['name' => 'Loose Gems', 'image' => 'images/ruby.png'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => 'Beautiful ' . $cat['name'] . ' for every occasion.',
                    'image' => $cat['image']
                ]
            );
        }

        $ringCat = Category::where('slug', 'rings')->first();
        $neckCat = Category::where('slug', 'necklaces')->first();
        $earringCat = Category::where('slug', 'earrings')->first();
        $braceletCat = Category::where('slug', 'bracelets')->first();
        $gemCat = Category::where('slug', 'loose-gems')->first();

        // 1. Rings
        Product::updateOrCreate(
            ['slug' => 'midnight-star-sapphire-ring'],
            [
                'category_id' => $ringCat->id,
                'name' => 'Midnight Star',
                'description' => 'A rare Ceylon blue sapphire set in a handcrafted platinum band. The deep blue hue captures the essence of the midnight sky.',
                'price' => 8900.00,
                'stock' => 5,
                'gemstone_type' => 'Sapphire',
                'images' => ['images/ring-1.png'],
                'is_featured' => true,
                'product_type' => 'jewelry',
                'color' => 'Blue',
                'weight' => 2.5,
                'shape' => 'Oval',
                'treatment' => 'Heated',
                'metal' => 'Platinum',
                'original_price' => 9500.00,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'crimson-vow-ruby-ring'],
            [
                'category_id' => $ringCat->id,
                'name' => 'Crimson Vow',
                'description' => 'An intense Burmese ruby ring, symbolizing passion and vitality. Surrounded by a halo of pave diamonds.',
                'price' => 12500.00,
                'stock' => 2,
                'gemstone_type' => 'Ruby',
                'images' => ['images/reviews-ring.png'], 
                'is_featured' => true,
                'product_type' => 'jewelry',
                'color' => 'Red',
                'weight' => 3.1,
                'shape' => 'Cushion',
                'treatment' => 'Unheated',
                'metal' => 'Rose Gold',
            ]
        );

        // 2. Necklaces
        Product::updateOrCreate(
            ['slug' => 'celestial-drop-pendant'],
            [
                'category_id' => $neckCat->id,
                'name' => 'Celestial Drop',
                'description' => 'A solitary pear-shaped diamond suspended from a delicate 18k white gold chain. Pure elegance defined.',
                'price' => 12500.00,
                'stock' => 3,
                'gemstone_type' => 'Diamond',
                'images' => ['images/necklace-1.png'],
                'is_featured' => true,
                'product_type' => 'jewelry',
                'color' => 'White',
                'weight' => 1.5,
                'shape' => 'Pear',
                'treatment' => 'None',
                'metal' => 'White Gold',
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'verdant-heart-emerald-necklace'],
            [
                'category_id'   => $neckCat->id,
                'name'          => 'Verdant Heart',
                'description'   => 'A Colombian emerald of exceptional clarity, cut into a heart shape and set in yellow gold.',
                'price'         => 15200.00,
                'stock'         => 1,
                'gemstone_type' => 'Emerald',
                'images'        => ['images/necklace-1.png'], // Reusing for demo, ideally different
                'is_featured'   => false,
                'product_type'  => 'jewelry',
                'color'         => 'Green',
                'weight'        => 2.8,
                'shape'         => 'Heart',
                'treatment'     => 'Oil Treated',
                'metal'         => 'Gold',
            ]
        );

        // 3. Earrings
        Product::updateOrCreate(
            ['slug' => 'solar-flare-ruby-earrings'],
            [
                'category_id' => $earringCat->id,
                'name' => 'Solar Flare',
                'description' => 'Radiant ruby stud earrings that capture the light with every movement. Set in 18k rose gold.',
                'price' => 4200.00,
                'stock' => 8,
                'gemstone_type' => 'Ruby',
                'images' => ['images/earrings-1.png'],
                'is_featured' => true,
                'product_type' => 'jewelry',
                'color' => 'Red',
                'weight' => 1.2,
                'shape' => 'Round',
                'treatment' => 'Heated',
                'metal' => 'Rose Gold',
                'original_price' => 4500.00,
            ]
        );

        // 4. Bracelets
        Product::updateOrCreate(
            ['slug' => 'eternal-link-diamond-bracelet'],
            [
                'category_id' => $braceletCat->id,
                'name' => 'Eternal Link',
                'description' => 'A continuous line of brilliant-cut diamonds set in platinum. A timeless piece for the modern collector.',
                'price' => 6800.00,
                'stock' => 4,
                'gemstone_type' => 'Diamond',
                'images' => ['images/bracelet-1.png'],
                'is_featured' => true,
                'product_type' => 'jewelry',
                'color' => 'White',
                'weight' => 4.5,
                'shape' => 'Round',
                'treatment' => 'None',
                'metal' => 'Platinum',
            ]
        );

        // 5. Loose Gems
        Product::updateOrCreate(
            ['slug' => 'royal-burma-ruby-loose'],
            [
                'category_id'   => $gemCat->id,
                'name'          => 'Royal Burma Ruby',
                'description'   => 'Unheated, pigeon-blood ruby of the finest quality. A collector’s dream.',
                'price'         => 25000.00,
                'stock'         => 1,
                'gemstone_type' => 'Ruby',
                'images'        => ['images/ruby.png'],
                'is_featured'   => true,
                'product_type'  => 'gem',
                'color'         => 'Red',
                'weight'        => 3.05,
                'shape'         => 'Cushion',
                'treatment'     => 'Unheated',
            ]
        );

        Product::create([
            'category_id' => $gemCat->id,
            'name' => 'Ceylon Blue Sapphire',
            'slug' => 'ceylon-blue-sapphire-loose',
            'description' => 'Velvety blue sapphire, 4.20 carats. Perfectly cut to maximize brilliance.',
            'price' => 8900.00,
            'stock' => 2,
            'gemstone_type' => 'Sapphire',
            'images' => ['images/sapphire.png'],
            'is_featured' => true,
            'product_type' => 'gem',
            'color' => 'Blue',
            'weight' => 4.20,
            'shape' => 'Oval',
            'treatment' => 'Heated',
        ]);

        Product::create([
            'category_id' => $gemCat->id,
            'name' => 'Zambian Emerald',
            'slug' => 'zambian-emerald-loose',
            'description' => 'Vivid green emerald with minor oil. A stone of growth and renewal.',
            'price' => 15200.00,
            'stock' => 1,
            'gemstone_type' => 'Emerald',
            'images' => ['images/emerald.png'],
            'is_featured' => true,
            'product_type' => 'gem',
            'color' => 'Green',
            'weight' => 2.15,
            'shape' => 'Emerald',
            'treatment' => 'Oil Treated',
        ]);

        Product::create([
            'category_id' => $gemCat->id,
            'name' => 'Pink Tourmaline',
            'slug' => 'pink-tourmaline-loose',
            'description' => 'A vibrant pink tourmaline, offering a playful yet sophisticated pop of color.',
            'price' => 3400.00,
            'stock' => 5,
            'gemstone_type' => 'Tourmaline',
            'images' => ['images/pink_gemstone_hero.png'],
            'is_featured' => false,
            'product_type' => 'gem',
            'color' => 'Pink',
            'weight' => 3.8,
            'shape' => 'Oval',
            'treatment' => 'None',
            'original_price' => 3800.00,
        ]);

        Product::create([
            'category_id' => $gemCat->id,
            'name' => 'Imperial Topaz',
            'slug' => 'imperial-topaz-loose',
            'description' => 'Golden-orange hue characteristic of the finest Imperial Topaz.',
            'price' => 5600.00,
            'stock' => 2,
            'gemstone_type' => 'Topaz',
            'images' => ['images/cavarigem.png'],
            'is_featured' => false,
            'product_type' => 'gem',
            'color' => 'Orange',
            'weight' => 2.4,
            'shape' => 'Peardrop',
            'treatment' => 'Irradiated',
        ]);
        
        Product::create([
            'category_id' => $gemCat->id,
            'name' => 'Aquamarine Crystal',
            'slug' => 'aquamarine-crystal-loose',
            'description' => 'Sea-blue aquamarine with exceptional clarity.',
            'price' => 2100.00,
            'stock' => 3,
            'gemstone_type' => 'Aquamarine',
            'images' => ['images/hero-gem.png'],
            'is_featured' => false,
            'product_type' => 'gem',
            'color' => 'Blue',
            'weight' => 5.2,
            'shape' => 'Round',
            'treatment' => 'None',
        ]);

        Product::create([
            'category_id' => $gemCat->id,
            'name' => 'Grand Emerald',
            'slug' => 'grand-emerald-hero',
            'description' => 'A monolithic emerald piece, suitable for a museum or a grand centerpiece.',
            'price' => 45000.00,
            'stock' => 1,
            'gemstone_type' => 'Emerald',
            'images' => ['images/hero_emerald.png'],
            'is_featured' => true,
            'product_type' => 'gem',
            'color' => 'Green',
            'weight' => 12.5,
            'shape' => 'Emerald',
            'treatment' => 'Oil Treated',
        ]);
    }
}
