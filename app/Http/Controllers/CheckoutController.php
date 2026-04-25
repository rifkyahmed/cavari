<?php

namespace App\Http\Controllers;

use App\Models\AbandonedCheckout;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Mail\OrderConfirmation;
use App\Models\Product;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function index()
    {
        if (session()->has('last_order_success_uuid')) {
            return redirect()->route('checkout.public-success', ['uuid' => session('last_order_success_uuid')]);
        }

        if (!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('cart.index');
        }

        $cart = session('cart');
        $subtotal = 0;
        foreach ($cart as $id => $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $discount = 0;
        if (session()->has('coupon')) {
            $coupon = session('coupon');
            if ($coupon['discount_type'] == 'percentage') {
                $discount = ($subtotal * $coupon['discount_value']) / 100;
            } else {
                $discount = $coupon['discount_value'];
            }
        }
        $totalAfterCoupon = max(0, $subtotal - $discount);

        $giftCardAmount = 0;
        if (session()->has('gift_card')) {
            $sessionGiftCard = session('gift_card');
            $giftCard = \App\Models\GiftCard::find($sessionGiftCard['id']);
            if ($giftCard && $giftCard->is_active && $giftCard->balance > 0) {
                $giftCardAmount = min($totalAfterCoupon, $giftCard->balance);
            } else {
                session()->forget('gift_card');
            }
        }

        $total = $totalAfterCoupon - $giftCardAmount;
        $addresses = auth()->check() ? auth()->user()->addresses : collect([]);

        // Track abandoned checkout: only for logged-in users
        if (auth()->check()) {
            $user = auth()->user();
            $existing = AbandonedCheckout::where('user_id', $user->id)->first();
            
            $data = [
                'user_name'  => $user->name,
                'user_email' => $user->email,
                'cart_data'  => $cart,
                'cart_total' => $total,
            ];

            if ($existing) {
                // More robust comparison of cart data
                $oldCart = $existing->cart_data;
                $newCart = $cart;
                
                // Sort by product IDs to ensure order doesn't affect comparison
                if (is_array($oldCart)) ksort($oldCart);
                if (is_array($newCart)) ksort($newCart);

                // Only reset the reminder flag if the cart content has actually changed
                if (json_encode($oldCart) !== json_encode($newCart)) {
                    $data['reminder_sent_at'] = null;
                }
                
                $existing->update($data);
            } else {

                $data['reminder_sent_at'] = null;
                $data['user_id'] = $user->id;
                AbandonedCheckout::create($data);
            }
        }

        // Initialize Stripe
        $clientSecret = null;
        if (config('services.stripe.secret') && $total > 0) {
            Stripe::setApiKey(config('services.stripe.secret'));
            try {
                // AMOUNT IS IN CENTS
                $paymentIntent = PaymentIntent::create([
                    'amount' => (int) round($total * 100),
                    'currency' => 'usd',
                ]);
                $clientSecret = $paymentIntent->client_secret;
            } catch (\Exception $e) {
                // Optionally handle stripe error
            }
        }

        return view('checkout.index', compact('cart', 'subtotal', 'discount', 'giftCardAmount', 'total', 'addresses', 'clientSecret'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string',
            'billing_zip' => 'required|string',
            'billing_country' => 'required|string',
            'shipping_address' => 'required_without:shipping_same_as_billing|nullable|string|max:255',
            'shipping_city' => 'required_without:shipping_same_as_billing|nullable|string',
            'shipping_zip' => 'required_without:shipping_same_as_billing|nullable|string',
            'shipping_country' => 'required_without:shipping_same_as_billing|nullable|string',
            'payment_intent' => 'nullable|string',
            'payment_method' => 'required|string|in:stripe,coinbase',
        ]);

        $billingAddress = $request->billing_address . ', ' . $request->billing_city . ', ' . $request->billing_zip . ', ' . $request->billing_country;
        
        if ($request->has('shipping_same_as_billing')) {
            $shippingAddress = $billingAddress;
        } else {
            $shippingAddress = $request->shipping_address . ', ' . $request->shipping_city . ', ' . $request->shipping_zip . ', ' . $request->shipping_country;
        }

        $cart = session('cart');
        $subtotal = 0;
        foreach ($cart as $id => $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $discount = 0;
        if (session()->has('coupon')) {
            $coupon = session('coupon');
            if ($coupon['discount_type'] == 'percentage') {
                $discount = ($subtotal * $coupon['discount_value']) / 100;
            } else {
                $discount = $coupon['discount_value'];
            }
        }
        $totalAfterCoupon = max(0, $subtotal - $discount);

        $giftCardAmount = 0;
        $giftCardId = null;
        if (session()->has('gift_card')) {
            $sessionGiftCard = session('gift_card');
            $giftCard = \App\Models\GiftCard::find($sessionGiftCard['id']);
            if ($giftCard && $giftCard->is_active && $giftCard->balance > 0) {
                $giftCardId = $giftCard->id;
                $giftCardAmount = min($totalAfterCoupon, $giftCard->balance);
            }
        }

        $total = $totalAfterCoupon - $giftCardAmount;

        // Validate stock for each cart item
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if (! $product) {
                    return redirect()->back()->with('error', 'Product not found.');
                }
                if ($product->stock < $details['quantity']) {
                    return redirect()->back()->with('error', "Only {$product->stock} items left in stock for {$product->name}.");
                }
            }
            DB::beginTransaction();

        try {
            $orderData = [
                'user_id' => auth()->id(), // Restricted by middleware
                'status' => 'pending',
                'total_price' => $total,
                'billing_address' => $billingAddress,
                'shipping_address' => $shippingAddress,
                'payment_link_uuid' => (string) Str::uuid(),
                'payment_status' => $request->payment_method === 'stripe' ? 'paid' : 'pending',
                'gift_card_id' => $giftCardId,
                'gift_card_amount' => $giftCardAmount,
                'discount' => $discount,
            ];

            if (Schema::hasColumn('orders', 'order_ip_address')) {
                $orderData['order_ip_address'] = $request->ip();
            }

            $order = Order::create($orderData);

            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
                // Decrement stock
                if ($product) {
                    $product->decrement('stock', $details['quantity']);
                }
            }

            DB::commit();

            if (session()->has('coupon')) {
                $usedCoupon = \App\Models\Coupon::where('code', session('coupon')['code'])->first();
                if ($usedCoupon && $usedCoupon->usage_limit !== null && $usedCoupon->usage_limit > 0) {
                    $usedCoupon->decrement('usage_limit');
                }
            }

            if ($giftCardId && $giftCardAmount > 0) {
                $giftCard = \App\Models\GiftCard::lockForUpdate()->find($giftCardId);
                $giftCard->decrement('balance', $giftCardAmount);
                
                \App\Models\GiftCardTransaction::create([
                    'gift_card_id' => $giftCardId,
                    'order_id' => $order->id,
                    'amount' => $giftCardAmount,
                    'type' => 'debit',
                    'notes' => 'Applied to order #' . $order->id,
                ]);
            }

            session()->forget('cart');
            session()->forget('coupon');
            session()->forget('gift_card');

            // ✅ Clear persistent database cart
            if (auth()->check()) {
                $userCart = \App\Models\Cart::where('user_id', auth()->id())->first();
                if ($userCart) {
                    $userCart->items()->delete();
                    $userCart->delete();
                }
            }

            // ✅ Order completed: remove abandoned checkout record for this user
            if (auth()->check()) {
                AbandonedCheckout::where('user_id', auth()->id())->delete();
            }

            // Send Order Confirmation Email with Invoice
            try {
                Mail::to(auth()->user()->email)->send(new OrderConfirmation($order));
            } catch (\Exception $emailError) {
                // Log the email error but don't stop the checkout
                \Illuminate\Support\Facades\Log::error('Failed to send order confirmation email: ' . $emailError->getMessage());
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'order_id' => $order->id]);
            }

            if (!empty($order->payment_link_uuid)) {
                session(['last_order_success_uuid' => $order->payment_link_uuid]);
                return redirect()->route('checkout.public-success', ['uuid' => $order->payment_link_uuid])
                    ->with('success', 'Order placed successfully!');
            }

            return redirect()->route('checkout.success', ['id' => $order->id])->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', 'Something went wrong processing your order. ' . $e->getMessage());
        }
    }

    public function success($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        return view('checkout.success', compact('order'));
    }

    public function publicSuccess(string $uuid)
    {
        session()->forget('last_order_success_uuid');

        $order = Order::with('user')
            ->where('payment_link_uuid', $uuid)
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
