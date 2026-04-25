<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        // 1. Total Revenue in Date Range
        $totalRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        // 2. Total Orders in Date Range
        $totalOrders = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // 3. Total Customers Signup in Date Range (or total unique buyers)
        $newCustomers = User::where('role', 'user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // 4. Top Selling Products (grouped from order_items)
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('products.name', 'products.slug', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.quantity * order_items.price) as revenue'))
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 5. Daily Revenue for Chart/List (Last X days based on selection)
        // Adjust grouping depending on date range length. Let's do daily for now.
        $dailyRevenueRaw = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as sum')
        )
        ->where('payment_status', 'paid')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get()
        ->keyBy('date');

        $dailyRevenue = [];
        $currentDate = $startDate->copy();
        while($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $dailyRevenue[] = (object) [
                'date' => $dateString,
                'sum' => isset($dailyRevenueRaw[$dateString]) ? $dailyRevenueRaw[$dateString]->sum : 0
            ];
            $currentDate->addDay();
        }

        $dailyRevenue = collect($dailyRevenue);

        return view('admin.analytics.index', compact(
            'startDate', 
            'endDate', 
            'totalRevenue', 
            'totalOrders', 
            'newCustomers', 
            'topProducts', 
            'dailyRevenue'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $orders = Order::with(['user', 'orderItems.product'])
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $response = new StreamedResponse(function() use ($orders) {
            $handle = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($handle, [
                'Order ID',
                'Date',
                'Customer Name',
                'Customer Email',
                'Total Price',
                'Status',
                'Items (Qty x Product)',
                'Shipping Address'
            ]);

            foreach ($orders as $order) {
                $itemsStr = $order->orderItems->map(function($item) {
                    return $item->quantity . 'x ' . ($item->product->name ?? 'Deleted Product');
                })->implode(' | ');

                fputcsv($handle, [
                    $order->id,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->user->name ?? 'Guest',
                    $order->user->email ?? 'N/A',
                    '$' . number_format($order->total_price, 2),
                    $order->status,
                    $itemsStr,
                    $order->shipping_address
                ]);
            }

            fclose($handle);
        });

        $filename = 'analytics_orders_' . $startDate->format('Ymd') . '_to_' . $endDate->format('Ymd') . '.csv';
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
