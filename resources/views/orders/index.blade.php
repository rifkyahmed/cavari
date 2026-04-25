<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>My Orders | {{ config('app.name', 'Cavari') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gloock&family=Instrument+Sans:wght@400;500;600&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-gloock { font-family: 'Gloock', serif; }
        .font-instrument { font-family: 'Instrument Sans', sans-serif; }
        .font-space-mono { font-family: 'Space Mono', monospace; }
        
        .bg-hero-gradient {
            background: linear-gradient(135deg, #fff 0%, #fff0f5 50%, #fff 100%);
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient flex flex-col min-h-screen">

    <x-navbar />

    <main class="flex-grow w-full max-w-5xl mx-auto pt-36 pb-24 px-6 md:px-12">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 border-b border-black/5 pb-8">
            <div>
                <h1 class="font-gloock text-5xl md:text-7xl leading-none mb-2">
                    Acquisitions
                </h1>
                <p class="font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">
                    Order History
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                 <span class="font-space-mono text-sm border border-black/10 px-3 py-1 rounded-full bg-white/50 backdrop-blur">
                    {{ $orders->count() }} Transactions
                 </span>
            </div>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <!-- Order Ledger Card -->
                    <div class="group relative bg-white/40 backdrop-blur-md border border-white/60 rounded-2xl overflow-hidden hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-0.5 transition-all duration-500">
                        
                        <!-- Card Header (Meta Info) -->
                        <div class="px-6 py-4 flex flex-wrap gap-4 justify-between items-center border-b border-white/50 bg-white/20">
                            <div class="flex gap-6 items-center">
                                <div>
                                    <span class="block font-space-mono text-[9px] uppercase tracking-widest text-gray-400 mb-0.5">Reference</span>
                                    <span class="font-space-mono text-sm font-bold text-black">#{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div>
                                    <span class="block font-space-mono text-[9px] uppercase tracking-widest text-gray-400 mb-0.5">Date</span>
                                    <span class="font-instrument text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100/50 text-gray-800 border border-gray-200">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="font-gloock text-xl">{{ \App\Helpers\CurrencyHelper::format($order->total_price) }}</span>
                            </div>
                        </div>

                        <!-- Card Body (Items Preview) -->
                        <div class="p-6 flex flex-col sm:flex-row justify-between items-center gap-6">
                            
                            <!-- Product Thumbnails (Overlap Stack) -->
                            <div class="flex -space-x-4 overflow-hidden py-2 pl-2">
                                    @foreach($order->items->take(4) as $item)
                                        <div class="relative w-16 h-16 rounded-lg border-2 border-white shadow-sm overflow-hidden bg-gray-100 transition-transform hover:-translate-y-2 hover:z-10 flex items-center justify-center text-gray-400">
                                            @php
                                                $images = $item->product ? $item->product->images : ($item->custom_details['images'] ?? []);
                                                $imageUrl = null;

                                                if (is_array($images) && count($images) > 0) {
                                                    $mainImage = $images[0];
                                                    if ($mainImage) {
                                                        if (\Illuminate\Support\Str::startsWith($mainImage, ['http', 'https'])) {
                                                            $imageUrl = $mainImage;
                                                        } elseif (\Illuminate\Support\Str::startsWith($mainImage, ['/storage/', 'storage/'])) {
                                                            $imageUrl = asset($mainImage);
                                                        } elseif (\Illuminate\Support\Str::startsWith($mainImage, 'images/')) {
                                                            $imageUrl = asset($mainImage);
                                                        } else {
                                                            $imageUrl = asset('storage/' . $mainImage);
                                                        }
                                                    }
                                                }
                                            @endphp
                                            
                                            @if($imageUrl)
                                                <img src="{{ $imageUrl }}" alt="Item" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 4)
                                        <div class="relative w-16 h-16 rounded-lg border-2 border-white shadow-sm overflow-hidden bg-gray-50 flex items-center justify-center z-0">
                                            <span class="font-space-mono text-xs text-gray-500 font-bold">+{{ $order->items->count() - 4 }}</span>
                                        </div>
                                    @endif
                            </div>

                            <!-- Action -->
                            <div class="flex flex-col gap-2">
                                @if($order->payment_link_uuid && $order->payment_status !== 'paid')
                                    <a href="{{ route('custom_orders.pay', $order->payment_link_uuid) }}" class="whitespace-nowrap px-6 py-3 rounded-xl bg-black text-white font-space-mono text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all duration-300 shadow-lg text-center">
                                        Complete Payment
                                    </a>
                                @endif
                                <a href="{{ route('orders.show', $order) }}" class="whitespace-nowrap px-6 py-3 rounded-xl border border-black/10 text-black font-space-mono text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all duration-300 text-center">
                                    View Ledger
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 flex justify-center">
                {{ $orders->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="py-24 flex flex-col items-center justify-center text-center">
                 <div class="w-20 h-20 bg-white/50 backdrop-blur rounded-full flex items-center justify-center mb-6 border border-white shadow-sm">
                     <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                 </div>
                <h2 class="font-gloock text-3xl mb-2 text-gray-900">No Acquisitions</h2>
                <p class="font-instrument text-gray-500 mb-8">Your history is currently empty.</p>
                <a href="{{ route('products.index') }}" class="font-space-mono text-[10px] font-bold uppercase tracking-widest border-b border-black pb-0.5 hover:text-gray-600 transition">
                    Browse Collection
                </a>
            </div>
        @endif

    </main>

    <x-footer />

</body>
</html>
