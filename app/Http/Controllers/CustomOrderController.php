<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CustomOrderController extends Controller
{
    public function pay($uuid)
    {
        $order = Order::where('payment_link_uuid', $uuid)->firstOrFail();

        // If guest, ensure the intended URL is saved so login/register redirects smoothly back here
        if (!auth()->check()) {
            session(['url.intended' => request()->fullUrl(), 'intended_url' => request()->fullUrl()]);
            return redirect()->route('home')->with(['open_auth_modal' => true]);
        }

        $user = auth()->user();

        // Admins can view anything without claiming it
        if ($user && $user->isAdmin()) {
            // Continue below normally
        } else {
            // Enforce matching user or assign if unassigned
            if (is_null($order->user_id)) {
                $order->update(['user_id' => auth()->id()]);
            } elseif (auth()->id() !== $order->user_id) {
                abort(403, 'Unauthorized to view this order payment link.');
            }
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->id)->with('success', 'This custom order has already been paid.');
        }

        $clientSecret = null;
        if (config('services.stripe.secret') && $order->total_price > 0) {
            Stripe::setApiKey(config('services.stripe.secret'));
            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => (int) round($order->total_price * 100),
                    'currency' => 'usd',
                ]);
                $clientSecret = $paymentIntent->client_secret;
            } catch (\Exception $e) {
                // handle error
            }
        }

        $addresses = auth()->user()->addresses ?? collect();

        return view('custom_orders.pay', compact('order', 'clientSecret', 'addresses'));
    }

    public function process(Request $request, $uuid)
    {
        $order = Order::where('payment_link_uuid', $uuid)->firstOrFail();

        $user = auth()->user();

        if ($user && $user->isAdmin()) {
            // Admin can process anything
        } else {
            if (is_null($order->user_id)) {
                $order->update(['user_id' => auth()->id()]);
            } elseif (auth()->id() !== $order->user_id) {
                abort(403, 'Unauthorized');
            }
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->id);
        }

        $request->validate([
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string',
            'billing_zip' => 'required|string',
            'billing_country' => 'required|string',
            'shipping_address' => 'required_without:shipping_same_as_billing|nullable|string|max:255',
            'shipping_city' => 'required_without:shipping_same_as_billing|nullable|string',
            'shipping_zip' => 'required_without:shipping_same_as_billing|nullable|string',
            'shipping_country' => 'required_without:shipping_same_as_billing|nullable|string',
        ]);

        $billingAddress = $request->billing_address . ', ' . $request->billing_city . ', ' . $request->billing_zip . ', ' . $request->billing_country;
        
        if ($request->has('shipping_same_as_billing')) {
            $shippingAddress = $billingAddress;
        } else {
            $shippingAddress = $request->shipping_address . ', ' . $request->shipping_city . ', ' . $request->shipping_zip . ', ' . $request->shipping_country;
        }

        $orderData = [
            'billing_address' => $billingAddress,
            'shipping_address' => $shippingAddress,
            'payment_status' => 'paid',
            'status' => 'processing',
        ];

        if (Schema::hasColumn('orders', 'order_ip_address')) {
            $orderData['order_ip_address'] = $request->ip();
        }

        $order->update($orderData);

        // Clear the payment intent and uuid so it cant be paid again if necessary, but tracking payment_status='paid' is sufficient.

        if (!empty($order->payment_link_uuid)) {
            session(['last_order_success_uuid' => $order->payment_link_uuid]);
            return redirect()->route('checkout.public-success', $order->payment_link_uuid)
                ->with('success', 'Custom order payment successful!');
        }

        return redirect()->route('checkout.success', $order->id)->with('success', 'Custom order payment successful!');
    }
}
