<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Order;

class PaymentController extends Controller
{
    private $apiKey;
    private $webhookSecret;
    private $apiUrl = 'https://api.commerce.coinbase.com';

    public function __construct()
    {
        $this->apiKey = config('services.coinbase.api_key');
        $this->webhookSecret = config('services.coinbase.webhook_secret');
    }

    /**
     * Create a Coinbase Commerce charge and return the hosted URL.
     */
    public function createCharge(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        $payload = [
            'name'         => 'Order #' . $order->id . ' - ' . config('app.name'),
            'description'  => 'Payment for jewelry purchase',
            'pricing_type' => 'fixed_price',
            'local_price'  => [
                'amount'   => number_format($order->total_price, 2, '.', ''),
                'currency' => 'USD',
            ],
            'metadata' => [
                'order_id' => $order->id,
                'user_id'  => $order->user_id,
            ],
            'redirect_url' => route('checkout.success', ['id' => $order->id]),
            'cancel_url'   => route('checkout.index'),
        ];

        try {
            $response = Http::withHeaders([
                'X-CC-Api-Key' => $this->apiKey,
                'X-CC-Version' => '2018-03-22',
            ])->post($this->apiUrl . '/charges', $payload);

            if ($response->successful()) {
                $charge = $response->json()['data'];
                
                // Store the charge ID on the order
                $order->update(['coinbase_charge_id' => $charge['id']]);

                return response()->json([
                    'success' => true,
                    'hosted_url' => $charge['hosted_url']
                ]);
            }

            Log::error('Coinbase API error: ' . $response->body());
            return response()->json(['success' => false, 'message' => 'Coinbase API Error'], 500);

        } catch (\Exception $e) {
            Log::error('Coinbase charge creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Connection Error'], 500);
        }
    }

    /**
     * Webhook endpoint – Coinbase posts a JSON payload here.
     * Verifies HMAC signature and marks the related order as paid when the
     * charge is confirmed.
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('X-CC-Webhook-Signature');
        $payload   = $request->getContent();

        if (!$signature || !$this->webhookSecret) {
            Log::warning('Missing Coinbase signature or secret.');
            return response('Unauthorized', 401);
        }

        // Verify the webhook signature
        $computedSignature = hash_hmac('sha256', $payload, $this->webhookSecret);
        
        if (!hash_equals($computedSignature, $signature)) {
            Log::warning('Invalid Coinbase webhook signature.');
            return response('Invalid signature', 400);
        }

        $event = json_decode($payload, true);
        $type  = $event['event']['type'] ?? null;
        $chargeData = $event['event']['data'] ?? [];
        $chargeId = $chargeData['id'] ?? null;

        Log::info("Coinbase Webhook received: {$type} for Charge ID: {$chargeId}");

        if (in_array($type, ['charge:confirmed', 'charge:resolved']) && $chargeId) {
            $order = Order::where('coinbase_charge_id', $chargeId)->first();
            if ($order) {
                $order->payment_status = 'paid';
                if (Schema::hasColumn('orders', 'paid_at')) {
                    $order->paid_at = now();
                }
                $order->save();
                Log::info("Order {$order->id} marked as paid via Coinbase.");
            }
        }

        return response('OK', 200);
    }
}

