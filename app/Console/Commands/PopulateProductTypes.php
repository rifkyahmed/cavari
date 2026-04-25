<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class PopulateProductTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:populate-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate product_type field for all products based on their category';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting to populate product types...');

        $totalProcessed = 0;
        $gemsProcessed = 0;
        $jewelryProcessed = 0;
        $looseGemsSlug = 'loose-gems';

        // Update loose gems
        $gemsUpdated = Product::whereHas('category', function($q) use ($looseGemsSlug) {
            $q->where('slug', $looseGemsSlug);
        })
        ->where(function($q) {
            $q->whereNull('product_type')
              ->orWhere('product_type', '!=', 'gem');
        })
        ->update(['product_type' => 'gem']);

        $gemsProcessed = $gemsUpdated;

        // Update jewelry (everything that's not loose gems)
        $jewelryUpdated = Product::where(function($q) use ($looseGemsSlug) {
            $q->whereDoesntHave('category', function($sub) use ($looseGemsSlug) {
                $sub->where('slug', $looseGemsSlug);
            });
        })
        ->where(function($q) {
            $q->whereNull('product_type')
              ->orWhere('product_type', '!=', 'jewelry');
        })
        ->update(['product_type' => 'jewelry']);

        $jewelryProcessed = $jewelryUpdated;
        $totalProcessed = $gemsProcessed + $jewelryProcessed;

        $this->info("✓ Product types populated successfully!");
        $this->info("  Total updated: {$totalProcessed}");
        $this->info("  Gems: {$gemsProcessed}");
        $this->info("  Jewelry: {$jewelryProcessed}");

        return Command::SUCCESS;
    }
}
