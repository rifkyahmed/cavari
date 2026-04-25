<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>Order Confirmed | {{ config('app.name', 'Cavari') }}</title>

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

    <main class="flex-grow w-full max-w-7xl mx-auto pt-36 pb-24 px-6 md:px-12 flex items-center justify-center relative overflow-hidden">
        <!-- Decorative Glow -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/40 blur-3xl rounded-full pointer-events-none"></div>

        <div class="max-w-xl w-full text-center relative z-10">
            <!-- Checkmark Animation/Icon -->
            <div class="w-24 h-24 mx-auto mb-8 bg-green-500/10 rounded-full flex items-center justify-center text-green-500 animate-[bounce_1s_ease-in-out_infinite]">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="font-gloock text-4xl md:text-5xl lg:text-6xl text-gray-900 mb-6 tracking-tight">
                Thank You!
            </h1>
            
            <p class="font-instrument text-lg text-gray-600 mb-8 max-w-md mx-auto">
                Your luxury order **#{{ $order->id }}** has been placed successfully. A confirmation containing your invoice has been sent to **{{ $order->user->email ?? 'your email' }}**.
            </p>

            <div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-8 mb-10 shadow-[0_20px_40px_-12px_rgba(0,0,0,0.1)] text-left font-space-mono text-sm">
                <div class="flex flex-col md:flex-row justify-between pb-6 border-b border-black/5 mb-6 gap-6 md:gap-4">
                    <div class="md:w-1/2">
                        <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Paid</span>
                        <span class="text-xl font-gloock text-black">{{ \App\Helpers\CurrencyHelper::format($order->total_price) }}</span>
                    </div>
                    <div class="md:w-1/2 md:text-right">
                        <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Ship To</span>
                        <span class="block text-xs font-bold text-gray-800 mb-1">{{ $order->user->name }}</span>
                        <span class="block text-xs text-gray-500 max-w-[200px] md:ml-auto leading-relaxed">{{ $order->shipping_address }}</span>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <a href="{{ route('orders.public.invoice', $order->payment_link_uuid) }}" target="_blank" class="w-full flex items-center justify-center gap-3 bg-black text-white px-8 py-4 rounded-xl font-bold uppercase tracking-widest text-[10px] hover:bg-gray-800 transition-all transform hover:-translate-y-0.5 shadow-lg group">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Invoice
                    </a>
                    <a href="{{ route('orders.public.show', $order->payment_link_uuid) }}" target="_blank" class="w-full flex items-center justify-center gap-3 bg-white border border-gray-200 text-gray-900 px-8 py-4 rounded-xl font-bold uppercase tracking-widest text-[10px] hover:bg-gray-50 transition-all transform hover:-translate-y-0.5 shadow-sm group">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12H3m0 0l4-4m-4 4l4 4m13-8v8a2 2 0 01-2 2H9" />
                        </svg>
                        See Order
                    </a>
                    <a href="{{ route('products.index') }}" class="w-full flex items-center justify-center bg-white border border-gray-200 text-gray-900 px-8 py-4 rounded-xl font-bold uppercase tracking-widest text-[10px] hover:bg-gray-50 transition-all transform hover:-translate-y-0.5">
                        Continue Shopping
                    </a>
                </div>
            </div>

            <p class="font-instrument text-xs text-gray-400">
                Need help? <a href="{{ route('contact') }}" class="underline hover:text-black transition-colors">Contact our concierge</a>.
            </p>
        </div>
    </main>

    <x-footer />
    
    <x-toast />
</body>
</html>
