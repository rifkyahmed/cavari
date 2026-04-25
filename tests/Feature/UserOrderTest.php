<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;

class UserOrderTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_view_orders_list()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('orders.index'));
        $response->assertStatus(200);
    }

    public function test_user_can_download_invoice()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 500,
            'status' => 'paid',
            'payment_status' => 'paid',
            'is_custom' => false
        ]);

        $response = $this->actingAs($user)->get(route('user.orders.invoice', $order->id));
        $response->assertStatus(200);
    }

    public function test_user_cannot_download_invoice_for_unpaid_custom_order()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 2500,
            'status' => 'pending',
            'payment_status' => 'pending',
            'is_custom' => true,
            'payment_link_uuid' => 'fake-uuid-1234'
        ]);

        $response = $this->actingAs($user)->get(route('user.orders.invoice', $order->id));
        
        // Assert redirected to payment page
        $response->assertRedirect(route('custom_orders.pay', 'fake-uuid-1234'));
        $response->assertSessionHas('error');
    }
}
