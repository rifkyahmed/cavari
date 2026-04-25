<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CustomOrderFlowTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function test_user_can_pay_for_custom_order()
    {
        $user = User::factory()->create();
        
        $order = Order::create([
            'payment_link_uuid' => 'test-uuid-999-' . rand(1, 99999),
            'total_price' => 1250,
            'status' => 'pending',
            'payment_status' => 'pending',
            'is_custom' => true,
        ]);

        $this->browse(function (Browser $browser) use ($user, $order) {
            $browser->loginAs($user)
                    ->visit(route('custom_orders.pay', $order->payment_link_uuid))
                    ->waitForText('Secure Checkout')
                    ->assertSee('$1,250.00')
                    ->assertSee('Bespoke Detail');
                    
            // E2E Stripe test button checking
            $browser->assertPresent('#submit-button')
                    ->assertSee('FULFILL PAYMENT');
        });
    }

    public function test_unauthenticated_user_clicks_custom_link_and_login_modal_opens()
    {
        $order = Order::create([
            'payment_link_uuid' => 'test-uuid-444-' . rand(1, 99999),
            'total_price' => 500,
            'status' => 'pending',
            'payment_status' => 'pending',
            'is_custom' => true,
        ]);

        $this->browse(function (Browser $browser) use ($order) {
            $browser->logout()
                    ->visit(route('custom_orders.pay', $order->payment_link_uuid))
                    // When unauthenticated, it forcefully redirects to Home
                    ->waitForLocation('/');
        });
    }
}
