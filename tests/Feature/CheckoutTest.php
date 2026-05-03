<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Mail\OrderConfirmation;

class CheckoutTest extends TestCase
{
    use DatabaseTransactions;

    public function test_cannot_access_checkout_with_empty_cart()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('checkout.index'));
        
        $response->assertRedirect(route('cart.index'));
    }

    public function test_user_can_process_checkout()
    {
        Mail::fake();

        $user = User::factory()->create();
        $category = Category::create(['name' => 'Testing', 'slug' => 'testing-cat', 'type' => 'jewelry']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Gold Chain',
            'slug' => 'gold-chain-'.rand(100,999),
            'description' => 'A beautifully designed gold chain.',
            'price' => 500,
            'stock' => 10,
        ]);

        $cart = [
            $product->id => [
                'name' => $product->name,
                'quantity' => 2,
                'price' => $product->price,
            ]
        ];

        $response = $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->post(route('checkout.process'), [
                'billing_address' => '123 Main St',
                'billing_city' => 'New York',
                'billing_zip' => '10001',
                'billing_country' => 'USA',
                'shipping_same_as_billing' => 'on',
            ]);

        $order = \App\Models\Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(1000, $order->total_price);
        $this->assertEquals('paid', $order->payment_status);

        $response->assertRedirect(route('checkout.success', ['id' => $order->id]));

        Mail::assertSent(OrderConfirmation::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $this->assertNull(session('cart'));
    }
}
