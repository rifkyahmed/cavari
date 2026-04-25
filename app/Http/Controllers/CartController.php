<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        if (session()->has('last_order_success_uuid')) {
            $uuid = session()->pull('last_order_success_uuid');
            return redirect()->route('checkout.public-success', ['uuid' => $uuid]);
        }

        $cart = session()->get('cart', []);
        $totals = $this->calculateTotals($cart);
        
        return view('cart.index', compact('cart', 'totals'));
    }

    public function store(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);
        $action = 'added';
        $message = 'Product added to cart.';

        // Toggle behavior
        if (isset($cart[$product->id])) {
            if (!$request->boolean('force_add')) {
                unset($cart[$product->id]);
                $action = 'removed';
                $message = 'Product removed from cart.';
                
                // Sync DB (Remove)
                if (auth()->check()) {
                    $userCart = \App\Models\Cart::firstOrCreate(['user_id' => auth()->id()]);
                    $userCart->items()->where('product_id', $product->id)->delete();
                }
            }
        } else {
            // Add to cart
            if ($product->stock <= 0) {
                // Out of stock – abort the operation
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success'=>false,'message'=>'Product is out of stock.'], 400);
                }
                return redirect()->back()->with('error','Product is out of stock.');
            }
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->images[0] ?? null
            ];
            
            // Sync DB (Add)
            if (auth()->check()) {
                $userCart = \App\Models\Cart::firstOrCreate(['user_id' => auth()->id()]);
                $userCart->items()->updateOrCreate(
                    ['product_id' => $product->id],
                    ['quantity' => 1]
                );
            }
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => $message,
                'action' => $action,
                'cartCount' => count($cart)
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $product = \App\Models\Product::find($request->id);
            if (! $product) {
                $msg = 'Product not found.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 404);
                }
                return redirect()->back()->with('error', $msg);
            }
            if ($product->stock < $request->quantity) {
                $msg = "Only {$product->stock} items left in stock.";
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }
                return redirect()->back()->with('error', $msg);
            }
            $cart = session()->get('cart');
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            // Sync DB (Update)
            if (auth()->check()) {
                $userCart = \App\Models\Cart::firstOrCreate(['user_id' => auth()->id()]);
                $userCart->items()->updateOrCreate(
                    ['product_id' => $request->id],
                    ['quantity' => $request->quantity]
                );
            }
            
            $totals = $this->calculateTotals($cart);
            $itemTotal = $cart[$request->id]['price'] * $cart[$request->id]['quantity'];

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully',
                    'itemTotal' => $itemTotal,
                    'subtotal' => $totals['subtotal'],
                    'discount' => $totals['discount'],
                    'total' => $totals['total'],
                    'cartCount' => count($cart)
                ]);
            }
            
            session()->flash('success', 'Cart updated successfully');
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
                
                // Sync DB (Remove)
                if (auth()->check()) {
                    $userCart = \App\Models\Cart::firstOrCreate(['user_id' => auth()->id()]);
                    $userCart->items()->where('product_id', $request->id)->delete();
                }
            }
            
            $totals = $this->calculateTotals($cart);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product removed successfully',
                    'subtotal' => $totals['subtotal'],
                    'discount' => $totals['discount'],
                    'total' => $totals['total'],
                    'cartCount' => count($cart)
                ]);
            }

            session()->flash('success', 'Product removed successfully');
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = \App\Models\Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        if ($coupon->user_email) {
            if (!auth()->check()) {
                return back()->with('error', 'Please log in to use this exclusive coupon.');
            }
            if (auth()->user()->email !== $coupon->user_email) {
                return back()->with('error', 'This coupon is not valid for your account.');
            }
        }

        if ($coupon->expiry_date && $coupon->expiry_date->isPast()) {
            return back()->with('error', 'This coupon has expired.');
        }

        if ($coupon->usage_limit !== null && $coupon->usage_limit <= 0) {
            return back()->with('error', 'This coupon has reached its usage limit.');
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
        ]);

        return back()->with('success', 'Coupon applied successfully.');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed.');
    }

    public function applyGiftCard(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $giftCard = \App\Models\GiftCard::where('code', $request->code)->where('is_active', true)->where('balance', '>', 0)->first();

        if (!$giftCard) {
            return back()->with('error', 'Invalid or empty gift card.');
        }

        if ($giftCard->expiry_date && $giftCard->expiry_date->isPast()) {
            return back()->with('error', 'This gift card has expired.');
        }

        session()->put('gift_card', [
            'id' => $giftCard->id,
            'code' => $giftCard->code,
            'balance' => $giftCard->balance,
        ]);

        return back()->with('success', 'Gift card applied successfully.');
    }

    public function removeGiftCard()
    {
        session()->forget('gift_card');
        return back()->with('success', 'Gift card removed.');
    }

    private function calculateTotals($cart)
    {
        $subtotal = 0;
        foreach($cart as $id => $details) {
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
            // Re-fetch to ensure fresh balance
            $giftCard = \App\Models\GiftCard::find($sessionGiftCard['id']);
            if ($giftCard && $giftCard->is_active && $giftCard->balance > 0) {
                $giftCardAmount = min($totalAfterCoupon, $giftCard->balance);
            } else {
                session()->forget('gift_card');
            }
        }

        $finalTotal = $totalAfterCoupon - $giftCardAmount;

        return [
            'subtotal' => $subtotal, 
            'discount' => $discount,
            'gift_card_amount' => $giftCardAmount,
            'total' => $finalTotal
        ];
    }
}
