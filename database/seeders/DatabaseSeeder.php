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

    }
}
