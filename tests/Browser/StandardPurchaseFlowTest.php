<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StandardPurchaseFlowTest extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Test that a user can browse, add to cart, and reach checkout.
     */
    public function test_standard_purchase_flow()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Midnight Star',
            'slug' => 'midnight-star',
            'is_hidden' => false,
        ]);

        $this->browse(function (Browser $browser) use ($user, $product) {
            $browser->loginAs($user)
                    ->visitRoute('products.index')
                    ->waitForText('Midnight Star', 10) 
                    ->click('a[href*="midnight-star"]')
                    ->waitForText('ADD TO CART', 10)
                    ->click('button#add-to-cart-btn, button[onclick*="addToCart"]')
                    ->waitForText('added to cart', 10)
                    ->visitRoute('cart.index')
                    ->waitForText('Midnight Star')
                    ->clickLink('Secure Checkout')
                    ->waitForLocation('/checkout')
                    ->assertPathIs('/checkout');
        });
    }

    /**
     * Test that a user can login via the authentication modal.
     */
    public function test_user_can_login_via_modal()
    {
        $user = User::factory()->create([
            'email' => 'dusk-tester-' . rand(1, 9999) . '@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->logout()
                    ->visit('/')
                    ->waitFor('#main-header')
                    ->click('#main-header button[onclick="openAuthModal()"]')
                    ->waitFor('#auth-modal-panel')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->click('#login-form button[type="submit"]') 
                    ->waitUntilMissing('#login-form button[type="submit"]', 10) 
                    ->assertAuthenticatedAs($user);
        });
    }
}
