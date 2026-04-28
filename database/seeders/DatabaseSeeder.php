<?php

namespace Database\Seeders;


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


    }
}
