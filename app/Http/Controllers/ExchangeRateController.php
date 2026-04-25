<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    /**
     * The currencies we support on the storefront.
     */
    private const CURRENCIES = ['LKR', 'AED', 'INR', 'GBP', 'EUR', 'AUD', 'CAD'];

    /**
     * Hardcoded fallback rates (used only if the live API is unreachable).
     */
    private const FALLBACK_RATES = [
        'LKR' => 311.10,
        'AED' => 3.6725,
        'INR' => 92.55,
        'GBP' => 0.755,
        'EUR' => 0.874,
        'AUD' => 1.427,
        'CAD' => 1.371,
    ];

    /**
     * Return live USD-based exchange rates, cached for 1 hour.
     * Uses https://open.er-api.com (free, no API key, 161 currencies incl. AED & LKR).
     */
    public function rates(): JsonResponse
    {
        $rates = Cache::remember('exchange_rates_usd', 3600, function () {
            try {
                // Free tier: no API key, updated daily, 161 currencies
                $response = Http::timeout(6)
                    ->get('https://open.er-api.com/v6/latest/USD');

                if ($response->successful() && $response->json('result') === 'success') {
                    $all = $response->json('rates', []);

                    // Only return the currencies we care about
                    return collect(self::CURRENCIES)
                        ->mapWithKeys(fn ($cur) => [$cur => $all[$cur] ?? null])
                        ->filter()        // drop any that were missing
                        ->toArray();
                }
            } catch (\Throwable) {
                // Fall through to null → triggers fallback below
            }

            return null;
        });

        $isLive = (bool) $rates;

        // Merge live rates over fallback so we always have every currency
        $rates = array_merge(self::FALLBACK_RATES, $rates ?? []);

        return response()->json([
            'base'      => 'USD',
            'rates'     => $rates,
            'is_live'   => $isLive,
            'cached_at' => now()->toIso8601String(),
        ]);
    }
}
