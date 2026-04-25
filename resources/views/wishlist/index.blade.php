<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>Wishlist | {{ config('app.name', 'Cavari') }}</title>

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

    <main class="flex-grow w-full max-w-7xl mx-auto pt-36 pb-24 px-6 md:px-12">
        
        <!-- Minimal Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 border-b border-black/5 pb-8">
            <div>
                <h1 class="font-gloock text-5xl md:text-7xl leading-none mb-2">
                    The Vault
                </h1>
                <p class="font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">
                    Saved Treasures
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <span id="wishlist-count" class="font-space-mono text-sm border border-black/10 px-3 py-1 rounded-full bg-white/50 backdrop-blur">
                    {{ $wishlists->total() }} Items
                </span>
            </div>
        </div>

        @if($wishlists->count() > 0)
            <div id="wishlist-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($wishlists as $item)
                    <!-- Horizontal Glass Card -->
                    <div class="group relative flex bg-white/40 backdrop-blur-md border border-white/60 rounded-2xl overflow-hidden hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 transition-all duration-500">
                        
                        <!-- Image Section (Left - Square Box style) -->
                        <a href="{{ route('products.show', $item->product->slug) }}" class="w-[35%] min-w-[35%] relative bg-white overflow-hidden border-r border-black/5">
                             @php
                                $mainImage = $item->product->images[0] ?? null;
                                $imageUrl = asset('images/sapphire.png'); // Default fallback

                                if ($mainImage) {
                                    if (\Illuminate\Support\Str::startsWith($mainImage, ['http', 'https'])) {
                                        $imageUrl = $mainImage;
                                    } elseif (\Illuminate\Support\Str::startsWith($mainImage, 'images/')) {
                                        $imageUrl = asset($mainImage);
                                    } else {
                                        $imageUrl = asset('storage/' . $mainImage);
                                    }
                                }
                             @endphp
                             <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover transition-transform duration-700 transform scale-125 group-hover:scale-110">
                        </a>

                        <!-- Content Section (Right) -->
                        <div class="flex-grow p-6 flex flex-col justify-between relative">
                            
                            <!-- Top: Info + Remove -->
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-gloock text-2xl mb-1 leading-tight max-w-[80%]">
                                        <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                    </h3>
                                    <p class="font-space-mono text-[10px] text-gray-500 uppercase tracking-widest">{{ $item->product->category->name ?? 'Object' }}</p>
                                </div>
                                
                                <button type="button" onclick="removeFromWishlist(this, {{ $item->id }})" class="text-gray-300 hover:text-red-500 transition-colors p-1" title="Remove">
                                    <span class="sr-only">Remove</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            
                            <!-- Bottom: Price + Action -->
                            <div class="flex items-end justify-between mt-6">
                                <span class="font-instrument text-lg font-medium">{{ \App\Helpers\CurrencyHelper::format($item->product->price) }}</span>
                                
                                @php
                                    $inCart = isset(session('cart')[$item->product->id]);
                                @endphp
                                <button type="button" onclick="addToCart(this, {{ $item->product->id }})" class="bg-white border border-gray-200 px-5 py-2 rounded-lg font-space-mono text-[10px] font-bold uppercase tracking-widest transition-all duration-300 shadow-sm {{ $inCart ? 'text-red-500 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'text-black hover:bg-black hover:text-white hover:border-black' }}">
                                    {{ $inCart ? 'Remove from Cart' : 'Add to Cart' }}
                                </button>
                            </div>
                            
                            <!-- Decorative Shimmer -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent pointer-events-none -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 flex justify-center">
                {{ $wishlists->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="py-24 flex flex-col items-center justify-center text-center">
                 <div class="w-20 h-20 bg-white/50 backdrop-blur rounded-full flex items-center justify-center mb-6 border border-white shadow-sm">
                     <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                 </div>
                <h2 class="font-gloock text-3xl mb-2 text-gray-900">The Vault is Locked</h2>
                <p class="font-instrument text-gray-500 mb-8">Save items here to track their availability and updates.</p>
                <a href="{{ route('products.index') }}" class="font-space-mono text-[10px] font-bold uppercase tracking-widest border-b border-black pb-0.5 hover:text-gray-600 transition">
                    Open Catalog
                </a>
            </div>
        @endif

    </main>

    <x-footer />

    <x-toast />

    <script>
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
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    
                    // UI Update Logic
                    if (data.action === 'added') {
                        btn.innerText = 'Remove from Cart';
                        btn.classList.remove('text-black', 'hover:bg-black', 'hover:text-white', 'hover:border-black');
                        btn.classList.add('text-red-500', 'hover:bg-red-50', 'hover:text-red-600', 'hover:border-red-200');
                    } else {
                        // Removed
                        btn.innerText = 'Add to Cart';
                        btn.classList.remove('text-red-500', 'hover:bg-red-50', 'hover:text-red-600', 'hover:border-red-200');
                        btn.classList.add('text-black', 'hover:bg-black', 'hover:text-white', 'hover:border-black');
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

        function removeFromWishlist(btn, wishlistId) {
            fetch(`/wishlist/${wishlistId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    // Find container and remove
                    // The button is inside .group .flex .flex-wrap...
                    // The main card is .group defined in foreach.
                    const card = btn.closest('.group');
                    if(card) {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.remove();
                            
                            // Parse and update total count
                            const countSpan = document.getElementById('wishlist-count');
                            if(countSpan) {
                                const currentText = countSpan.innerText;
                                const currentCount = parseInt(currentText) || 0;
                                const newCount = Math.max(0, currentCount - 1);
                                countSpan.innerText = `${newCount} Items`;
                                
                                if(newCount === 0) {
                                    location.reload();
                                    return;
                                }
                            }

                            const grid = document.getElementById('wishlist-grid');
                            if (grid && grid.children.length === 0) {
                                location.reload();
                            } 
                        }, 500);
                    }
                } else {
                    showToast('Something went wrong.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to remove item.', 'error');
            });
        }
    </script>
</body>
</html>
