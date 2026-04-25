<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'billing_address',
        'shipping_address',
        'payment_link_uuid',
        'payment_status',
        'gift_card_id',
        'gift_card_amount',
        'discount',
        'order_ip_address',
        'coinbase_charge_id',
    ];




    /**
     * Get country information based on order_ip_address.
     * Returns an array with 'country', 'country_code', 'flag_url'.
     */
    public function getCountryAttribute()
    {
        $ip = trim((string) $this->order_ip_address);
        if ($ip === '' || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return ['country' => 'Local / Private', 'country_code' => null, 'flag_url' => null];
        }

        return Cache::remember('admin_ip_geo_' . md5($ip), 86400, function () use ($ip) {
            try {
                $response = Http::timeout(5)->get('https://ipwho.is/' . $ip);
                $payload = $response->json();
                if (!$response->successful() || !is_array($payload) || !($payload['success'] ?? false)) {
                    return ['country' => 'Unknown', 'country_code' => null, 'flag_url' => null];
                }
                $countryCode = strtoupper((string) ($payload['country_code'] ?? ''));
                return [
                    'country' => $payload['country'] ?? 'Unknown',
                    'country_code' => $countryCode ?: null,
                    'flag_url' => $countryCode ? 'https://flagcdn.com/w40/' . strtolower($countryCode) . '.png' : null,
                ];
            } catch (\Throwable $e) {
                return ['country' => 'Unknown', 'country_code' => null, 'flag_url' => null];
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class);
    }
}
