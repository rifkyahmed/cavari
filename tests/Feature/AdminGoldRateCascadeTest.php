<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminGoldRateCascadeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_gold_rate_update_changes_only_gem_gold_products(): void
    {
        $requiredProductColumns = ['gold_weight', 'gold_cost_price', 'cost_price', 'caret_range'];
        foreach ($requiredProductColumns as $column) {
            if (!Schema::hasColumn('products', $column)) {
                $this->markTestSkipped("products.{$column} column is required for this pricing regression test.");
            }
        }

        Http::fake([
            'https://open.er-api.com/*' => Http::response([
                'rates' => ['LKR' => 325.0],
            ], 200),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        Setting::set('gold_price', 1.00, 'market');

        $goldMain = Category::create([
            'name' => 'Gem and Gold',
            'slug' => 'gem-and-gold',
            'type' => 'jewelry',
        ]);
        $otherMain = Category::create([
            'name' => 'Gem and Copper',
            'slug' => 'gem-and-copper',
            'type' => 'jewelry',
        ]);

        $goldProduct = Product::create([
            'category_id' => $goldMain->id,
            'name' => 'Gold Test Ring',
            'slug' => 'gold-test-ring',
            'description' => 'Gold ring',
            'price' => 100.00,
            'stock' => 10,
            'product_type' => 'jewelry',
            'gold_weight' => 2.00,
            'caret_range' => '22k',
            'gold_cost_price' => 2.00 * (22 / 24), // Consistent with $1.00 gold_price
            'cost_price' => 70.00,
        ]);

        $silverProduct = Product::create([
            'category_id' => $otherMain->id,
            'name' => 'Silver Test Ring',
            'slug' => 'silver-test-ring',
            'description' => 'Silver ring',
            'price' => 100.00,
            'stock' => 10,
            'product_type' => 'jewelry',
            'gold_weight' => 3.00,
            'gold_cost_price' => 50.00,
            'cost_price' => 70.00,
        ]);

        $platinumProduct = Product::create([
            'category_id' => $otherMain->id,
            'name' => 'Platinum Test Ring',
            'slug' => 'platinum-test-ring',
            'description' => 'Platinum ring',
            'price' => 100.00,
            'stock' => 10,
            'product_type' => 'jewelry',
            'gold_weight' => 4.00,
            'gold_cost_price' => 50.00,
            'cost_price' => 70.00,
        ]);

        $otherProduct = Product::create([
            'category_id' => $otherMain->id,
            'name' => 'Copper Test Ring',
            'slug' => 'copper-test-ring',
            'description' => 'Copper ring',
            'price' => 100.00,
            'stock' => 10,
            'product_type' => 'jewelry',
            'gold_weight' => 5.00,
            'gold_cost_price' => 50.00,
            'cost_price' => 70.00,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.dashboard.update-gold'), [
            'gold_price_lkr' => 650.00,
        ]);

        $response->assertRedirect();

        $goldProduct->refresh();
        $silverProduct->refresh();
        $platinumProduct->refresh();
        $otherProduct->refresh();

        // Delta is +1.00 USD/g. Only Gem and Gold should change.
        $expectedGoldDelta = 2.00 * (22 / 24);

        $this->assertEqualsWithDelta(110.00, (float) $goldProduct->price, 0.01); // (100 + 1.833) rounded up to next 10 is 110
        $this->assertEqualsWithDelta(2.00 * (22 / 24) * 2.00, (float) $goldProduct->gold_cost_price, 0.01);
        $this->assertEqualsWithDelta(70.00 + $expectedGoldDelta, (float) $goldProduct->cost_price, 0.01);

        $this->assertEqualsWithDelta(100.00, (float) $silverProduct->price, 0.01);
        $this->assertEqualsWithDelta(50.00, (float) $silverProduct->gold_cost_price, 0.01);
        $this->assertEqualsWithDelta(70.00, (float) $silverProduct->cost_price, 0.01);

        $this->assertEqualsWithDelta(100.00, (float) $platinumProduct->price, 0.01);
        $this->assertEqualsWithDelta(50.00, (float) $platinumProduct->gold_cost_price, 0.01);
        $this->assertEqualsWithDelta(70.00, (float) $platinumProduct->cost_price, 0.01);

        $this->assertEqualsWithDelta(100.00, (float) $otherProduct->price, 0.01);
        $this->assertEqualsWithDelta(50.00, (float) $otherProduct->gold_cost_price, 0.01);
        $this->assertEqualsWithDelta(70.00, (float) $otherProduct->cost_price, 0.01);

        $this->assertEqualsWithDelta(2.00, (float) Setting::get('gold_price'), 0.0001);
    }
}
