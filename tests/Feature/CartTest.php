<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;

class CartTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Gems', 'slug' => 'gems', 'type' => 'gem']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Sapphire Stone',
            'slug' => 'sapphire-stone-'.rand(100,999), // unique slug
            'description' => 'A beautifully cut sapphire.',
            'price' => 1200,
            'stock' => 10,
        ]);

        $response = $this->actingAs($user)->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertSessionHas('success');
        
        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($cart);
        $this->assertCount(1, $cart->items);
        $this->assertEquals($product->id, $cart->items->first()->product_id);
        $this->assertEquals(2, $cart->items->first()->quantity);
    }
}
