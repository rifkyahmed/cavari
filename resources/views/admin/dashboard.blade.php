@extends('layouts.admin')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-bold font-space-mono text-gray-800">Dashboard</h1>
        <span class="text-sm text-gray-500">{{ now()->format('F j, Y, g:i a') }}</span>
    </div>

    <div class="flex items-center space-x-3 bg-white/60 p-2 rounded-xl shadow-sm border border-orange-100 backdrop-blur-sm">
        <div class="flex flex-col items-end mr-2 text-right">
            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest underline decoration-orange-200 decoration-2 underline-offset-4 mb-1">Gold Market (1g)</span>
            <div class="flex flex-col items-end">
                <span class="text-xs font-space-mono font-bold text-gray-400 flex items-center gap-1">
                    <span class="w-1 h-1 rounded-full bg-orange-400"></span>
                    ${{ number_format($goldPrice ?? 0, 2) }}
                </span>
                <span class="text-lg font-space-mono font-bold text-orange-600 leading-tight">Rs.{{ number_format($goldPriceLkr ?? 0, 0) }}</span>
            </div>
        </div>
        <form action="{{ route('admin.dashboard.update-gold') }}" method="POST" class="flex flex-col items-stretch">
            @csrf
            <div class="flex items-stretch">
                <div class="relative group">
                    <input id="goldPriceInput" type="number" name="gold_price_lkr" step="0.01" 
                           class="w-24 px-2 py-1.5 text-xs border border-gray-200 rounded-l-lg focus:outline-none focus:border-orange-300 bg-white/80 font-mono text-right pr-7" 
                           placeholder="Price" required>
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[8px] font-bold text-gray-400 group-focus-within:text-orange-500">LKR</span>
                </div>
                <button id="goldPriceButton" type="submit" class="bg-gradient-to-r from-orange-400 to-red-400 text-white px-3 py-1.5 text-[10px] uppercase tracking-wider rounded-r-lg hover:from-orange-500 hover:to-red-500 font-bold transition-all shadow-sm flex items-center gap-1 disabled:opacity-60 disabled:cursor-not-allowed">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    Sync
                </button>
            </div>
            <p id="goldPriceHelper" class="mt-1 text-[10px] leading-tight text-gray-500 max-w-[220px] text-right">
                Enter a positive LKR value. USD preview updates live.
            </p>
        </form>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Revenue Card -->
    <div class="glass-panel p-6 flex flex-col justify-between h-32 relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <div class="z-10 relative">
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Total Revenue</h3>
            <p class="text-3xl font-space-mono font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="absolute right-[-20px] top-[-20px] w-24 h-24 bg-green-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute right-[-20px] bottom-[-20px] w-24 h-24 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
    </div>

    <!-- Orders Card -->
    <div class="glass-panel p-6 flex flex-col justify-between h-32 relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <div class="z-10 relative">
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Total Orders</h3>
            <p class="text-3xl font-space-mono font-bold text-gray-900">{{ $totalOrders }}</p>
        </div>
        <div class="absolute right-[-20px] top-[-20px] w-24 h-24 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
    </div>

    <!-- Products Card -->
    <div class="glass-panel p-6 flex flex-col justify-between h-32 relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <div class="z-10 relative">
             <h3 class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Active Products</h3>
            <p class="text-3xl font-space-mono font-bold text-gray-900">{{ $totalProducts }}</p>
        </div>
        <div class="absolute right-[-20px] top-[-20px] w-24 h-24 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
    </div>

    <!-- Customers Card -->
    <div class="glass-panel p-6 flex flex-col justify-between h-32 relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <div class="z-10 relative">
             <h3 class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Total Customers</h3>
            <p class="text-3xl font-space-mono font-bold text-gray-900">{{ $totalCustomers }}</p>
        </div>
         <div class="absolute right-[-20px] top-[-20px] w-24 h-24 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
    </div>
</div>

<!-- Business Health Rows -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
    <!-- Inventory Value -->
    <div class="glass-panel p-6 h-28 flex flex-col justify-center relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-1">Inventory Value</h3>
        <p class="text-lg font-space-mono font-bold text-gray-900">${{ number_format($inventoryValue, 0) }}</p>
        <div class="absolute right-[-10px] top-[-10px] w-16 h-16 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </div>
    
    <!-- Today Profit -->
    <div class="glass-panel p-6 h-28 flex flex-col justify-center relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-1">Today Profit</h3>
        <p class="text-lg font-space-mono font-bold text-gray-900">${{ number_format($todayRevenue, 0) }}</p>
         <div class="absolute right-[-10px] bottom-[-10px] w-16 h-16 bg-green-400 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </div>
 
    <!-- Avg Order Value -->
    <div class="glass-panel p-6 h-28 flex flex-col justify-center relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-1">Avg Order Value</h3>
        <p class="text-lg font-space-mono font-bold text-gray-900">${{ number_format($averageOrderValue, 0) }}</p>
        <div class="absolute right-[-10px] top-[-10px] w-16 h-16 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </div>
 
    <!-- Pending Payments -->
    <div class="glass-panel p-6 h-28 flex flex-col justify-center relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-1">Pending Payments</h3>
        <div class="flex items-baseline">
            <p class="text-lg font-space-mono font-bold text-gray-900 mr-2">{{ $pendingPaymentsCount }}</p>
            <p class="text-xs text-gray-500 font-medium">${{ number_format($pendingPaymentsAmount, 0) }}</p>
        </div>
         <div class="absolute right-[-10px] bottom-[-10px] w-16 h-16 bg-yellow-400 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </div>
 
    <!-- Low Stock Items -->
    <div class="glass-panel p-6 h-28 flex flex-col justify-center relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-1">Low Stock Items</h3>
        <p class="text-lg font-space-mono font-bold text-gray-900 {{ $lowStockCount > 0 ? 'text-red-600' : '' }}">{{ $lowStockCount }}</p>
         <div class="absolute right-[-10px] top-[-10px] w-16 h-16 bg-red-400 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
     <!-- Dead Stock Items -->
     <a href="{{ route('admin.products.index', ['dead_stock' => 1]) }}" class="glass-panel p-6 h-28 flex flex-col justify-center relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-1">Dead Stock</h3>
        <p class="text-lg font-space-mono font-bold text-gray-900 {{ $deadStockCount > 0 ? 'text-rose-600' : '' }}">{{ $deadStockCount }}</p>
         <div class="absolute right-[-10px] top-[-10px] w-16 h-16 bg-rose-300 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </a>
 
     <!-- Custom Orders Pending -->
    <div class="glass-panel p-6 h-28 flex flex-col justify-center relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-1">Custom Orders</h3>
        <p class="text-lg font-space-mono font-bold text-gray-900 {{ $customRequestsCount > 0 ? 'text-indigo-600' : '' }}">{{ $customRequestsCount }}</p>
         <div class="absolute right-[-10px] bottom-[-10px] w-16 h-16 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </div>

    <!-- Active Gift Cards -->
    <a href="{{ route('admin.gift-cards.index') }}" class="glass-panel p-6 h-28 flex flex-col justify-center relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-1">Gift Cards</h3>
        <div class="flex items-baseline">
            <p class="text-lg font-space-mono font-bold text-gray-900 mr-2">{{ $giftCardsActiveCount }}</p>
            <p class="text-xs text-gray-500 font-medium">${{ number_format($giftCardsTotalBalance, 0) }}</p>
        </div>
        <div class="absolute right-[-10px] top-[-10px] w-16 h-16 bg-rose-400 rounded-full mix-blend-multiply filter blur-xl opacity-20"></div>
    </a>

    <!-- Weekly Growth -->
    <div class="glass-panel p-6 h-28 flex flex-col justify-center relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h4 class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-1">Weekly Growth</h4>
        <div class="flex items-baseline">
            <span class="text-xl font-bold font-space-mono {{ $salesGrowth >= 0 ? 'text-green-600' : 'text-red-500' }}">
                {{ $salesGrowth > 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}%
            </span>
             <span class="text-[10px] text-gray-400 ml-1">vs last week</span>
        </div>
         <div class="absolute right-[-10px] top-[-10px] w-12 h-12 bg-green-100 rounded-full mix-blend-multiply filter blur-md opacity-50"></div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <div class="glass-panel p-6 xl:col-span-2">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800 font-space-mono">Live Sales Graph</h3>
                <p class="text-xs text-gray-500 mt-1">Rolling 14-day revenue view. Auto-refreshes every 30 seconds.</p>
            </div>
            <span id="liveDashboardStamp" class="inline-flex items-center gap-2 self-start sm:self-auto px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Live
            </span>
        </div>
        <div class="h-72 w-full">
            <canvas id="liveSalesChart"></canvas>
        </div>
    </div>

    <div class="glass-panel p-6">
        <div class="flex flex-col gap-2 mb-4">
            <h3 class="text-lg font-bold text-gray-800 font-space-mono">Sales by Country</h3>
            <p class="text-xs text-gray-500">Grouped from the buyer IP on each paid order.</p>
        </div>
        <div class="rounded-3xl border border-white/70 bg-white/50 overflow-hidden shadow-sm">
            <div class="grid grid-cols-12 gap-3 px-5 py-3 border-b border-gray-100 bg-white/70 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 font-space-mono">
                <div class="col-span-5">Country</div>
                <div class="col-span-2 text-center">Sales</div>
                <div class="col-span-3 text-right">Value</div>
                <div class="col-span-2 text-right">IPs</div>
            </div>
            <div id="countrySalesList" class="divide-y divide-gray-100 max-h-80 overflow-y-auto"></div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Chart Section -->
    <div class="glass-panel p-6 lg:col-span-1">
        <h3 class="text-lg font-bold text-gray-800 mb-4 font-space-mono">Orders Overview</h3>
        <div class="relative h-64 w-full flex justify-center items-center">
            <canvas id="orderStatusChart"></canvas>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="glass-panel p-6 lg:col-span-2 overflow-hidden">
        <div class="flex justify-between items-center mb-4">
             <h3 class="text-lg font-bold text-gray-800 font-space-mono">Recent Orders</h3>
             <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">View All</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                        <th class="pb-3 pl-2">Order ID</th>
                        <th class="pb-3">Customer</th>
                        <th class="pb-3">Total</th>
                        <th class="pb-3">Payment</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right pr-2">Date</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($latestOrders as $order)
                        <tr class="hover:bg-white/40 transition-colors">
                            <td class="py-3 pl-2 font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="py-3 text-gray-600">{{ $order->user ? $order->user->name : 'Guest' }}</td>
                            <td class="py-3 font-semibold text-gray-900">${{ $order->total_price }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 text-xs font-bold rounded-full 
                                    {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 
                                       ($order->payment_status === 'pending' ? 'bg-amber-100 text-amber-800' : 
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($order->payment_status ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-1 text-xs font-bold rounded-full 
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-3 text-right pr-2 text-gray-500">{{ $order->created_at->format('M d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Sales Insights Snapshot -->
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Top Category -->
    <div class="glass-panel p-6 flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h4 class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-1">Top Categories</h4>
        <div class="h-40 w-full relative flex justify-center">
             <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <!-- Top Product -->
    <div class="glass-panel p-6 flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h4 class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-1">Top Products</h4>
         <div class="h-40 w-full relative flex justify-center">
             <canvas id="productChart"></canvas>
        </div>
    </div>

    <!-- Peak Sales Day (Day Distribution) -->
    <div class="glass-panel p-6 flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h4 class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-1">Sales by Day</h4>
         <div class="h-40 w-full relative flex justify-center">
             <canvas id="dayChart"></canvas>
        </div>
    </div>

    <!-- Repeat Customer Rate -->
    <div class="glass-panel p-6 flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
        <h4 class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-1">Retention</h4>
        <div class="h-40 w-full relative flex justify-center">
             <canvas id="retentionChart"></canvas>
        </div>
    </div>
</div>

<!-- Alerts Section -->
@if(isset($outOfStockBestSellers) && $outOfStockBestSellers->isNotEmpty())
<div class="mb-8">
    <div class="flex items-center mb-4">
        <div class="w-1.5 h-6 bg-red-500 rounded-full mr-3"></div>
        <h2 class="text-lg font-bold font-space-mono text-gray-800 uppercase tracking-wider">Attention Needed</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($outOfStockBestSellers as $product)
            <div class="glass-panel p-4 flex items-center relative overflow-hidden border-l-4 border-red-500 group hover:bg-red-50/30 transition-colors">
                <!-- Image -->
                 <div class="w-16 h-16 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0 mr-4 shadow-sm border border-white/50">
                    @php 
                        $imagePath = null;
                        $rawImages = $product->images;
                        
                        if (is_array($rawImages) && count($rawImages) > 0) {
                            $imagePath = $rawImages[0];
                        } elseif (is_string($rawImages)) {
                            $decoded = json_decode($rawImages, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && count($decoded) > 0) {
                                $imagePath = $decoded[0];
                            } elseif (!empty($rawImages)) {
                                // Assume plain string path if not valid JSON
                                $imagePath = $rawImages;
                            }
                        }
                    @endphp
                    
                    @if($imagePath)
                        @php
                            // Handle external URLs
                            if (Str::startsWith($imagePath, ['http', 'https'])) {
                                $finalUrl = $imagePath;
                            } else {
                                // Clean local path
                                $cleanPath = str_replace('\\', '/', $imagePath); 
                                $cleanPath = str_replace('public/', '', $cleanPath); // Remove 'public/' if present
                                $cleanPath = ltrim($cleanPath, '/');
                                
                                // Specific check for seeder images in public/images
                                if (Str::startsWith($cleanPath, 'images/')) {
                                    $finalUrl = asset($cleanPath);
                                } 
                                // Prevent double 'storage/'
                                elseif (Str::startsWith($cleanPath, 'storage/')) {
                                    $finalUrl = asset($cleanPath);
                                } else {
                                    $finalUrl = asset('storage/' . $cleanPath);
                                }
                            }
                        @endphp
                        <img src="{{ $finalUrl }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/80?text=No+Img';">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-gray-900 truncate" title="{{ $product->name }}">{{ Str::limit($product->name, 20) }}</h4>
                    <p class="text-xs text-gray-500 mt-1 mb-2">
                        Stock Left: 
                        <span class="font-mono font-bold {{ $product->stock == 0 ? 'text-red-600' : 'text-red-600' }}">
                            {{ $product->stock }}
                        </span>
                    </p>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-[10px] font-bold uppercase tracking-wider rounded-md hover:bg-red-200 transition-colors">
                        Restock Now 
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
 
 <!-- Dead Stock Alerts -->
 <div class="mb-8">
     <div class="flex items-baseline justify-between mb-4">
         <div class="flex items-baseline">
             <div class="w-1.5 h-6 bg-rose-500 rounded-full mr-3 translate-y-1"></div>
             <h2 class="text-2xl font-gloock text-gray-800">Dead Stock 
                 <span class="text-xs font-space-mono font-normal text-rose-400 normal-case ml-2">(Unsold > 90 days)</span>
             </h2>
         </div>
         @if(isset($deadStockItems) && $deadStockItems->isNotEmpty())
            <a href="{{ route('admin.products.index', ['dead_stock' => 1]) }}" class="text-rose-600 hover:text-rose-800 text-[10px] uppercase font-bold tracking-widest border-b border-rose-200 pb-0.5">View All Collection</a>
         @endif
     </div>
     
     @if(isset($deadStockItems) && $deadStockItems->isNotEmpty())
     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
         @foreach($deadStockItems as $product)
             <div class="glass-panel p-4 flex items-center relative overflow-hidden border-l-2 border-rose-300 group hover:bg-rose-50/20 transition-all duration-500">
                 <!-- Image -->
                 <div class="w-16 h-16 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0 mr-4 shadow-sm border border-white/50">
                    @php 
                        $imagePath = null;
                        $rawImages = $product->images;
                        
                        if (is_array($rawImages) && count($rawImages) > 0) {
                            $imagePath = $rawImages[0];
                        } elseif (is_string($rawImages)) {
                            $decoded = json_decode($rawImages, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && count($decoded) > 0) {
                                $imagePath = $decoded[0];
                            } elseif (!empty($rawImages)) {
                                $imagePath = $rawImages;
                            }
                        }
                    @endphp
                    
                    @if($imagePath)
                        @php
                            if (Str::startsWith($imagePath, ['http', 'https'])) {
                                $finalUrl = $imagePath;
                            } else {
                                $cleanPath = str_replace(['\\', 'public/'], ['/', ''], $imagePath);
                                $cleanPath = ltrim($cleanPath, '/');
                                if (Str::startsWith($cleanPath, ['images/', 'storage/'])) {
                                    $finalUrl = asset($cleanPath);
                                } else {
                                    $finalUrl = asset('storage/' . $cleanPath);
                                }
                            }
                        @endphp
                        <img src="{{ $finalUrl }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/80?text=No+Img';">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>
 
                 <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-gray-900 truncate" title="{{ $product->name }}">{{ Str::limit($product->name, 18) }}</h4>
                    <p class="text-[10px] text-gray-500 mt-1 uppercase font-space-mono tracking-tighter italic">Listed: {{ $product->created_at->format('M d, Y') }}</p>
                    <div class="flex justify-between items-end mt-2">
                        <span class="text-xs font-bold text-gray-900 font-mono italic">Qty: {{ $product->stock }}</span>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-[9px] font-bold text-rose-600 hover:text-rose-800 uppercase tracking-[0.2em] border-b border-rose-100 pb-0.5">Manage Details</a>
                    </div>
                 </div>
             </div>
         @endforeach
     </div>
     @else
     <div class="bg-gradient-to-br from-rose-50/50 to-white/50 border border-rose-100 border-dashed rounded-2xl p-10 text-center">
         <div class="w-14 h-14 bg-rose-100/50 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
            <svg class="w-7 h-7 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
         </div>
         <p class="font-gloock text-xl text-gray-800">Perfect Inventory Health</p>
         <p class="text-gray-400 text-xs mt-2 font-instrument tracking-wide uppercase">All creations have moved in the last 90 days cycle.</p>
     </div>
     @endif
 </div>



<!-- Recent Birthdays Alerts -->
@if(isset($recentBirthdays) && $recentBirthdays->isNotEmpty())
<div class="mb-8">
    <div class="flex items-center mb-4">
        <div class="w-1.5 h-6 bg-pink-500 rounded-full mr-3"></div>
        <h2 class="text-lg font-bold font-space-mono text-gray-800 uppercase tracking-wider">Recent & Upcoming Birthdays</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($recentBirthdays as $user)
            <div class="glass-panel p-4 flex flex-col items-start relative overflow-hidden border-l-4 border-pink-400 group hover:bg-pink-50/30 transition-colors">
                 <div class="flex items-center w-full mb-3">
                    <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 font-bold mr-3 flex-shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 truncate" title="{{ $user->name }}">{{ $user->name }}</h4>
                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                 </div>
                 
                 <div class="flex justify-between items-center w-full border-t border-gray-100 pt-3">
                     <span class="text-xs font-bold text-pink-600 font-mono">
                         {{ \Carbon\Carbon::parse($user->birthday)->format('M d') }}
                     </span>
                     <a href="{{ route('admin.customers.show', $user->id) }}" class="text-[10px] font-bold text-pink-600 hover:text-pink-800 uppercase tracking-widest border-b border-pink-200 pb-0.5">Send Offer</a>
                 </div>
            </div>
        @endforeach
    </div>
</div>
@endif


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const liveEndpoint = @json(route('admin.dashboard.live-data'));
        const liveStamp = document.getElementById('liveDashboardStamp');
        const goldInput = document.getElementById('goldPriceInput');
        const goldButton = document.getElementById('goldPriceButton');
        const goldHelper = document.getElementById('goldPriceHelper');
        const lkrRate = Number(@json($lkrRate ?? 325));
        const initialLiveGeneratedAt = @json($liveGeneratedAt ?? null);

        const moneyFormatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        const compactMoneyFormatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        });

        function formatUsd(value) {
            return '$' + moneyFormatter.format(Number(value || 0));
        }

        function formatLiveStamp(value) {
            if (!value) {
                return 'Updating live data...';
            }

            const parsedDate = new Date(value);
            if (Number.isNaN(parsedDate.getTime())) {
                return 'Updating live data...';
            }

            return 'Updated ' + parsedDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        function updateGoldValidation() {
            if (!goldInput || !goldButton || !goldHelper) {
                return;
            }

            const rawValue = goldInput.value.trim();
            const parsedValue = Number.parseFloat(rawValue);
            const isValid = rawValue !== '' && Number.isFinite(parsedValue) && parsedValue > 0;

            goldButton.disabled = !isValid;
            goldInput.setAttribute('aria-invalid', isValid ? 'false' : 'true');

            goldInput.classList.toggle('border-red-300', !isValid && rawValue !== '');
            goldInput.classList.toggle('border-emerald-300', isValid);

            if (rawValue === '') {
                goldHelper.textContent = 'Enter a positive LKR value. USD preview updates live.';
                goldHelper.className = 'mt-1 text-[10px] leading-tight text-gray-500 max-w-[220px] text-right';
                return;
            }

            if (!isValid) {
                goldHelper.textContent = 'Please enter a valid positive number.';
                goldHelper.className = 'mt-1 text-[10px] leading-tight text-red-600 max-w-[220px] text-right';
                return;
            }

            const estimatedUsd = parsedValue / lkrRate;
            goldHelper.textContent = 'Preview: ' + formatUsd(estimatedUsd) + ' at the current rate.';
            goldHelper.className = 'mt-1 text-[10px] leading-tight text-emerald-700 max-w-[220px] text-right';
        }

        if (goldInput) {
            goldInput.addEventListener('input', updateGoldValidation);
            updateGoldValidation();
        }

        const orderStatusContext = document.getElementById('orderStatusChart')?.getContext('2d');
        const statusData = @json($ordersByStatus);
        const statusLabels = Object.keys(statusData).length ? Object.keys(statusData) : ['No Data'];
        const statusValues = Object.keys(statusData).length ? Object.values(statusData) : [1];
        const statusColors = Object.keys(statusData).length ? ['#818cf8', '#34d399', '#f472b6', '#fbbf24', '#ef4444'] : ['#e5e7eb'];

        if (orderStatusContext) {
            new Chart(orderStatusContext, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        label: '# of Orders',
                        data: statusValues,
                        backgroundColor: statusColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                font: {
                                    family: "'Figtree', sans-serif",
                                    size: 12
                                }
                            }
                        }
                    },
                    cutout: '65%',
                }
            });
        }

        function createMiniChart(id, dataObj, colorPalette) {
            const element = document.getElementById(id);
            if (!element) return;

            const keys = Object.keys(dataObj);
            const values = Object.values(dataObj);
            const hasData = keys.length > 0 && values.some(v => v > 0);

            new Chart(element.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: hasData ? keys : ['No Data'],
                    datasets: [{
                        data: hasData ? values : [1],
                        backgroundColor: hasData ? colorPalette : ['#f3f4f6'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: hasData }
                    }
                }
            });
        }

        createMiniChart('categoryChart', @json($categoriesShare), ['#60a5fa', '#34d399', '#f472b6', '#fbbf24', '#a78bfa']);
        createMiniChart('productChart', @json($productsShare), ['#f87171', '#818cf8', '#fb923c', '#2dd4bf', '#e879f9']);
        createMiniChart('dayChart', @json($daysShare), ['#1e293b', '#334155', '#475569', '#64748b', '#94a3b8', '#cbd5e1', '#e2e8f0']);
        createMiniChart('retentionChart', @json($customerRetentionShare), ['#6366f1', '#ec4899']);

        const liveSalesContext = document.getElementById('liveSalesChart')?.getContext('2d');
        const countrySalesList = document.getElementById('countrySalesList');
        let liveSalesChart = null;

        function renderCountryList(rows) {
            if (!countrySalesList) {
                return;
            }

            if (!rows.length) {
                countrySalesList.innerHTML = '<div class="px-5 py-6 text-sm text-gray-500">No country data yet.</div>';
                return;
            }

            countrySalesList.innerHTML = rows.map(function (row, index) {
                const flagMarkup = row.flag_url
                    ? `<img src="${row.flag_url}" alt="${row.country}" class="w-8 h-5 rounded-sm object-cover border border-gray-100 shadow-sm flex-shrink-0">`
                    : `<div class="w-8 h-5 rounded-sm bg-gray-200 text-[9px] font-bold text-gray-500 flex items-center justify-center flex-shrink-0">${row.country.slice(0, 2).toUpperCase()}</div>`;

                return `
                    <div class="grid grid-cols-12 gap-3 items-center px-5 py-4 hover:bg-white/70 transition-colors">
                        <div class="col-span-5 flex items-center gap-3 min-w-0">
                            ${flagMarkup}
                            <div class="min-w-0">
                                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">Country:</p>
                                <p class="text-sm font-semibold text-slate-700 truncate">${row.country}</p>
                            </div>
                        </div>
                        <div class="col-span-2 text-center">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">Sales:</p>
                            <p class="text-sm font-semibold text-slate-700">${row.orders}</p>
                        </div>
                        <div class="col-span-3 text-right">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">Value:</p>
                            <p class="text-sm font-semibold text-slate-700">${formatUsd(row.revenue)}</p>
                        </div>
                        <div class="col-span-2 text-right">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">IPs:</p>
                            <p class="text-sm font-semibold text-slate-700">${row.ip_count}</p>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderLiveSalesChart(rows) {
            if (!liveSalesContext) {
                return;
            }

            const labels = rows.map(function (row) {
                return row.label || row.date;
            });
            const values = rows.map(function (row) {
                return Number(row.revenue || 0);
            });

            const gradient = liveSalesContext.createLinearGradient(0, 0, 0, 280);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.38)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.04)');

            if (liveSalesChart) {
                liveSalesChart.data.labels = labels;
                liveSalesChart.data.datasets[0].data = values;
                liveSalesChart.data.datasets[0].backgroundColor = gradient;
                liveSalesChart.update('none');
                return;
            }

            liveSalesChart = new Chart(liveSalesContext, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        data: values,
                        borderColor: '#2563eb',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: 'Space Mono', size: 12 },
                            bodyFont: { family: 'Instrument Sans', size: 13, weight: 'bold' },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function (context) {
                                    return 'Revenue: ' + formatUsd(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    family: 'Space Mono',
                                    size: 10
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(229, 231, 235, 0.8)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    family: 'Space Mono',
                                    size: 10
                                },
                                callback: function (value) {
                                    return '$' + compactMoneyFormatter.format(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        const initialSalesTrend = @json($salesTrend ?? []);
        const initialSalesByCountry = @json($salesByCountry ?? []);
        renderLiveSalesChart(initialSalesTrend);
        renderCountryList(initialSalesByCountry);
        if (liveStamp) {
            liveStamp.textContent = formatLiveStamp(initialLiveGeneratedAt);
        }

        async function refreshLiveDashboard() {
            if (!liveEndpoint) {
                return;
            }

            try {
                const response = await fetch(liveEndpoint, {
                    headers: {
                        Accept: 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Unable to refresh dashboard data');
                }

                const payload = await response.json();
                renderLiveSalesChart(payload.salesTrend || []);
                renderCountryList(payload.salesByCountry || []);

                if (liveStamp) {
                    liveStamp.textContent = formatLiveStamp(payload.generatedAt);
                }
            } catch (error) {
                if (liveStamp) {
                    liveStamp.textContent = 'Live data unavailable';
                }
            }
        }

        setInterval(refreshLiveDashboard, 30000);
    });
</script>
@endpush
