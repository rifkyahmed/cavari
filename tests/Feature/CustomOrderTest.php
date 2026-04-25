<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomOrderTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_admin_can_create_custom_order(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);

        $category = \App\Models\Category::create(['name' => 'Test Cat', 'slug' => 'test-cat', 'type' => 'jewelry']);

        $payload = [
            'discount' => 50,
            'products' => [
                [
                    'product_type' => 'jewelry',
                    'category_id' => $category->id,
                    'name' => 'Exclusive Custom Ring',
                    'price' => 1500,
                    'cost_price' => 500,
                    'gold_weight' => 4.5,
                    'size' => '7',
                ]
            ]
        ];

        $response = $this->actingAs($admin)->post(route('admin.orders.store-custom'), $payload);
        
        $order = \App\Models\Order::latest()->first();
        $this->assertNotNull($order);
        
        $response->assertRedirect(route('admin.orders.index'));

        $this->assertEquals(1, $order->is_custom);
        $this->assertEquals(1450, $order->total_price);
        $this->assertEquals(50, $order->discount);
        $this->assertCount(1, $order->items);
        $this->assertEquals('Exclusive Custom Ring', $order->items->first()->custom_name);
    }

    public function test_unauthorized_user_cannot_view_payment_page()
    {
        $order = \App\Models\Order::create([
            'payment_link_uuid' => 'test-uuid-123',
            'total_price' => 1000,
            'status' => 'pending',
            'payment_status' => 'pending',
            'is_custom' => true,
        ]);

        $user1 = \App\Models\User::factory()->create(['role' => 'user']);
        $user2 = \App\Models\User::factory()->create(['role' => 'user']);

        // User 1 claims the order
        $this->actingAs($user1)->get(route('custom_orders.pay', 'test-uuid-123'));
        $order->refresh();
        $this->assertEquals($user1->id, $order->user_id);

        // User 2 shouldn't be able to access it
        $response = $this->actingAs($user2)->get(route('custom_orders.pay', 'test-uuid-123'));
        $response->assertStatus(403);
    }
}
