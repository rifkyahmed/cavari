<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>{{ $product->name }} | {{ config('app.name', 'Cavari') }}</title>
    <meta name="description"
        content="Buy {{ $product->name }}, an authentic {{ $product->gemstone_type ?? 'gemstone' }} piece from Cavari. {{ Str::limit(strip_tags($product->description), 120) }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    @php
        $ogImage = asset('images/og-image.png');
        if (!empty($product->images)) {
            $firstImg = $product->images[0];
            $ogImage = \Illuminate\Support\Str::startsWith($firstImg, ['http', 'https']) 
                ? $firstImg 
                : (\Illuminate\Support\Str::startsWith($firstImg, ['/storage/', 'storage/', 'images/']) 
                    ? asset($firstImg) 
                    : asset('storage/' . $firstImg));
        }
    @endphp

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $product->name }} | {{ config('app.name', 'Cavari') }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($product->description), 150) }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="Cavari">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $product->name }} | {{ config('app.name', 'Cavari') }}">
    <meta property="twitter:description" content="{{ Str::limit(strip_tags($product->description), 150) }}">
    <meta property="twitter:image" content="{{ $ogImage }}">

    <!-- Product Schema JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "{{ $product->name }}",
      "image": [
        @if(!empty($product->images))
            @foreach($product->images as $image)
                "{{ asset($image) }}"{{ !$loop->last ? ',' : '' }}
            @endforeach
        @endif
      ],
      "description": "{{ strip_tags($product->description) }}",
      "sku": "{{ $product->id }}",
      "brand": {
        "@type": "Brand",
        "name": "Cavari"
      },
      "offers": {
        "@type": "Offer",
        "url": "{{ request()->url() }}",
        "priceCurrency": "USD",
        "price": "{{ $product->price }}",
        "availability": "https://schema.org/InStock",
        "itemCondition": "https://schema.org/NewCondition"
      }
    }
    </script>

    <!-- Organization Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Cavari",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/logo.png') }}",
      "description": "Exquisite gems and luxury jewelry perfect for every occasion.",
      "sameAs": [
        "https://www.instagram.com/cavari",
        "https://www.pinterest.com/cavari"
      ]
    }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Gloock&family=Instrument+Sans:wght@400;500;600&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-gloock {
            font-family: 'Gloock', serif;
        }

        .font-instrument {
            font-family: 'Instrument Sans', sans-serif;
        }

        .font-space-mono {
            font-family: 'Space Mono', monospace;
        }

        /* Shared Background Gradient */
        .bg-hero-gradient {
            background: linear-gradient(135deg, #fff 0%, #fff0f5 50%, #fff 100%);
        }

        /* Hide Scrollbar for Gallery */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Responsive Heading for 1050px */
        @media (max-width: 1050px) {
            .product-title {
                font-size: 2.25rem !important;
                /* Equivalent to text-4xl */
            }
        }
    </style>
</head>

<body class="font-instrument antialiased text-gray-900 bg-hero-gradient flex flex-col min-h-screen">

    <x-navbar />

    <main class="flex-grow pt-32 pb-24 px-6 md:px-12 w-full max-w-[1600px] mx-auto">

        <!-- Breadcrumbs -->
        <nav class="flex items-center space-x-2 text-xs font-space-mono uppercase tracking-widest text-gray-400 mb-12">
            <a href="{{ route('home') }}" class="hover:text-black transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-black transition-colors">Shop</a>
            <span>/</span>
            <span class="text-black">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24 items-start">

            <!-- Left: Gallery (Sticky) -->
            @php
                // Resolve first image URL for Alpine initial state
                $firstImgUrl = '';
                if (!empty($product->images)) {
                    $img = $product->images[0];
                    $firstImgUrl = \Illuminate\Support\Str::startsWith($img, ['http', 'https'])
                        ? $img
                        : (\Illuminate\Support\Str::startsWith($img, ['/storage/', 'storage/', 'images/'])
                            ? asset($img)
                            : asset('storage/' . $img));
                }

                // Resolve video URL
                $productVideoUrl = '';
                if (!empty($product->video)) {
                    $productVideoUrl = $product->video;

                    // Resolve asset path if local
                    if (!\Illuminate\Support\Str::startsWith($productVideoUrl, ['http', 'https'])) {
                        $productVideoUrl = (\Illuminate\Support\Str::startsWith($productVideoUrl, ['/storage/', 'storage/']))
                            ? asset($productVideoUrl)
                            : asset('storage/' . $productVideoUrl);
                    }

                    // Cloudinary Optimization (Compression)
                    if (\Illuminate\Support\Str::contains($productVideoUrl, 'cloudinary.com')) {
                        if (!\Illuminate\Support\Str::contains($productVideoUrl, '/q_auto')) {
                            $productVideoUrl = str_replace('/upload/', '/upload/q_auto,f_auto/', $productVideoUrl);
                        }

                        // Ensure browser-friendly extension
                        if (!\Illuminate\Support\Str::endsWith($productVideoUrl, ['.mp4', '.webm', '.ogg'])) {
                            $productVideoUrl .= '.mp4';
                        }
                    }
                }

                $hasVideo = !empty($productVideoUrl);
            @endphp

            <div class="lg:col-span-6 lg:sticky lg:top-32" x-data="{
                     activeType: '{{ $hasVideo ? 'video' : 'image' }}',
                     activeImage: '{{ $firstImgUrl }}',
                     videoLoaded: false,
                     toggleMedia(type, img = null) {
                        this.activeType = type;
                        if (img) this.activeImage = img;
                        const v = document.getElementById('main-product-video');
                        if (type === 'video' && v) { 
                            v.currentTime = 0;
                            v.play().catch(()=>{}); 
                        } else { 
                            v?.pause(); 
                        }
                     }
                 }" x-init="() => {
    const v = document.getElementById('main-product-video');
    if (v) {
        // 1. Mute & set source from data‑src (bypasses CSP block)
        v.muted = true;
        v.defaultMuted = true;
        v.src = v.dataset.src;   // <-- key change

        // 2. Force a fresh load
        v.load();

        // 3. Persistent play loop (tries every 500 ms)
        let attempts = 0;
        const playInterval = setInterval(() => {
            attempts++;
            if (v.paused) {
                v.play().then(() => {
                    clearInterval(playInterval);
                    videoLoaded = true;
                }).catch(() => {});
            } else {
                clearInterval(playInterval);
                videoLoaded = true;
            }
        });
    }
}">

                <div class="grid gap-6">
                    <!-- Main Media Area -->
                    <div
                        class="aspect-square w-full max-h-[500px] bg-gray-50 rounded-2xl overflow-hidden relative group mx-auto shadow-sm">

                        {{-- Video Layer --}}
                        @if($hasVideo)
                            <div class="absolute inset-0 w-full h-full bg-transparent transition-opacity duration-500"
                                :class="activeType === 'video' ? 'opacity-100 z-20' : 'opacity-0 z-0'">
                                {{-- No more spinner - image shows through --}}

                                <video id="main-product-video" data-src="{{ $productVideoUrl }}" poster="{{ $firstImgUrl }}"
                                    muted loop playsinline crossorigin="anonymous" preload="none"
                                    @loadedmetadata="videoLoaded = true" @loadeddata="videoLoaded = true"
                                    @playing="videoLoaded = true" @canplay="$el.play().catch(()=>{}); videoLoaded = true"
                                    disablePictureInPicture oncontextmenu="return false;"
                                    class="w-full h-full object-cover"></video>

                                {{-- Unmute toggle --}}
                                <button type="button" onclick="toggleMute()" id="mute-btn"
                                    class="absolute bottom-4 right-4 z-30 bg-black/50 hover:bg-black/80 text-white rounded-full p-2 backdrop-blur-sm transition-all"
                                    title="Toggle sound">
                                    {{-- Icons --}}
                                    <svg id="icon-muted" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                    </svg>
                                    <svg id="icon-unmuted" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.536 8.464a5 5 0 010 7.072M12 6v12m-3.536-9.536a5 5 0 000 7.072M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                    </svg>
                                </button>
                            </div>
                        @endif

                        {{-- Image Layer --}}
                        <div class="absolute inset-0 w-full h-full transition-opacity duration-500"
                            :class="activeType === 'image' ? 'opacity-100 z-20' : 'opacity-0 z-0'">
                            @if(!empty($product->images))
                                <img :src="activeImage" src="{{ $firstImgUrl }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            @endif
                        </div>

                        <!-- Floating badge -->
                        <div
                            class="absolute bottom-6 left-6 bg-white/80 backdrop-blur-md px-4 py-2 rounded-full border border-white/50 z-10">
                            <span class="font-space-mono text-[10px] uppercase tracking-widest text-black">Authentic
                                {{ $product->gemstone_type ?? ($product->category->name ?? 'Gemstone') }}</span>
                        </div>
                    </div>

                    <!-- Thumbnails Strip -->
                    <div class="grid grid-cols-5 gap-3">

                        @if($hasVideo)
                            <button type="button" @click="toggleMedia('video')"
                                class="aspect-square bg-black rounded-xl overflow-hidden border-2 transition-all relative group/thumb"
                                :class="activeType === 'video' ? 'border-black shadow-md' : 'border-transparent hover:border-gray-400'">
                                <video src="{{ $productVideoUrl }}" muted preload="metadata"
                                    class="w-full h-full object-cover opacity-70 group-hover/thumb:opacity-90 transition-opacity"
                                    style="pointer-events:none;" disablePictureInPicture
                                    oncontextmenu="return false;"></video>

                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-8 h-8 bg-white/90 rounded-full flex items-center justify-center shadow">
                                        <svg class="w-4 h-4 text-black ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </div>
                                </div>
                            </button>
                        @endif

                        {{-- Image thumbnails --}}
                        @if(!empty($product->images))
                            @foreach($product->images as $image)
                                @php
                                    $thumbUrl = \Illuminate\Support\Str::startsWith($image, ['http', 'https'])
                                        ? $image
                                        : (\Illuminate\Support\Str::startsWith($image, ['/storage/', 'storage/', 'images/'])
                                            ? asset($image)
                                            : asset('storage/' . $image));
                                @endphp
                                <button type="button" @click="toggleMedia('image', '{{ $thumbUrl }}')"
                                    class="aspect-square bg-gray-50 rounded-xl overflow-hidden border-2 transition-all p-1.5"
                                    :class="(activeType === 'image' && activeImage === '{{ $thumbUrl }}') ? 'border-black shadow-md' : 'border-transparent hover:border-gray-400'">
                                    <img src="{{ $thumbUrl }}" alt="{{ $product->name }} view {{ $loop->iteration }}"
                                        loading="lazy" class="w-full h-full object-contain">
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>


            <!-- Right: Product Info -->
            <div class="lg:col-span-6 space-y-10">

                <!-- Header -->
                <div class="border-b border-black/10 pb-10">
                    <h1 class="font-gloock text-4xl md:text-5xl text-black leading-none mb-8 product-title">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-center justify-between items-end">
                        <div class="flex flex-col gap-1" x-data="{
                            usdPrice: {{ $product->price }},
                            usdOriginal: {{ $product->original_price ?? 0 }},
                            goldPriceUsd: {{ $goldPrice ?? 0 }},
                            selectedCurrency: 'USD',
                            isLive: false,
                            rates: {
                                'USD': { rate: 1, symbol: '$' },
                                'LKR': { rate: 325.50, symbol: 'Rs ' },
                                'AED': { rate: 3.6725, symbol: 'AED ' },
                                'INR': { rate: 82.85, symbol: '₹' },
                                'GBP': { rate: 0.79, symbol: '£' },
                                'EUR': { rate: 0.92, symbol: '€' },
                                'AUD': { rate: 1.52, symbol: 'A$' },
                                'CAD': { rate: 1.35, symbol: 'C$' }
                            },
                            symbols: {
                                'USD': '$', 'LKR': 'Rs ', 'AED': 'AED ',
                                'INR': '₹', 'GBP': '£', 'EUR': '€',
                                'AUD': 'A$', 'CAD': 'C$'
                            },
                            format(amount) {
                                let r = this.rates[this.selectedCurrency];
                                let val = amount * r.rate;
                                return r.symbol + val.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            },
                            async fetchRates() {
                                try {
                                    const res = await fetch('{{ route('api.exchange-rates') }}');
                                    if (!res.ok) return;
                                    const data = await res.json();
                                    for (const [cur, rate] of Object.entries(data.rates)) {
                                        if (this.rates[cur]) {
                                            this.rates[cur] = { rate: rate, symbol: this.symbols[cur] ?? cur + ' ' };
                                        }
                                    }
                                    this.isLive = data.is_live === true;
                                } catch(e) { /* keep fallback rates silently */ }
                            },
                            init() { this.fetchRates(); }
                        }">
                            <div class="flex items-center gap-3">
                                <span class="font-instrument text-3xl font-medium text-black" x-text="format(usdPrice)">
                                    {{ \App\Helpers\CurrencyHelper::format($product->price) }}
                                </span>
                                @if($product->original_price)
                                    <span class="font-instrument text-xl text-gray-400 line-through"
                                        x-text="format(usdOriginal)">
                                        {{ \App\Helpers\CurrencyHelper::format($product->original_price) }}
                                    </span>
                                @endif

                                <!-- Ultra-Luxury Currency Selector -->
                                <div class="ml-2 relative" x-data="{ open: false }" @click.away="open = false">
                                    <button @click="open = !open" type="button"
                                        class="group flex items-center gap-3 bg-white/40 backdrop-blur-md border border-black/5 py-2 px-5 rounded-full transition-all duration-500 hover:bg-white hover:border-black/20 hover:shadow-lg focus:outline-none">
                                        <span
                                            class="font-space-mono text-[11px] uppercase font-bold tracking-[0.2em] text-black/80 group-hover:text-black"
                                            x-text="selectedCurrency"></span>
                                        <svg class="w-2.5 h-2.5 transition-transform duration-500 text-black/30 group-hover:text-black/60"
                                            :class="{'rotate-180': open}" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <!-- Premium Glass Menu -->
                                    <div x-show="open"
                                        x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-500"
                                        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute left-0 mt-4 w-32 bg-white/80 backdrop-blur-xl border border-white shadow-[0_20px_50px_rgba(0,0,0,0.1)] rounded-2xl z-50 overflow-hidden py-2"
                                        style="display: none;">

                                        <div
                                            class="px-3 py-2 border-b border-black/5 mb-1 flex items-center justify-between">
                                            <span
                                                class="font-space-mono text-[9px] uppercase tracking-[0.2em] text-black/40 font-bold">Currency</span>
                                            <span x-show="isLive" class="flex items-center gap-1"
                                                title="Live exchange rates">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse inline-block"></span>
                                                <span
                                                    class="font-space-mono text-[8px] text-green-600 uppercase tracking-wider">Live</span>
                                            </span>
                                        </div>

                                        <template x-for="(data, currency) in rates" :key="currency">
                                            <button @click="selectedCurrency = currency; open = false" type="button"
                                                class="w-full flex items-center justify-between px-5 py-2.5 transition-all duration-300 group/item focus:outline-none"
                                                :class="selectedCurrency === currency ? 'bg-black/5' : 'hover:bg-black/5'">
                                                <span
                                                    class="font-space-mono text-[10px] uppercase font-bold tracking-widest transition-colors"
                                                    :class="selectedCurrency === currency ? 'text-black' : 'text-gray-400 group-hover/item:text-black'"
                                                    x-text="currency"></span>
                                                <div x-show="selectedCurrency === currency"
                                                    class="w-1 h-1 rounded-full bg-black"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <span class="font-space-mono text-[9px] text-gray-400 uppercase tracking-widest mt-1"
                                x-show="selectedCurrency !== 'USD'"
                                x-text="isLive ? '* Live rate. Checkout is processed in USD.' : '* Estimated rate. Checkout is processed in USD.'"
                                style="display: none;"></span>


                        </div>

                        <!-- Ratings -->
                        @php
                            $avgRating = $product->reviews->where('is_approved', 1)->avg('rating') ?? 0;
                            $count = $product->reviews->where('is_approved', 1)->count();
                        @endphp
                        <div class="flex items-center gap-2">
                            <div class="flex text-amber-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'fill-current' : 'text-gray-300 fill-current' }}"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="font-space-mono text-[10px] text-gray-500">({{ $count }}
                                {{ Str::plural('Review', $count) }})</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="font-instrument text-gray-600 leading-relaxed text-lg">
                    <p>{{ $product->description ?? 'An exquisite piece from our collection, featuring natural tones and expert craftsmanship. Perfect for the modern connoisseur.' }}
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Quantity Selector -->
                    <div class="flex items-center gap-6">
                        <span
                            class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-gray-500">Selection</span>
                        <div class="flex items-center border border-black/10 rounded-sm">
                            <button type="button" onclick="changeQuantity(-1)"
                                class="px-4 py-2 hover:bg-gray-50 transition">-</button>
                            <input type="number" name="quantity" id="product_quantity" value="1" min="1"
                                max="{{ $product->stock ?? 10 }}"
                                class="w-16 text-center border-x border-black/10 py-2 outline-none font-space-mono text-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none bg-transparent">
                            <button type="button" onclick="changeQuantity(1)"
                                class="px-4 py-2 hover:bg-gray-50 transition">+</button>
                        </div>
                        <span class="font-instrument text-xs text-gray-400 font-light">
                            @if($product->stock > 0)
                                @if($product->stock <= 5)
                                    <span class="text-red-600 font-bold">Low stock: only {{ $product->stock }} left!</span>
                                @else
                                    {{ $product->stock }} in stock
                                @endif
                            @else
                                Out of stock
                            @endif
                        </span>
                    </div>

                    <div class="flex gap-4">
                        <!-- Add to Cart (AJAX) -->
                        @php
                            $outOfStock = ($product->stock ?? 0) <= 0;
                        @endphp
                        @if($outOfStock)
                            <button type="button"
                                class="flex-1 py-4 px-8 bg-gray-400 text-gray-700 border border-gray-500 rounded-sm cursor-not-allowed"
                                disabled>
                                <span>Out of Stock</span>
                            </button>
                        @else
                            <button type="button" onclick="addToCart(this, {{ $product->id }})"
                                class="flex-1 py-4 px-8 font-space-mono text-xs font-bold uppercase tracking-widest transition-all duration-300 shadow-sm hover:shadow-md rounded-sm flex items-center justify-center gap-3 group border {{ $inCart ? 'bg-white text-black border-black hover:bg-gray-50' : 'bg-black text-white border-transparent hover:bg-gray-900' }}">
                                <span>{{ $inCart ? 'Remove from Cart' : 'Add to Cart' }}</span>
                                <span
                                    class="w-1.5 h-1.5 rounded-full group-hover:animate-pulse {{ $inCart ? 'bg-black' : 'bg-white' }}"></span>
                            </button>
                        @endif

                        <!-- Buy Now Button -->
                        <button type="button" onclick="buyNow({{ $product->id }})"
                            class="flex-1 py-4 px-8 bg-white border border-black text-black font-space-mono text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white hover:border-black transition-all duration-300 shadow-sm hover:shadow-md rounded-sm flex items-center justify-center">
                            <span>Buy Now</span>
                        </button>

                        <!-- Wishlist Button -->
                        @auth
                            @php
                                $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                            @endphp
                            <button type="button" onclick="addToWishlist(this, {{ $product->id }})"
                                class="w-14 flex-shrink-0 h-auto flex items-center justify-center border border-black transition-all duration-300 rounded-sm {{ $inWishlist ? 'text-red-600' : 'text-black hover:text-red-600' }}"
                                title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                <svg class="w-5 h-5 {{ $inWishlist ? 'fill-current' : 'fill-none' }}" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        @else
                            <button type="button" onclick="openAuthModal()"
                                class="w-14 flex-shrink-0 h-auto flex items-center justify-center border border-black transition-all duration-300 rounded-sm text-black hover:text-red-600"
                                title="Login to Add to Wishlist">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        @endauth
                    </div>

                    <!-- Trust Signals -->
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-white/40 p-3 rounded-lg border border-white/60">
                            <span
                                class="block font-space-mono text-[10px] font-bold uppercase text-gray-500 mb-1">Shipping</span>
                            <span class="font-instrument text-xs">Free Worldwide</span>
                        </div>

                        @if($product->certificate)
                            <a href="{{ asset($product->certificate) }}" target="_blank"
                                class="bg-white/40 p-3 rounded-lg border border-black/5 hover:border-black/20 hover:shadow-sm transition-all group block">
                                <span
                                    class="block font-space-mono text-[10px] font-bold uppercase text-gray-500 mb-1">Authenticity</span>
                                <span
                                    class="font-instrument text-xs text-black font-bold uppercase tracking-wider flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3 text-black/40" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Official Certificate
                                </span>
                            </a>
                        @else
                            <div class="bg-white/40 p-3 rounded-lg border border-black/5">
                                <span
                                    class="block font-space-mono text-[10px] font-bold uppercase text-gray-400 mb-1">Authenticity</span>
                                <span class="font-instrument text-xs text-black/60">Verified & Certified</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Details Accordion (Using Alpine for simple toggle if available, or pure CSS/Details) -->
                <div class="space-y-4 pt-8">

                    <!-- Details Item -->
                    <details class="group border-b border-black/10 pb-4 cursor-pointer">
                        <summary
                            class="flex items-center justify-between list-none font-space-mono text-xs font-bold uppercase tracking-widest text-black">
                            <span>Product Specifications</span>
                            <span class="transform group-open:rotate-180 transition text-gray-400">+</span>
                        </summary>
                        <div class="mt-4 grid grid-cols-2 gap-y-2 text-sm font-instrument text-gray-600">
                            <span>Gemstone:</span> <span
                                class="text-black">{{ $product->gemstone_type ?? 'N/A' }}</span>
                            @if($product->product_type === 'gem' || is_null($product->product_type))
                                <span>Weight:</span> <span class="text-black">{{ $product->weight ?? 'N/A' }} ct</span>
                                <span>Dimensions:</span> <span class="text-black">{{ $product->dimensions ?? 'N/A' }}</span>
                            @endif

                            @if($product->gold_weight > 0)
                                <span>Gold Weight:</span> <span class="text-black">{{ $product->gold_weight }} g</span>
                            @endif
                            @if($product->gem_weight > 0)
                                <span>Gem Weight:</span> <span class="text-black">{{ $product->gem_weight }} ct</span>
                            @endif
                            @if($product->size)
                                <span>Size:</span> <span class="text-black">{{ $product->size }}</span>
                            @endif

                            @if($product->origin)
                                <span>Origin:</span> <span class="text-black">{{ $product->origin }}</span>
                            @endif
                            @if($product->clarity)
                                <span>Clarity:</span> <span class="text-black">{{ $product->clarity }}</span>
                            @endif
                            @if($product->special_comments)
                                <span class="col-span-1">Special Comments:</span>
                                <span class="text-black col-span-1 break-words">{{ $product->special_comments }}</span>
                            @endif
                        </div>
                    </details>

                    <!-- Shipping Item -->
                    <!-- Shipping Item -->
                    <details class="group border-b border-black/10 pb-4 cursor-pointer">
                        <summary
                            class="flex items-center justify-between list-none font-space-mono text-xs font-bold uppercase tracking-widest text-black">
                            <span>Delivery & Returns</span>
                            <span class="transform group-open:rotate-180 transition text-gray-400">+</span>
                        </summary>
                        <div class="mt-4 text-sm font-instrument text-gray-600 leading-relaxed">
                            Complimentary shipping on all orders. Returns valid within 14 days of delivery.
                        </div>
                    </details>



                    @if($product->certificate)
                        <!-- Premium Certificate Section -->
                        <div
                            class="mt-12 p-8 bg-white/40 backdrop-blur-md border border-black/5 rounded-sm shadow-sm relative overflow-hidden group hover:-translate-y-1 hover:shadow-2xl hover:bg-white/60 transition-all duration-500">
                            <!-- Background Pattern/Seal -->
                            <div
                                class="absolute -top-4 -right-4 opacity-[0.03] group-hover:opacity-[0.07] transition-opacity duration-500 transform rotate-12">
                                <svg class="w-48 h-48 text-black" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                </svg>
                            </div>

                            <div class="relative z-10 flex flex-col md:flex-row gap-10 items-center">
                                @php
                                    $isImage = in_array(strtolower(pathinfo($product->certificate, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                @endphp

                                {{-- Certificate Preview with "Attached" look --}}
                                <div class="relative flex-shrink-0">
                                    {{-- Paperclip SVG - Nudged Left & Moves Down on Hover --}}
                                    <div
                                        class="absolute -top-2 -left-1.5 z-20 transition-transform duration-700 transform rotate-[-35deg] group-hover:rotate-[-45deg] group-hover:translate-y-0.5">
                                        <svg class="w-6 h-6 text-gray-400 drop-shadow-sm" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.5">
                                            <path d="M6 5v14a3 3 0 003 3h6a3 3 0 003-3V5a3 3 0 00-3-3H9a3 3 0 00-3 3z" />
                                            <path d="M9 2v14a3 3 0 003 3h0a3 3 0 003-3V2" />
                                        </svg>
                                    </div>

                                    @if($isImage)
                                        <div
                                            class="w-36 h-48 bg-white shadow-[0_10px_30px_rgba(0,0,0,0.08)] rounded-sm overflow-hidden border border-gray-100 rotate-1 group-hover:rotate-0 transition-transform duration-700 relative">
                                            <img src="{{ asset($product->certificate) }}" alt="Certificate Preview"
                                                class="w-full h-full object-cover">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/5 to-transparent pointer-events-none">
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="w-36 h-48 bg-white shadow-[0_10px_30px_rgba(0,0,0,0.08)] rounded-sm flex flex-col items-center justify-center border border-gray-100 rotate-1 group-hover:rotate-0 transition-transform duration-700">
                                            <svg class="w-12 h-12 text-gray-200 mb-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span
                                                class="font-space-mono text-[8px] uppercase tracking-widest text-gray-300">Digital
                                                Document</span>
                                        </div>
                                    @endif

                                    {{-- Authenticity Seal --}}
                                    <div
                                        class="absolute -bottom-4 -right-4 w-12 h-12 bg-white rounded-full shadow-lg border border-black/5 flex items-center justify-center z-20 transform group-hover:scale-110 transition-transform duration-500">
                                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex-1 text-center md:text-left">
                                    <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                                        <span class="w-2 h-2 rounded-full bg-black"></span>
                                        <span
                                            class="font-space-mono text-[9px] font-bold uppercase tracking-[0.25em] text-black">Authenticity
                                            Ledger</span>
                                    </div>
                                    <h3 class="font-gloock text-3xl text-black mb-4">Official Certification</h3>
                                    <p class="font-instrument text-gray-600 mb-8 leading-relaxed max-w-md">
                                        Every gemstone from our collection is scrutinized by master gemologists. This
                                        digital record serves as a permanent verification of its origin, cut, and purity.
                                    </p>
                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                                        <a href="{{ asset($product->certificate) }}" target="_blank"
                                            class="inline-flex items-center gap-3 px-8 py-4 bg-black text-white font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-md hover:shadow-xl rounded-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Inspect Certificate
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Inquiry Form for All Products -->
                <div
                    class="mt-12 bg-white/50 backdrop-blur-md p-8 border border-black/10 shadow-lg relative overflow-hidden rounded-sm">
                    <!-- Subtle background circle inside the card -->
                    <div
                        class="absolute -top-[10%] -right-[10%] w-[100px] h-[100px] rounded-full border border-black/5 blur-[1px] pointer-events-none">
                    </div>

                    <div class="mb-6 relative z-10">
                        <span
                            class="font-space-mono text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 block mb-2">Request
                            Information</span>
                        <h3 class="font-gloock text-3xl text-black leading-tight">Inquire About<br /><span
                                class="italic text-gray-500 text-2xl">this
                                {{ $product->product_type === 'gem' ? 'Gemstone' : 'Jewelry Piece' }}</span></h3>
                    </div>

                    @if(session('success'))
                        <div
                            class="mb-6 p-4 bg-green-50/50 border border-green-100 text-green-800 rounded-sm font-instrument text-sm flex items-start shadow-sm relative z-10">
                            <span class="leading-relaxed">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('shop.custom-design.submit') }}" method="POST"
                        class="space-y-8 relative z-10">
                        @csrf
                        <input type="hidden" name="type" value="other">

                        <!-- Name -->
                        <div class="relative group">
                            <input type="text" id="bespoke_name" name="name" required placeholder=" "
                                class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-base peer transition-all pb-2 px-0">
                            <label for="bespoke_name"
                                class="absolute left-0 bottom-2.5 font-space-mono text-[9px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-7 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-7 origin-left pointer-events-none">
                                Full Name
                            </label>
                            <div
                                class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="relative group">
                            <input type="email" id="bespoke_email" name="email" required placeholder=" "
                                class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-base peer transition-all pb-2 px-0">
                            <label for="bespoke_email"
                                class="absolute left-0 bottom-2.5 font-space-mono text-[9px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-7 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-7 origin-left pointer-events-none">
                                Email Address
                            </label>
                            <div
                                class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full">
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="relative group">
                            <input type="tel" id="bespoke_phone" name="phone" placeholder=" "
                                class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-base peer transition-all pb-2 px-0">
                            <label for="bespoke_phone"
                                class="absolute left-0 bottom-2.5 font-space-mono text-[9px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-7 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-7 origin-left pointer-events-none">
                                WhatsApp Number
                            </label>
                            <div
                                class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full">
                            </div>
                        </div>

                        <!-- Vision -->
                        <div class="relative group pt-2">
                            <textarea id="bespoke_vision" name="message" rows="3" required placeholder=" "
                                class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-base peer transition-all pb-2 px-0 resize-none">I would like to inquire about {{ $product->name }}. Here are my questions: </textarea>
                            <label for="bespoke_vision"
                                class="absolute left-0 top-1 font-space-mono text-[9px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-7 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-7 origin-left pointer-events-none">
                                Your Message / Inquiry
                            </label>
                            <div
                                class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full mt-4 group relative px-6 py-4 bg-black text-white hover:bg-gray-900 transition-colors overflow-hidden flex items-center justify-center rounded-sm">
                            <span
                                class="relative z-10 font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] flex items-center">
                                Request Consultation
                                <svg class="w-3 h-3 ml-2 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                            <div
                                class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500 ease-out">
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>



        <!-- Related Products Section -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="mt-32 border-t border-black/10 pt-24">
                <div class="flex items-end justify-between mb-12">
                    <h2 class="font-gloock text-4xl text-black">You May Also Admire</h2>
                    <a href="{{ route('products.index') }}"
                        class="font-space-mono text-xs font-bold uppercase tracking-widest underline underline-offset-4 hover:text-gray-600">View
                        All</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('products.show', $related->slug) }}" class="group block">
                            <div class="aspect-[4/5] bg-gray-100 mb-4 overflow-hidden rounded-sm relative">
                                @if(!empty($related->images))
                                    <img src="{{ asset($related->images[0]) }}"
                                        alt="{{ $related->name }} - {{ $related->category->name ?? 'Jewelry' }}" loading="lazy"
                                        class="w-full h-full object-cover lg:group-hover:scale-105 transition duration-700 opacity-90 lg:group-hover:opacity-100">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center text-gray-400 font-space-mono text-xs">
                                        No Image</div>
                                @endif
                                <div class="absolute bottom-4 left-4">
                                    <span
                                        class="bg-white px-2 py-1 font-space-mono text-[10px] uppercase tracking-widest text-black">{{ $related->category->name ?? 'Jewelry' }}</span>
                                </div>
                            </div>
                            <h3 class="font-gloock text-xl mb-1 group-hover:text-gray-600 transition">{{ $related->name }}</h3>
                            <p class="font-instrument text-gray-500">{{ \App\Helpers\CurrencyHelper::format($related->price) }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Redesigned Reviews Section: The Glass Ledger -->
        <div class="mt-32 border-t border-black/5 pt-24 pb-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-24 relative">

                <!-- Left: Title & Summary -->
                <div class="lg:col-span-1 space-y-8 lg:sticky lg:top-32 lg:self-start">
                    <div>
                        <span
                            class="font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 block mb-3">Community</span>
                        <h2 class="font-gloock text-5xl md:text-6xl text-black leading-none mb-6">Client<br /><span
                                class="italic text-gray-500">Admiration</span></h2>
                        <p class="font-instrument text-gray-600 text-lg leading-relaxed max-w-sm">
                            Real experiences from collectors who have made this piece part of their legacy.
                        </p>
                    </div>

                    <!-- Overall Rating Badge -->
                    @php
                        $avgRating = $product->reviews->where('is_approved', 1)->avg('rating') ?? 0;
                        $count = $product->reviews->where('is_approved', 1)->count();
                    @endphp
                    <div
                        class="inline-flex items-center gap-4 bg-white/60 backdrop-blur-sm border border-black/5 rounded-full px-6 py-3">
                        <span class="font-gloock text-3xl">{{ number_format($avgRating, 1) }}</span>
                        <div class="h-8 w-px bg-black/10"></div>
                        <div class="flex flex-col">
                            <div class="flex text-amber-400 text-[10px]">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= round($avgRating) ? 'fill-current' : 'text-gray-300 fill-current' }}"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                @endfor
                            </div>
                            <span
                                class="font-space-mono text-[10px] text-gray-400 mt-0.5 uppercase tracking-wider">{{ $count }}
                                Reviews</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Reviews Feed & Form -->
                <div class="lg:col-span-2 space-y-16">

                    <!-- Form Card (Glass Style) -->
                    <div class="relative overflow-hidden rounded-2xl border border-white/50 shadow-sm"
                        style="background: linear-gradient(135deg, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0.4) 100%); backdrop-filter: blur(20px);">

                        <div class="p-8 md:p-12 relative z-10">
                            <h3 class="font-gloock text-2xl mb-8">Share Your Perspective</h3>

                            @auth
                                <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="space-y-6"
                                    x-data="{ rating: 0, hoverRating: 0 }">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <!-- Rating -->
                                        <div class="space-y-3">
                                            <label
                                                class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-gray-500 block">Rating</label>
                                            <div class="flex items-center gap-2 cursor-pointer group">
                                                <template x-for="i in 5">
                                                    <svg @click="rating = i" @mouseenter="hoverRating = i"
                                                        @mouseleave="hoverRating = 0"
                                                        class="w-8 h-8 transition-all duration-300 transform group-hover:scale-105"
                                                        :class="(hoverRating >= i || rating >= i) ? 'fill-amber-400 drop-shadow-md' : 'fill-transparent stroke-black/20 stroke-1'"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                                    </svg>
                                                </template>
                                            </div>
                                            <input type="hidden" name="rating" :value="rating">
                                        </div>

                                        <!-- Submit Button (Aligned) -->
                                        <div class="flex items-end justify-end">
                                            <!-- Placeholder for spacing -->
                                        </div>
                                    </div>

                                    <!-- Comment -->
                                    <div class="space-y-3">
                                        <label
                                            class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-gray-500 block">Your
                                            Experience</label>
                                        <textarea name="comment" rows="3"
                                            class="w-full bg-white/50 border-b border-black/10 focus:border-black p-4 font-instrument text-lg outline-none transition-colors resize-none placeholder-gray-400"
                                            placeholder="A masterpiece of light and form..."></textarea>
                                    </div>

                                    <div class="flex justify-end pt-4">
                                        <button type="submit"
                                            class="group relative overflow-hidden bg-black text-white px-10 py-4 font-space-mono text-xs font-bold uppercase tracking-widest rounded-full hover:shadow-xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                            :disabled="rating === 0">
                                            <span class="relative z-10">Publish Review</span>
                                            <div
                                                class="absolute inset-0 bg-gray-800 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500">
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="bg-white/40 border border-white/60 p-8 text-center rounded-xl">
                                    <p class="font-instrument text-gray-500 mb-6 italic">"Join our community of verified
                                        collectors to share your thoughts."</p>
                                    <button onclick="openAuthModal()"
                                        class="inline-block px-8 py-3 bg-black text-white font-space-mono text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition rounded-full">
                                        Authenticate to Review
                                    </button>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Reviews Grid -->
                    <div class="grid gap-6">
                        @forelse($product->reviews->where('is_approved', 1) as $review)
                            <div
                                class="group bg-white p-8 rounded-none border-l-2 border-transparent hover:border-black transition-all duration-500 hover:shadow-lg hover:bg-gray-50">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-full bg-black/5 flex items-center justify-center font-gloock text-lg text-black">
                                            {{ substr($review->user->name ?? $review->reviewer_name ?? 'G', 0, 1) }}
                                        </div>
                                        <div>
                                            <h4
                                                class="font-space-mono text-xs font-bold uppercase tracking-widest text-black">
                                                {{ $review->user->name ?? $review->reviewer_name ?? 'Guest' }}
                                            </h4>
                                            <span
                                                class="text-[10px] text-gray-400 font-space-mono uppercase tracking-widest">Verified
                                                Buyer</span>
                                        </div>
                                    </div>
                                    <div class="flex text-amber-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300 fill-current' }}"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="font-instrument text-gray-600 leading-relaxed text-lg italic">
                                    "{{ $review->comment }}"</p>
                                <div class="mt-6 pt-6 border-t border-black/5 flex justify-between items-center">
                                    <span
                                        class="font-space-mono text-[10px] text-gray-400 uppercase tracking-widest">{{ $review->created_at->format('F d, Y') }}</span>
                                    <button class="text-gray-300 hover:text-black transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center border border-dashed border-gray-300 rounded-lg">
                                <p class="font-instrument text-gray-400 italic text-lg">"Be the first to adorn this page
                                    with your thoughts."</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>

    </main>

    <x-footer />

    <x-toast />
    <script>
        function changeQuantity(val) {
            const input = document.getElementById('product_quantity');
            let current = parseInt(input.value);
            let next = current + val;
            let min = parseInt(input.min) || 1;
            let max = parseInt(input.max) || 99;

            if (next >= min && next <= max) {
                input.value = next;
            }
        }

        function addToWishlist(btn, productId) {
            fetch('{{ route("wishlist.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');

                        const svg = btn.querySelector('svg');
                        if (data.action === 'added') {
                            btn.classList.remove('text-black');
                            btn.classList.add('text-red-600');
                            svg.classList.remove('fill-none');
                            svg.classList.add('fill-current');
                            btn.title = 'Remove from Wishlist';
                        } else {
                            btn.classList.remove('text-red-600');
                            btn.classList.add('text-black');
                            svg.classList.remove('fill-current');
                            svg.classList.add('fill-none');
                            btn.title = 'Add to Wishlist';
                        }
                    } else {
                        showToast(data.message || 'Something went wrong.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to update wishlist.', 'error');
                });
        }

        function addToCart(btn, productId) {
            fetch('{{ route("cart.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: document.getElementById('product_quantity')?.value || 1
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');

                        // UI Update Logic
                        // Check if it's the grid button (has svg child) or show page button (has span child with text)
                        const isGridButton = btn.querySelector('svg') !== null;

                        if (data.action === 'added') {
                            if (isGridButton) {
                                btn.classList.remove('bg-white/40', 'text-black', 'hover:bg-white');
                                btn.classList.add('bg-white', 'text-red-600', 'border-red-200', 'hover:bg-red-50');
                                btn.title = 'Remove from Cart';
                                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>';
                            } else {
                                // Show page button
                                // Remove black styles, add white/border styles
                                btn.classList.remove('bg-black', 'text-white', 'hover:bg-gray-800');
                                btn.classList.add('bg-white', 'text-black', 'border', 'border-black', 'hover:bg-gray-100');
                                const textSpan = btn.querySelector('span:first-child');
                                if (textSpan) textSpan.innerText = 'Remove from Cart';
                                const pulseSpan = btn.querySelector('span:last-child');
                                if (pulseSpan) {
                                    pulseSpan.classList.remove('bg-white');
                                    pulseSpan.classList.add('bg-black');
                                }
                            }
                        } else {
                            // Removed
                            if (isGridButton) {
                                btn.classList.remove('bg-white', 'text-red-600', 'border-red-200', 'hover:bg-red-50');
                                btn.classList.add('bg-white/40', 'text-black', 'hover:bg-white');
                                btn.title = 'Add to Cart';
                                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>';
                            } else {
                                // Show page button
                                btn.classList.remove('bg-white', 'text-black', 'border', 'border-black', 'hover:bg-gray-100');
                                btn.classList.add('bg-black', 'text-white', 'hover:bg-gray-800');
                                const textSpan = btn.querySelector('span:first-child');
                                if (textSpan) textSpan.innerText = 'Add to Cart';
                                const pulseSpan = btn.querySelector('span:last-child');
                                if (pulseSpan) {
                                    pulseSpan.classList.remove('bg-black');
                                    pulseSpan.classList.add('bg-white');
                                }
                            }
                        }

                    } else {
                        showToast(data.message || 'Something went wrong.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to update cart.', 'error');
                });
        }
        function buyNow(productId) {
            fetch('{{ route("cart.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: document.getElementById('product_quantity')?.value || 1,
                    force_add: true
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to checkout
                        window.location.href = "{{ route('checkout.index') }}";
                    } else {
                        showToast(data.message || 'Something went wrong.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to process buy now.', 'error');
                });
        }
    </script>
</body>

</html>