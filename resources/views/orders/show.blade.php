<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>Ledger #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }} | {{ config('app.name', 'Cavari') }}</title>

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
        
        <!-- Header & Nav -->
        <div class="mb-12">
            <a href="{{ isset($publicAccess) && $publicAccess ? route('home') : route('orders.index') }}" class="inline-flex items-center gap-2 font-space-mono text-[10px] uppercase tracking-widest text-gray-500 hover:text-black transition-colors mb-8 group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                {{ isset($publicAccess) && $publicAccess ? 'Return to Store' : 'Return to Acquisitions' }}
            </a>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-black/5">
                <div>
                    <h1 class="font-gloock text-4xl md:text-5xl lg:text-6xl leading-none text-black mb-2">
                        Ledger
                    </h1>
                    <p class="font-space-mono text-sm font-bold tracking-widest text-gray-400">
                        #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if(isset($publicAccess) && $publicAccess)
                        <a href="{{ $publicInvoiceUrl }}" target="_blank" class="px-6 py-3 bg-black text-white rounded-full font-space-mono text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-lg flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Invoice
                        </a>
                    @else
                        @if($order->payment_link_uuid && $order->payment_status !== 'paid')
                            <a href="{{ route('user.orders.invoice', $order->id) }}" class="px-6 py-3 bg-red-50 text-red-600 rounded-full font-space-mono text-[10px] font-bold uppercase tracking-widest hover:bg-red-100 transition shadow-sm flex items-center gap-2 border border-red-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Invoice (Requires Payment)
                            </a>
                        @else
                            <a href="{{ route('user.orders.invoice', $order->id) }}" target="_blank" class="px-6 py-3 bg-black text-white rounded-full font-space-mono text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-lg flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Invoice
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Col: Items -->
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-8 shadow-[0_20px_40px_-12px_rgba(0,0,0,0.04)]">
                    <h3 class="font-space-mono text-xs font-bold uppercase tracking-widest text-black mb-6 border-b border-black/5 pb-4">Secured Artifacts</h3>
                    
                    <div class="space-y-6 mt-6">
                            @foreach($order->items as $item)
                                @php
                                    $images = $item->product ? $item->product->images : ($item->custom_details['images'] ?? []);
                                    $itemName = $item->product ? $item->product->name : ($item->custom_name ?? 'Custom Item');
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
                                <div class="flex items-start gap-4 pb-6 border-b border-black/5 last:border-0 last:pb-0">
                                    <!-- Thumbnail -->
                                    <div class="w-20 h-20 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden border border-white shadow-sm hover:-translate-y-1 transition max-h-20 max-w-20 flex items-center justify-center text-gray-400">
                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}" alt="{{ $itemName }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @endif
                                    </div>
                                    
                                    <!-- Details -->
                                    <div class="flex-grow min-w-0 pt-1">
                                        <h4 class="font-instrument text-base font-medium text-black truncate">{{ $itemName }}</h4>
                                        <div class="font-space-mono text-[10px] text-gray-400 mt-1 uppercase tracking-widest">
                                            Qty: {{ $item->quantity }}
                                        </div>
                                        <div class="font-space-mono text-[9px] text-gray-500 mt-2 flex flex-wrap gap-x-3 gap-y-1">
                                            @if($item->product)
                                                @if($item->product->metal) <span class="uppercase">{{ $item->product->metal }}</span> @endif
                                                @if($item->product->gemstone_type) <span class="uppercase">{{ $item->product->gemstone_type }}</span> @endif
                                            @elseif($item->custom_details)
                                                @php $details = $item->custom_details; @endphp
                                                @if(!empty($details['product_type'])) <span class="uppercase">{{ str_replace('_', ' ', $details['product_type']) }}</span> @endif
                                                @if(!empty($details['gold_weight'])) <span class="uppercase">GOLD: {{ $details['gold_weight'] }}G</span> @endif
                                                @if(!empty($details['gem_weight'])) <span class="uppercase">GEM: {{ $details['gem_weight'] }}CT</span> @endif
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Price -->
                                    <div class="text-right pt-1 whitespace-nowrap">
                                        <span class="block font-instrument text-sm font-medium text-black">{{ \App\Helpers\CurrencyHelper::format($item->price * $item->quantity) }}</span>
                                        <span class="block font-space-mono text-[9px] text-gray-400 mt-1 uppercase text-nowrap">{{ \App\Helpers\CurrencyHelper::format($item->price) }} unit</span>
                                    </div>
                                </div>
                            @endforeach
                    </div>
                </div>

            </div>

            <!-- Right Col: Summary -->
            <div class="space-y-6">
                <!-- Status & Dates -->
                <div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-8 shadow-[0_20px_40px_-12px_rgba(0,0,0,0.04)] hover:-translate-y-1 transition duration-500">
                    <h3 class="font-space-mono text-xs font-bold uppercase tracking-widest text-black mb-6">Status Overview</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <span class="block font-space-mono text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-2">Fulfillment</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-black text-white">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <span class="block font-space-mono text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-2">Payment</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border border-black/10 text-gray-800">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div>
                            <span class="block font-space-mono text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-1">Purchased On</span>
                            <span class="font-instrument text-sm text-black">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Destination -->
                <div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-8 shadow-[0_20px_40px_-12px_rgba(0,0,0,0.04)] hover:-translate-y-1 transition duration-500">
                    <h3 class="font-space-mono text-xs font-bold uppercase tracking-widest text-black mb-6">Destination</h3>
                    <div class="font-instrument text-sm text-gray-700 leading-relaxed whitespace-pre-line break-words">
                        {{ $order->shipping_address }}
                    </div>
                </div>

                <!-- Totals -->
                <div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-8 shadow-[0_20px_40px_-12px_rgba(0,0,0,0.04)] hover:-translate-y-1 transition duration-500">
                    <h3 class="font-space-mono text-xs font-bold uppercase tracking-widest text-black mb-6">Summary</h3>
                    
                    @php
                        $subtotal = $order->items->sum(function($item) {
                            return $item->price * $item->quantity;
                        });
                        $discount = $order->discount ?? 0;
                    @endphp

                    <div class="space-y-4 font-space-mono text-xs pb-4 border-b border-black/10">
                        <div class="flex justify-between items-center text-gray-500">
                            <span class="uppercase tracking-widest">Subtotal</span>
                            <span class="text-sm">{{ \App\Helpers\CurrencyHelper::format($subtotal) }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between items-center text-green-600">
                                <span class="uppercase tracking-widest">Discount</span>
                                <span class="text-sm">-{{ \App\Helpers\CurrencyHelper::format($discount) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center text-gray-500">
                            <span class="uppercase tracking-widest">Shipping</span>
                            <span class="text-sm uppercase tracking-widest text-green-600">Complimentary</span>
                        </div>
                    </div>
                    
                    <div class="pt-4 flex justify-between items-center text-black font-bold">
                        <span class="uppercase tracking-widest font-space-mono text-xs font-bold">Total Settled</span>
                        <span class="font-gloock text-2xl font-normal">{{ \App\Helpers\CurrencyHelper::format($order->total_price) }}</span>
                    </div>

                    @if(!isset($publicAccess) && $order->payment_link_uuid && $order->payment_status !== 'paid')
                        <div class="mt-8 pt-6 border-t border-black/10">
                            <a href="{{ route('custom_orders.pay', $order->payment_link_uuid) }}" class="flex justify-center items-center w-full px-6 py-4 bg-black text-white font-space-mono text-xs font-bold uppercase tracking-[0.2em] rounded-xl hover:bg-zinc-900 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                Complete Payment
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>

    </main>

    <x-footer />
    
    <x-toast />
</body>
</html>
