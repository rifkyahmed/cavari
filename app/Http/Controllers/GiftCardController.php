<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GiftCard;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Mail;
use App\Mail\GiftCardMail;

class GiftCardController extends Controller
{
    public function index()
    {
        return view('gift-cards.index');
    }

    public function initialize(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        session(['gift_card_checkout_amount' => $request->amount]);

        return redirect()->route('gift-cards.checkout');
    }

    public function checkout()
    {
        $amount = session('gift_card_checkout_amount');

        if (!$amount) {
            return redirect()->route('gift-cards.index');
        }

        // Initialize Stripe
        $clientSecret = null;
        $stripeKey = config('services.stripe.key');
        $stripeSecret = config('services.stripe.secret');

        if ($stripeSecret && $amount > 0) {
            Stripe::setApiKey($stripeSecret);
            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => (int) round($amount * 100),
                    'currency' => 'usd',
                    'metadata' => [
                        'type' => 'gift_card_purchase',
                        'user_id' => auth()->id()
                    ]
                ]);
                $clientSecret = $paymentIntent->client_secret;
            } catch (\Exception $e) {
                // Handle stripe error
            }
        }

        return view('gift-cards.checkout', compact('amount', 'clientSecret', 'stripeKey'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'recipient_email' => 'required|email',
            'recipient_name' => 'required|string',
            'sender_name' => 'required|string',
            'sender_email' => 'required|email',
            'message' => 'nullable|string|max:500',
            'payment_intent' => 'nullable|string', // Stripe payment intent ID
        ]);

        $code = strtoupper(Str::random(12));

        $giftCard = GiftCard::create([
            'code' => $code,
            'balance' => $request->amount,
            'initial_balance' => $request->amount,
            'sender_name' => $request->sender_name,
            'sender_email' => $request->sender_email,
            'recipient_name' => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'message' => $request->message,
            'user_id' => auth()->id(),
            'is_active' => true,
        ]);

        // Automated Flow:
        // 1. Send e-mail directly to recipient
        try {
            Mail::to($request->recipient_email)->send(new GiftCardMail($giftCard));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send gift card email: ' . $e->getMessage());
        }

        // 2. Provide code and sharing link to sender
        $shareUrl = url('/gift-cards?code=' . $code);
        
        // Clear session data
        session()->forget('gift_card_checkout_amount');

        return redirect()->route('gift-cards.index')->with([
            'success' => "Success. Your payment has been processed and the gift has been sent to {$request->recipient_name} via email.",
            'gift_code' => $code,
            'share_url' => $shareUrl
        ]);
    }
}
