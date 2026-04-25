<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $productType = $this->faker->randomElement(['gem', 'jewelry']);
        $gemstones = ['Ruby', 'Sapphire', 'Emerald', 'Diamond', 'Topaz', 'Tourmaline', 'Aquamarine', 'Amethyst', 'Citrine'];
        $shapes = ['Oval', 'Round', 'Cushion', 'Pear', 'Heart', 'Emerald', 'Princess', 'Marquise'];
        $colors = ['Red', 'Blue', 'Green', 'White', 'Pink', 'Yellow', 'Orange', 'Purple'];
        $treatments = ['None', 'Heated', 'Unheated', 'Oil Treated', 'Irradiated'];
        $metals = ['Platinum', 'Gold', 'White Gold', 'Rose Gold', 'Silver'];

        $images = [
            'images/ring-1.png',
            'images/necklace-1.png',
            'images/earrings-1.png',
            'images/bracelet-1.png',
            'images/ruby.png',
            'images/sapphire.png',
            'images/emerald.png',
            'images/pink_gemstone_hero.png',
            'images/cavarigem.png',
            'images/hero-gem.png',
        ];

        return [
            'name' => ucfirst($name),
            'slug' => \Illuminate\Support\Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'description' => $this->faker->paragraphs(2, true),
            'price' => $this->faker->numberBetween(500, 25000),
            'stock' => $this->faker->numberBetween(0, 15),
            'gemstone_type' => $this->faker->randomElement($gemstones),
            'images' => [$this->faker->randomElement($images)],
            'is_featured' => $this->faker->boolean(20),
            'is_hidden' => false,
            'product_type' => $productType,
            'color' => $this->faker->randomElement($colors),
            'weight' => $this->faker->randomFloat(2, 0.5, 15.0),
            'shape' => $this->faker->randomElement($shapes),
            'treatment' => $this->faker->randomElement($treatments),
            'metal' => $productType === 'jewelry' ? $this->faker->randomElement($metals) : null,
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id ?? \App\Models\Category::factory(),
            'origin' => $this->faker->country,
            'clarity' => $this->faker->randomElement(['VVS1', 'VVS2', 'VS1', 'VS2', 'SI1', 'SI2', 'I1']),
            'size' => $productType === 'jewelry' ? $this->faker->numberBetween(5, 12) : null,
        ];
    }
}
