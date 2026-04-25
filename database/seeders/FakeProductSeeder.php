<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class FakeProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 standard fake products
        Product::factory()->count(20)->create();

        // Create 8 Atelier products (shown in the Atelier section)
        Product::factory()->count(8)->create([
            'is_atelier' => true,
            'is_featured' => true,
            'product_type' => 'jewelry'
        ]);
        
        $this->command->info('20 standard and 8 Atelier products created successfully!');
    }
}
