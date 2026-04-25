<div class="relative bg-white w-full max-w-4xl mx-auto rounded-xl shadow-2xl overflow-hidden animate-fade-in-up">
    <!-- Close Button -->
    <button onclick="closeQuickView()" class="absolute top-4 right-4 z-50 p-2 bg-white/80 backdrop-blur rounded-full text-black hover:bg-black hover:text-white transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>

    <div class="grid grid-cols-1 md:grid-cols-2">
        <!-- Image Section -->
        <div class="relative bg-gray-50 h-[400px] md:h-auto flex items-center justify-center p-12">
            @php
                $mainImage = $product->images[0] ?? null;
                $imageUrl = asset('images/sapphire.png'); // Default fallback

                if ($mainImage) {
                    if (Str::startsWith($mainImage, ['http', 'https'])) {
                        $imageUrl = $mainImage;
                    } elseif (Str::startsWith($mainImage, 'images/')) {
                        $imageUrl = asset($mainImage);
                    } else {
                        $imageUrl = asset('storage/' . $mainImage);
                    }
                }
            @endphp
            
            {{-- Standardized White Box --}}
            <div class="relative flex-shrink-0 shadow-lg" style="width:320px;height:320px;background:#fff;">
                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" 
                     class="absolute inset-0 w-full h-full object-cover transform scale-125 transition-transform duration-700 hover:scale-[130%]">
            </div>
            
            <div class="absolute bottom-4 left-4">
                 <span class="bg-white/90 backdrop-blur px-3 py-1 font-space-mono text-[10px] uppercase tracking-widest text-black rounded-sm border border-black/5">{{ $product->category->name ?? 'Collection' }}</span>
            </div>
        </div>

        <!-- Details Section -->
        <div class="p-8 md:p-12 flex flex-col h-full overflow-y-auto">
            <h2 class="font-gloock text-3xl md:text-4xl text-black mb-2">{{ $product->name }}</h2>
            
            <div class="flex items-center gap-4 mb-6">
                 <span class="font-instrument text-2xl font-medium">{{ \App\Helpers\CurrencyHelper::format($product->price) }}</span>
                 @if($product->original_price)
                     <span class="font-instrument text-sm text-gray-400 line-through mt-1">{{ \App\Helpers\CurrencyHelper::format($product->original_price) }}</span>
                 @endif
                 @if($product->stock > 0)
                    <span class="text-green-600 font-space-mono text-[10px] uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-600 rounded-full"></span> In Stock
                    </span>
                 @else
                     <span class="text-red-500 font-space-mono text-[10px] uppercase tracking-widest">Out of Stock</span>
                 @endif
            </div>

            <p class="font-instrument text-gray-600 leading-relaxed mb-8 flex-grow">
                {{ Str::limit($product->description, 150) }}
            </p>

            <div class="space-y-4">
                 <div class="grid grid-cols-2 gap-4 text-sm font-instrument text-gray-500 border-t border-b border-gray-100 py-4 mb-6">
                    <div>
                        <span class="block font-space-mono text-[10px] uppercase text-gray-400 mb-1">Gemstone</span>
                        <span class="text-black">{{ $product->gemstone_type ?? 'N/A' }}</span>
                    </div>
                     <div>
                        <span class="block font-space-mono text-[10px] uppercase text-gray-400 mb-1">Weight</span>
                        <span class="text-black">{{ $product->weight ?? 'N/A' }} ct</span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <!-- Add to Cart -->
                     @php
                        $inCart = isset(session('cart')[$product->id]);
                    @endphp
                    <button type="button" onclick="addToCart(this, {{ $product->id }})" class="flex-1 py-4 px-6 font-space-mono text-xs font-bold uppercase tracking-widest transition shadow-lg rounded-sm flex items-center justify-center gap-2 group {{ $inCart ? 'bg-white text-black border border-black hover:bg-gray-100' : 'bg-black text-white hover:bg-gray-800' }}">
                        <span>{{ $inCart ? 'Remove from Cart' : 'Add to Cart' }}</span>
                        @if($inCart)
                            <span class="w-1.5 h-1.5 bg-black rounded-full group-hover:animate-pulse"></span>
                        @else
                            <span class="w-1.5 h-1.5 bg-white rounded-full group-hover:animate-pulse"></span>
                        @endif
                    </button>

                    <!-- Wishlist -->
                     @auth
                        @php
                            $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                        @endphp
                        <button type="button" onclick="addToWishlist(this, {{ $product->id }})" class="w-14 flex items-center justify-center border border-black/10 hover:border-black transition rounded-sm {{ $inWishlist ? 'text-red-600 border-black' : 'text-gray-400 hover:text-red-600' }}">
                             <svg class="w-5 h-5 {{ $inWishlist ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    @else
                        <button type="button" onclick="openAuthModal()" class="w-14 flex items-center justify-center border border-black/10 hover:border-black transition rounded-sm text-gray-400 hover:text-red-600">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    @endauth
                </div>
                
                 <a href="{{ route('products.show', $product->slug) }}" class="block text-center mt-4 font-space-mono text-[10px] uppercase tracking-widest text-gray-400 hover:text-black hover:underline underline-offset-4 transition-colors">
                    View Full Details
                </a>
            </div>
        </div>
    </div>
</div>
