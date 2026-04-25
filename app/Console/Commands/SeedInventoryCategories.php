<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedInventoryCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-inventory-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding categories...');

        // 1. Loose Gem (Level 1)
        $looseGem = \App\Models\Category::updateOrCreate(
            ['slug' => 'loose-gem'],
            ['name' => 'Loose Gem', 'type' => 'loose_gem', 'parent_id' => null]
        );

        // Level 2 (under Loose Gem)
        $looseGemTypes = ['Ruby', 'Sapphire', 'Emerald', 'Diamond'];
        foreach ($looseGemTypes as $typeName) {
            $type = \App\Models\Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($typeName . '-loose')],
                ['name' => $typeName, 'type' => 'loose_gem', 'parent_id' => $looseGem->id]
            );

            // Level 3 (example sub-types)
            if ($typeName === 'Sapphire') {
                $subTypes = ['Blue Sapphire', 'Yellow Sapphire', 'Pink Sapphire', 'Padparadscha Sapphire'];
                foreach ($subTypes as $subName) {
                    \App\Models\Category::updateOrCreate(
                        ['slug' => \Illuminate\Support\Str::slug($subName)],
                        ['name' => $subName, 'type' => 'loose_gem', 'parent_id' => $type->id]
                    );
                }
            } elseif ($typeName === 'Ruby') {
                 $subTypes = ['Burma Ruby', 'Mozambique Ruby'];
                 foreach ($subTypes as $subName) {
                    \App\Models\Category::updateOrCreate(
                        ['slug' => \Illuminate\Support\Str::slug($subName)],
                        ['name' => $subName, 'type' => 'loose_gem', 'parent_id' => $type->id]
                    );
                }
            }
        }

        // 2. Gem and Jewelry (Level 1)
        $gemJewelry = \App\Models\Category::updateOrCreate(
            ['slug' => 'gem-and-jewelry'],
            ['name' => 'Gem and Jewelry', 'type' => 'jewelry', 'parent_id' => null]
        );

        // Level 2 (Material)
        $materials = ['Gem and Gold', 'Gem and Silver', 'Gem and Platinum'];
        $productTypes = ['Rings', 'Necklace', 'Bangles', 'Earrings', 'Pendants'];

        foreach ($materials as $materialName) {
            $material = \App\Models\Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($materialName)],
                ['name' => $materialName, 'type' => 'jewelry', 'parent_id' => $gemJewelry->id]
            );

            // Level 3 (Product Type)
            foreach ($productTypes as $pTypeName) {
                \App\Models\Category::updateOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($materialName . '-' . $pTypeName)],
                    ['name' => $pTypeName, 'type' => 'jewelry', 'parent_id' => $material->id]
                );
            }
        }

        $this->info('Categories seeded successfully!');
    }
}
