<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>Bag | {{ config('app.name', 'Cavari') }}</title>

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
        
        <!-- Header -->
        <div class="mb-12 border-b border-black/5 pb-8">
            <h1 class="font-gloock text-5xl md:text-7xl mb-2">Shopping Bag</h1>
            <p class="font-space-mono text-xs uppercase tracking-widest text-gray-400">
                Review your selections
            </p>
        </div>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="flex flex-col lg:flex-row gap-16 items-start">
                
                <div class="w-full lg:w-2/3 space-y-8" id="cart-items-container">
                    @php $total = 0 @endphp
                    @foreach(session('cart') as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        
                        <!-- Glass Item Card -->
                        <div id="cart-item-{{ $id }}" class="group relative flex flex-col sm:flex-row bg-white/40 backdrop-blur-md border border-white/60 rounded-3xl overflow-hidden hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 transition-all duration-500">
                            
                            <!-- Image -->
                            <div class="w-full sm:w-48 aspect-square relative bg-gray-100 flex-shrink-0">
                                @if($details['image'])
                                    @php
                                        $mainImage = $details['image'];
                                        $imageUrl = asset('images/sapphire.png'); // Default fallback

                                        if ($mainImage) {
                                            if (\Illuminate\Support\Str::startsWith($mainImage, ['http', 'https'])) {
                                                $imageUrl = $mainImage;                         // full URL
                                            } elseif (\Illuminate\Support\Str::startsWith($mainImage, ['/storage/', 'storage/'])) {
                                                $imageUrl = asset($mainImage);                  // already has /storage/ prefix
                                            } elseif (\Illuminate\Support\Str::startsWith($mainImage, 'images/')) {
                                                $imageUrl = asset($mainImage);                  // public/images/...
                                            } else {
                                                $imageUrl = asset('storage/' . $mainImage);     // bare relative path
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 font-space-mono text-xs uppercase">No Image</div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-grow p-6 flex flex-col justify-between">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-gloock text-2xl mb-1">
                                            <a href="{{ route('products.show', \App\Models\Product::find($id)->slug ?? '#') }}">{{ $details['name'] }}</a>
                                        </h3>
                                        <p class="font-instrument text-sm text-gray-500">Unit Price: {{ \App\Helpers\CurrencyHelper::format($details['price']) }}</p>
                                    </div>
                                    
                                    <button type="button" onclick="removeCartItem(this, {{ $id }})" class="text-gray-300 hover:text-red-500 transition-colors p-2">
                                        <span class="sr-only">Remove</span>
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>

                                <div class="flex justify-between items-end">
                                    <div class="flex items-center gap-3">
                                         <span class="font-space-mono text-[9px] uppercase tracking-widest text-gray-400">Qty</span>
                                         <div class="flex items-center bg-white/50 rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                                            <button type="button" id="btn-minus-{{ $id }}" onclick="updateCart({{ $id }}, -1)" class="w-8 h-8 flex items-center justify-center hover:bg-black hover:text-white transition-colors text-lg font-medium leading-none {{ $details['quantity'] <= 1 ? 'opacity-30 cursor-not-allowed' : '' }}" {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>-</button>
                                            <span id="quantity-{{ $id }}" class="px-2 font-space-mono text-sm font-bold min-w-[32px] text-center">{{ $details['quantity'] }}</span>
                                            <button type="button" onclick="updateCart({{ $id }}, 1)" class="w-8 h-8 flex items-center justify-center hover:bg-black hover:text-white transition-colors text-lg font-medium leading-none">+</button>
                                         </div>
                                    </div>
                                    <span id="item-total-{{ $id }}" class="font-gloock text-xl text-black">{{ \App\Helpers\CurrencyHelper::format($details['price'] * $details['quantity']) }}</span>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Summary Panel (Sticky) -->
                <div class="w-full lg:w-1/3 lg:sticky lg:top-32">
                    <div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-8 shadow-[0_20px_40px_-12px_rgba(0,0,0,0.1)]">
                        <h2 class="font-gloock text-3xl mb-8">Summary</h2>
                        
                        <div class="space-y-4 mb-8 font-instrument text-sm text-gray-600">
                             <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span id="cart-subtotal" class="font-medium text-black">{{ \App\Helpers\CurrencyHelper::format($totals['subtotal']) }}</span>
                            </div>
                            @if(session()->has('coupon'))
                            <div class="flex justify-between text-green-700">
                                <span class="flex items-center gap-2">
                                    Discount ({{ session('coupon')['code'] }})
                                    <form action="{{ route('cart.coupon.remove') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 underline font-space-mono lowercase">remove</button>
                                    </form>
                                </span>
                                <span id="cart-discount" class="font-medium">-{{ \App\Helpers\CurrencyHelper::format($totals['discount']) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span class="text-green-600 font-medium">Complimentary</span>
                            </div>
                            @if(session()->has('gift_card'))
                            <div class="flex justify-between text-indigo-700">
                                <span class="flex items-center gap-2">
                                    Gift Card ({{ session('gift_card')['code'] }})
                                    <form action="{{ route('cart.gift-card.remove') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 underline font-space-mono lowercase">remove</button>
                                    </form>
                                </span>
                                <span id="cart-gift-card" class="font-medium">-{{ \App\Helpers\CurrencyHelper::format($totals['gift_card_amount']) }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="border-t border-black/10 pt-6 mb-6 flex justify-between items-baseline">
                             <span class="font-space-mono text-xs font-bold uppercase tracking-widest">Total</span>
                             <span id="cart-total" class="font-gloock text-4xl">{{ \App\Helpers\CurrencyHelper::format($totals['total']) }}</span>
                        </div>

                        <!-- Coupon Input -->
                        @if(!session()->has('coupon'))
                        <div class="mb-4">
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex items-center border-b border-gray-300 py-2">
                                @csrf
                                <input type="text" name="code" placeholder="Promo Code" required
                                       class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none font-instrument text-sm placeholder-gray-400">
                                <button type="submit" class="flex-shrink-0 text-[10px] font-space-mono font-bold uppercase tracking-widest text-black hover:text-gray-500 transition-colors">
                                    Apply
                                </button>
                            </form>
                        </div>
                        @endif

                        <!-- Gift Card Input -->
                        @if(!session()->has('gift_card'))
                        <div class="mb-8">
                            <form action="{{ route('cart.gift-card.apply') }}" method="POST" class="flex items-center border-b border-gray-300 py-2">
                                @csrf
                                <input type="text" name="code" placeholder="Gift Card Code" required
                                       class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none font-instrument text-sm placeholder-gray-400">
                                <button type="submit" class="flex-shrink-0 text-[10px] font-space-mono font-bold uppercase tracking-widest text-black hover:text-gray-500 transition-colors">
                                    Redeem
                                </button>
                            </form>
                        </div>
                        @endif

                        @auth
                            <a href="{{ route('checkout.index') }}" class="block w-full bg-black text-white text-center py-4 rounded-xl font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                Secure Checkout
                            </a>
                        @else
                            <button type="button" onclick="openAuthModal()" class="block w-full bg-black text-white text-center py-4 rounded-xl font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                Secure Checkout
                            </button>
                        @endauth
                        
                        <div class="mt-6 text-center">
                            <span class="text-[10px] text-gray-400 font-space-mono uppercase tracking-widest flex items-center justify-center gap-2">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Encrypted Transaction
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        @else
            <!-- Empty State -->
            <div class="py-32 text-center">
                <div class="w-20 h-20 bg-white/50 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-6 border border-white shadow-sm">
                    <span class="font-gloock text-3xl text-gray-300">0</span>
                </div>
                <h2 class="font-gloock text-3xl mb-4">Your Bag is Empty</h2>
                <a href="{{ route('products.index') }}" class="inline-block border-b border-black pb-0.5 font-space-mono text-[10px] font-bold uppercase tracking-widest hover:text-gray-600 transition">
                    Explore The Collection
                </a>
            </div>
        @endif

    </main>

    <x-footer />

    <x-toast />

    <script>
        function updateCart(id, change) {
            const quantitySpan = document.getElementById(`quantity-${id}`);
            let currentQty = parseInt(quantitySpan.innerText);
            let newQty = currentQty + change;
            
            if (newQty < 1) return;

            // Optimistic UI Update (optional, or wait for server)
            // waiting for server ensures validity
            
            fetch('{{ route("cart.update") }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ id: id, quantity: newQty })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Update Quantity Display
                    quantitySpan.innerText = newQty;
                    
                    // Update Item Total
                    const itemTotalEl = document.getElementById(`item-total-${id}`);
                    if(itemTotalEl) itemTotalEl.innerText = '$' + parseFloat(data.itemTotal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                    // Update Cart Totals
                    updateCartTotals(data.subtotal, data.total, data.discount);
                    
                    // Update Button States
                    const minusBtn = document.getElementById(`btn-minus-${id}`);
                    if(newQty <= 1) {
                         minusBtn.classList.add('opacity-30', 'cursor-not-allowed');
                         minusBtn.disabled = true;
                    } else {
                         minusBtn.classList.remove('opacity-30', 'cursor-not-allowed');
                         minusBtn.disabled = false;
                    }

                } else {
                    showToast(data.message || 'Failed to update cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error updating cart', 'error');
            });
        }

        function removeCartItem(btn, id) {
            // Smooth removal animation first
            const row = document.getElementById(`cart-item-${id}`);
            
            fetch('{{ route("cart.remove") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showToast(data.message, 'success');
                    
                    if(row) {
                        row.style.transition = 'all 0.5s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => {
                            row.remove();
                            if(data.cartCount === 0) location.reload();
                        }, 500);
                    }

                    updateCartTotals(data.subtotal, data.total, data.discount);
                }
            });
        }

        function updateCartTotals(subtotal, total, discount = 0) {
            const subtotalEl = document.getElementById('cart-subtotal');
            const totalEl = document.getElementById('cart-total');
            const discountEl = document.getElementById('cart-discount');
            
            if(subtotalEl) subtotalEl.innerText = '$' + parseFloat(subtotal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            if(totalEl) totalEl.innerText = '$' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            if(discountEl) discountEl.innerText = '-$' + parseFloat(discount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    </script>
</body>
</html>
