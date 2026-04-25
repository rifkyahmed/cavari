<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\CustomizationRequest;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Build a full category tree id list from one or more root category ids.
     */
    private function collectDescendantCategoryIds(array $rootIds): array
    {
        if (empty($rootIds)) {
            return [];
        }

        if (!Schema::hasColumn('categories', 'parent_id')) {
            return array_values(array_unique(array_map('intval', $rootIds)));
        }

        $allCategories = Category::query()->select('id', 'parent_id')->get();
        $childrenMap = [];

        foreach ($allCategories as $category) {
            if ($category->parent_id !== null) {
                $childrenMap[$category->parent_id][] = $category->id;
            }
        }

        $queue = array_values(array_unique(array_map('intval', $rootIds)));
        $allIds = [];

        while (!empty($queue)) {
            $currentId = array_shift($queue);

            if (in_array($currentId, $allIds, true)) {
                continue;
            }

            $allIds[] = $currentId;

            foreach ($childrenMap[$currentId] ?? [] as $childId) {
                $queue[] = (int) $childId;
            }
        }

        return $allIds;
    }

    /**
     * Resolve a gem-metal family by slug first, then by name fallback.
     */
    private function resolveGemMetalCategoryIds(string $slug, string $nameFallback): array
    {
        $normalizedName = strtolower(str_replace(['&', '-'], ['and', ' '], $nameFallback));

        $rootIds = Category::query()
            ->where('slug', $slug)
            ->orWhereRaw(
                "LOWER(REPLACE(REPLACE(name, '&', 'and'), '-', ' ')) = ?",
                [preg_replace('/\s+/', ' ', $normalizedName)]
            )
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        return $this->collectDescendantCategoryIds($rootIds);
    }

    /**
     * Apply market-rate delta for matching products.
     */
    private function applyRateDeltaToCategories(array $categoryIds, float $newPrice, bool $useCaretMultiplier): int
    {
        if (empty($categoryIds)) {
            return 0;
        }

        $productIds = Product::query()
            ->whereIn('category_id', $categoryIds)
            ->where('gold_weight', '>', 0)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        if (empty($productIds)) {
            return 0;
        }

        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $multiplierExpr = "(CASE
            WHEN caret_range = '24k' THEN 1.0
            WHEN caret_range = '22k' THEN 22/24.0
            WHEN caret_range = '20k' THEN 20/24.0
            WHEN caret_range = '18k' THEN 18/24.0
            ELSE 1.0
        END)";

        // We use a self-correcting delta:
        // 1. Calculate what the gold cost SHOULD be: (weight * new_price * multiplier)
        // 2. Adjust the total price by (NewGoldCost - CurrentGoldCost)
        // 3. Update the gold_cost_price to NewGoldCost
        
        DB::statement(
            "UPDATE products
             SET
                price = CEILING((price + ((gold_weight * ? * {$multiplierExpr}) - COALESCE(gold_cost_price, 0))) / 10) * 10,
                gold_cost_price = (gold_weight * ? * {$multiplierExpr}),
                cost_price = (COALESCE(cost_price, 0) - COALESCE(gold_cost_price, 0)) + (gold_weight * ? * {$multiplierExpr})
             WHERE id IN ({$placeholders})",
            array_merge([$newPrice, $newPrice, $newPrice], $productIds)
        );

        return count($productIds);
    }

    /**
     * Resolve a readable country name and ISO code for a buyer IP.
     */
    private function resolveCountryFromIp(?string $ip): array
    {
        $ip = trim((string) $ip);

        if ($ip === '' || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return [
                'country' => 'Local / Private',
                'country_code' => null,
                'flag_url' => null,
            ];
        }

        return Cache::remember('admin_ip_geo_' . md5($ip), 86400, function () use ($ip) {
            try {
                $response = Http::timeout(5)->get('https://ipwho.is/' . $ip);
                $payload = $response->json();

                if (!$response->successful() || !is_array($payload) || !($payload['success'] ?? false)) {
                    return [
                        'country' => 'Unknown',
                        'country_code' => null,
                        'flag_url' => null,
                    ];
                }

                $countryCode = strtoupper((string) ($payload['country_code'] ?? ''));

                return [
                    'country' => $payload['country'] ?? 'Unknown',
                    'country_code' => $countryCode ?: null,
                    'flag_url' => $countryCode ? 'https://flagcdn.com/w40/' . strtolower($countryCode) . '.png' : null,
                ];
            } catch (\Throwable $throwable) {
                return [
                    'country' => 'Unknown',
                    'country_code' => null,
                    'flag_url' => null,
                ];
            }
        });
    }

    /**
     * Normalize a country name into a stable bucket key.
     */
    private function normalizeCountryBucketKey(string $country, ?string $countryCode = null): string
    {
        $country = trim(preg_replace('/\s+/', ' ', $country));

        if ($countryCode) {
            return 'code:' . strtoupper($countryCode);
        }

        return 'name:' . strtolower($country);
    }

    /**
     * Build the dashboard payload that powers the live charts.
     */
    private function buildLiveDashboardPayload(): array
    {
        return Cache::remember('admin_dashboard_live_payload', 30, function () {
            $trendStart = now()->subDays(13)->startOfDay();
            $trendEnd = now()->endOfDay();

            $dailyRevenue = Order::query()
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$trendStart, $trendEnd])
                ->selectRaw('DATE(created_at) as sale_date, COUNT(*) as order_count, COALESCE(SUM(total_price), 0) as revenue')
                ->groupBy('sale_date')
                ->orderBy('sale_date')
                ->get()
                ->keyBy('sale_date');

            $salesTrend = [];

            for ($offset = 13; $offset >= 0; $offset--) {
                $date = now()->subDays($offset)->startOfDay();
                $dateKey = $date->toDateString();
                $dayData = $dailyRevenue->get($dateKey);

                $salesTrend[] = [
                    'date' => $dateKey,
                    'label' => $date->format('M d'),
                    'orders' => (int) ($dayData->order_count ?? 0),
                    'revenue' => (float) ($dayData->revenue ?? 0),
                ];
            }

            $countryBuckets = [];

            if (Schema::hasColumn('orders', 'order_ip_address')) {
                $countryRows = Order::query()
                    ->where('payment_status', 'paid')
                    ->whereNotNull('order_ip_address')
                    ->selectRaw('order_ip_address, COUNT(*) as order_count, COALESCE(SUM(total_price), 0) as revenue')
                    ->groupBy('order_ip_address')
                    ->get()
                    ->all();

                foreach ($countryRows as $row) {
                    $geo = $this->resolveCountryFromIp($row->order_ip_address);
                    $bucketKey = $this->normalizeCountryBucketKey((string) $geo['country'], $geo['country_code']);

                    if (!isset($countryBuckets[$bucketKey])) {
                        $countryBuckets[$bucketKey] = [
                            'country' => $geo['country'],
                            'country_code' => $geo['country_code'],
                            'flag_url' => $geo['flag_url'],
                            'orders' => 0,
                            'revenue' => 0.0,
                            'ips' => [],
                        ];
                    }

                    $countryBuckets[$bucketKey]['orders'] += (int) $row->order_count;
                    $countryBuckets[$bucketKey]['revenue'] += (float) $row->revenue;
                    $countryBuckets[$bucketKey]['ips'][] = $row->order_ip_address;
                }
            } else {
                $countryRows = Order::query()
                    ->where('payment_status', 'paid')
                    ->whereNotNull('shipping_address')
                    ->whereRaw("TRIM(SUBSTRING_INDEX(shipping_address, ',', -1)) <> ''")
                    ->selectRaw("TRIM(SUBSTRING_INDEX(shipping_address, ',', -1)) as country, COUNT(*) as order_count, COALESCE(SUM(total_price), 0) as revenue")
                    ->groupBy('country')
                    ->orderByDesc('revenue')
                    ->take(8)
                    ->get()
                    ->all();

                foreach ($countryRows as $row) {
                    $countryName = $row->getOriginal('country') ?? 'Unknown';
                    // Shipping address country, no IP geo data available
                    $countryCode = null;
                    $flagUrl = null;
                    $bucketKey = $this->normalizeCountryBucketKey($countryName, $countryCode);

                    $countryBuckets[$bucketKey] = [
                        'country' => $countryName,
                        'country_code' => $countryCode,
                        'flag_url' => $flagUrl,
                        'orders' => (int) $row->order_count,
                        'revenue' => (float) $row->revenue,
                        'ips' => [],
                    ];
                }
            }

            $totalCountryRevenue = array_sum(array_column($countryBuckets, 'revenue'));

            $salesByCountry = collect($countryBuckets)
                ->map(function (array $bucket) use ($totalCountryRevenue) {
                    $uniqueIps = array_values(array_unique($bucket['ips']));

                    return [
                        'country' => $bucket['country'],
                        'country_code' => $bucket['country_code'],
                        'flag_url' => $bucket['flag_url'],
                        'orders' => $bucket['orders'],
                        'revenue' => round($bucket['revenue'], 2),
                        'ip_count' => count($uniqueIps),
                        'share' => $totalCountryRevenue > 0 ? round(($bucket['revenue'] / $totalCountryRevenue) * 100, 1) : 0,
                    ];
                })
                ->sortByDesc('revenue')
                ->take(8)
                ->values()
                ->all();

            $totalLiveRevenue = array_sum(array_column($salesTrend, 'revenue'));
            $totalLiveOrders = array_sum(array_column($salesTrend, 'orders'));

            return [
                'salesTrend' => $salesTrend,
                'salesByCountry' => $salesByCountry,
                'liveRevenue' => $totalLiveRevenue,
                'liveOrders' => $totalLiveOrders,
                'generatedAt' => now()->toIso8601String(),
            ];
        });
    }

    public function liveData()
    {
        return response()->json($this->buildLiveDashboardPayload());
    }

    public function index()
    {
        // Market Info (Always fresh enough but cheap to fetch)
        $goldPrice = Setting::where('key', 'gold_price')->value('value') ?? 0;

        // Get LKR rate for display (Cached 1 hour)
        $lkrRate = Cache::remember('usd_lkr_rate', 3600, function () {
            try {
                $response = Http::timeout(5)->get('https://open.er-api.com/v6/latest/USD');
                return $response->json('rates.LKR', 325.0);
            } catch (\Exception $e) {
                return 325.0; // Fallback
            }
        });
        $goldPriceLkr = $goldPrice * $lkrRate;

        // Big Stats Block (Cached 10 mins)
        $stats = Cache::remember('admin_dashboard_stats', 600, function () {
            $totalOrders = Order::count();
            $totalProducts = Product::count();
            $totalCustomers = User::where('role', '!=', 'admin')->count();
            $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');

            $inventoryValue = Product::sum(DB::raw('price * stock'));
            $todayRevenue = Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total_price');
            $averageOrderValue = Order::where('payment_status', 'paid')->avg('total_price') ?? 0;
            $pendingPaymentsCount = Order::where('payment_status', 'pending')->count();
            $pendingPaymentsAmount = Order::where('payment_status', 'pending')->sum('total_price');
            $lowStockCount = Product::where('stock', '<=', 5)->count();
            $deadStockCount = Product::deadStock()->count();

            $customRequestsCount = CustomizationRequest::where('status', 'pending')->count();
            $giftCardsActiveCount = \App\Models\GiftCard::where('is_active', true)->where('balance', '>', 0)->count();
            $giftCardsTotalBalance = \App\Models\GiftCard::where('is_active', true)->sum('balance');

            $ordersByStatus = Order::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status')->toArray();
            $outOfStockBestSellers = Product::where('stock', '<=', 5)->orderBy('stock', 'asc')->take(4)->get();
            $deadStockItems = Product::deadStock()->orderBy('created_at', 'asc')->take(8)->get();

            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();
            $startLastWeek = now()->subWeek()->startOfWeek();
            $endLastWeek = now()->subWeek()->endOfWeek();

            $salesThisWeek = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('payment_status', 'paid')->sum('total_price');
            $salesLastWeek = Order::whereBetween('created_at', [$startLastWeek, $endLastWeek])->where('payment_status', 'paid')->sum('total_price');

            $salesGrowth = 0;
            if ($salesLastWeek > 0)
                $salesGrowth = (($salesThisWeek - $salesLastWeek) / $salesLastWeek) * 100;
            elseif ($salesThisWeek > 0)
                $salesGrowth = 100;

            $categoriesShare = [];
            if (Schema::hasTable('order_items')) {
                $categoriesShare = DB::table('order_items')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->select('categories.name', DB::raw('SUM(order_items.quantity) as total'))
                    ->groupBy('categories.id', 'categories.name')->orderByDesc('total')->take(5)->pluck('total', 'categories.name')->toArray();
            }

            $productsShare = [];
            if (Schema::hasTable('order_items')) {
                $productsShare = DB::table('order_items')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->select('products.name', DB::raw('SUM(order_items.quantity) as total'))
                    ->groupBy('products.id', 'products.name')->orderByDesc('total')->take(5)->pluck('total', 'products.name')->toArray();
            }

            $daysShare = Order::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')->where('payment_status', 'paid')
                ->groupBy('day')->orderByRaw("FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")->pluck('count', 'day')->toArray();

            $totalPayingCustomers = Order::where('payment_status', 'paid')->distinct('user_id')->count('user_id');
            $returningCustomers = 0;
            if ($totalPayingCustomers > 0) {
                $returningCustomers = DB::table('orders')->select('user_id')->where('payment_status', 'paid')->groupBy('user_id')->havingRaw('COUNT(*) > 1')->get()->count();
            }
            $customerRetentionShare = ['New' => $totalPayingCustomers - $returningCustomers, 'Returning' => $returningCustomers];

            // SQL-based birthday filtering (much faster)
            $recentBirthdays = User::whereNotNull('birthday')
                ->whereRaw("DATE_FORMAT(birthday, '%m-%d') BETWEEN DATE_FORMAT(NOW(), '%m-%d') AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 14 DAY), '%m-%d')")
                ->take(5)->get();

            return compact(
                'totalOrders',
                'totalProducts',
                'totalCustomers',
                'totalRevenue',
                'ordersByStatus',
                'inventoryValue',
                'todayRevenue',
                'averageOrderValue',
                'pendingPaymentsCount',
                'pendingPaymentsAmount',
                'lowStockCount',
                'deadStockCount',
                'customRequestsCount',
                'outOfStockBestSellers',
                'salesGrowth',
                'salesThisWeek',
                'categoriesShare',
                'productsShare',
                'daysShare',
                'customerRetentionShare',
                'deadStockItems',
                'recentBirthdays',
                'giftCardsActiveCount',
                'giftCardsTotalBalance'
            );
        });

        $latestOrders = Order::with('user')->latest()->take(5)->get();
        $livePayload = $this->buildLiveDashboardPayload();

        return view('admin.dashboard', array_merge($stats, [
            'latestOrders' => $latestOrders,
            'goldPrice' => $goldPrice,
            'goldPriceLkr' => $goldPriceLkr,
            'lkrRate' => $lkrRate,
            'salesTrend' => $livePayload['salesTrend'],
            'salesByCountry' => $livePayload['salesByCountry'],
            'liveRevenue' => $livePayload['liveRevenue'],
            'liveOrders' => $livePayload['liveOrders'],
            'liveGeneratedAt' => $livePayload['generatedAt'],
        ]));
    }

    public function updateGoldPrice(Request $request)
    {
        $request->validate([
            'gold_price_lkr' => 'required|numeric|min:0',
        ]);

        // Fetch live conversion rate
        try {
            $response = Http::timeout(10)->get('https://open.er-api.com/v6/latest/USD');
            $lkrRate = $response->json('rates.LKR', 325.0);
        } catch (\Exception $e) {
            $lkrRate = 325.0; // Fallback
        }

        // Convert LKR input to USD
        $newPrice = $request->gold_price_lkr / $lkrRate;

        $setting = Setting::where('key', 'gold_price')->first();
        $oldPrice = $setting ? $setting->value : 0;

        $delta = $newPrice - $oldPrice;
        if (abs($delta) > 0.0001) {
            // Find all categories that are gold-related
            $goldRootIds = Category::where('name', 'like', '%gold%')
                ->whereNull('parent_id')
                ->pluck('id')
                ->all();
            
            // If none found by name, try the specific slug we know
            if (empty($goldRootIds)) {
                $goldRootIds = Category::where('slug', 'gem-and-gold')->pluck('id')->all();
            }

            $goldCategoryIds = $this->collectDescendantCategoryIds($goldRootIds);
            $affectedProducts = $this->applyRateDeltaToCategories($goldCategoryIds, $newPrice, true);
        } else {
            $affectedProducts = 0;
        }

        Setting::updateOrCreate(
            ['key' => 'gold_price'],
            ['value' => $newPrice, 'group' => 'market']
        );

        return back()->with('success', 'Gold Price updated. LKR ' . number_format($request->gold_price_lkr, 2) . ' converted to $' . number_format($newPrice, 2) . ' (Rate: ' . $lkrRate . '). Updated ' . $affectedProducts . ' Gem+Gold products.');
    }
}
