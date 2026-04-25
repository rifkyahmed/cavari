@if($products->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 border-l border-gray-200">
        @foreach($products as $product)
            <div class="group relative border-r border-b border-gray-200 bg-transparent hover:bg-white group-[.is-active]:bg-white transition-colors duration-500 overflow-hidden flex flex-col h-[550px]" id="card-media-{{ $product->id }}">
                
                @php
                $mainImage = $product->images[0] ?? null;
                $imageUrl = asset('images/sapphire.png'); // Default fallback

                if ($mainImage) {
                    if (Str::startsWith($mainImage, ['http', 'https'])) {
                        $imageUrl = $mainImage;                         // full URL
                    } elseif (Str::startsWith($mainImage, ['/storage/', 'storage/'])) {
                        $imageUrl = asset($mainImage);                  // already has /storage/ prefix
                    } elseif (Str::startsWith($mainImage, 'images/')) {
                        $imageUrl = asset($mainImage);                  // public/images/...
                    } else {
                        $imageUrl = asset('storage/' . $mainImage);     // bare relative path
                    }
                }

                $videoUrl = null;
                if ($product->video) {
                    $videoUrl = $product->video;
                    
                    // Resolve asset path if local
                    if (!Str::startsWith($videoUrl, ['http', 'https'])) {
                        if (Str::startsWith($videoUrl, ['/storage/', 'storage/'])) {
                            $videoUrl = asset($videoUrl);
                        } else {
                            $videoUrl = asset('storage/' . $videoUrl);
                        }
                    }

                    // Cloudinary Optimization (Compression)
                    if (Str::contains($videoUrl, 'cloudinary.com')) {
                        // Use q_auto and vc_auto for optimal video compression and compatibility
                        if (!Str::contains($videoUrl, '/q_auto')) {
                            // Ensure we're targeting the delivery segment
                            $videoUrl = str_replace('/upload/', '/upload/q_auto,vc_auto/', $videoUrl);
                        }
                        
                        // Ensure browser-friendly extension (case-insensitive check)
                        $lowerUrl = Str::lower($videoUrl);
                        if (!Str::endsWith($lowerUrl, ['.mp4', '.webm', '.ogg', '.mov'])) {
                            $videoUrl .= '.mp4';
                        }
                    }
                }
                @endphp

                {{-- Hover Video (Background for the whole card) --}}
                @if($videoUrl)
                <video
                    id="card-vid-{{ $product->id }}"
                    data-src="{{ $videoUrl }}"
                    muted
                    loop
                    playsinline
                    crossorigin="anonymous"
                    preload="auto"
                    class="absolute inset-0 w-full h-full object-cover z-20 opacity-0"
                    style="transition: opacity 0.4s ease; will-change: opacity;"
                ></video>
                @endif

                <!-- Full Link -->
                <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0 z-30"></a>

                <!-- Header -->
                <div class="p-8 flex justify-between items-start relative z-30 transition-opacity duration-300" id="card-header-{{ $product->id }}">
                    <div class="flex flex-col min-w-0 flex-1 pr-4">
                        <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/40 group-hover:text-black group-[.is-active]:text-black transition-colors block truncate">{{ $product->category->name ?? 'Collection' }}</span>
                        <h3 class="font-gloock text-2xl md:text-3xl text-black mt-2 transform group-hover:-translate-y-1 group-[.is-active]:-translate-y-1 transition-transform duration-500 grid-product-title truncate">{{ $product->name }}</h3>
                    </div>
                    <div class="flex flex-col items-end text-right shrink-0 pl-2">
                        <span class="font-space-mono text-sm text-black font-medium">{{ \App\Helpers\CurrencyHelper::format($product->price) }}</span>
                        @if($product->original_price)
                            <span class="font-space-mono text-[10px] text-gray-400 line-through mt-1">{{ \App\Helpers\CurrencyHelper::format($product->original_price) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Fixed-Square Image Frame --}}
                <div class="flex-grow flex items-center justify-center relative z-10 py-6 overflow-hidden">

                    {{-- The square frame: fades out only if there's a video to show behind it --}}
                    <div class="relative flex-shrink-0 transition-opacity duration-500 @if($videoUrl) group-hover:opacity-0 group-[.is-active]:opacity-0 @endif" style="width:320px;height:320px;background:#fff;">

                        {{-- Product image: using object-cover and a default scale to make it look bigger --}}
                        <img src="{{ $imageUrl }}"
                            id="card-img-{{ $product->id }}"
                            alt="{{ $product->name }} luxury {{ strtolower($product->category->name ?? 'jewelry') }} by Cavari"
                            loading="lazy"
                            class="absolute inset-0 w-full h-full object-cover transform scale-125 group-hover:scale-[135%] group-[.is-active]:scale-[135%] transition-transform duration-800 ease-out"
                            style="transition: opacity 0.3s ease, transform 0.8s ease-out; will-change: transform, opacity;">

                        {{-- Video badge --}}
                        @if($videoUrl)
                        <span class="absolute top-2 right-2 z-50 text-white rounded-full px-2 py-0.5 text-[9px] font-space-mono uppercase tracking-widest flex items-center gap-1 pointer-events-none opacity-0 group-hover:opacity-100 group-[.is-active]:opacity-100 transition-opacity duration-300">
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons (Hover) -->
                <div class="absolute bottom-24 right-5 z-40 flex flex-col gap-3 pointer-events-none group-hover:pointer-events-auto group-[.is-active]:pointer-events-auto transition-opacity duration-300">
                    <!-- Quick View -->
                    <button onclick="showQuickView({{ $product->id }})" class="w-10 h-10 bg-white/40 backdrop-blur-md border border-white/30 rounded-full shadow-md flex items-center justify-center text-black hover:bg-white hover:scale-110 hover:shadow-xl transition-all duration-300 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 group-[.is-active]:translate-y-0 group-[.is-active]:opacity-100 delay-100 pointer-events-auto" title="Quick View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </button>
                    
                        <!-- Wishlist -->
                        @auth
                            @php
                                $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                            @endphp
                            <button type="button" onclick="addToWishlist(this, {{ $product->id }})" class="w-10 h-10 bg-white/40 backdrop-blur-md border border-white/30 rounded-full shadow-md flex items-center justify-center hover:bg-white hover:scale-110 hover:shadow-xl transition-all duration-300 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 group-[.is-active]:translate-y-0 group-[.is-active]:opacity-100 delay-200 pointer-events-auto {{ $inWishlist ? 'text-red-600' : 'text-black' }}" title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                                <svg class="w-4 h-4 {{ $inWishlist ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                        @else
                            <button type="button" onclick="openAuthModal()" class="w-10 h-10 bg-white/40 backdrop-blur-md border border-white/30 rounded-full shadow-md flex items-center justify-center text-black hover:bg-white hover:scale-110 hover:shadow-xl transition-all duration-300 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 group-[.is-active]:translate-y-0 group-[.is-active]:opacity-100 delay-200 pointer-events-auto" title="Login to Add to Wishlist">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                        @endauth

                    <!-- Add to Cart -->
                    @php
                        $inCart = isset(session('cart')[$product->id]);
                    @endphp
                    <button type="button" onclick="addToCart(this, {{ $product->id }})" class="w-10 h-10 backdrop-blur-md border border-white/30 rounded-full shadow-md flex items-center justify-center hover:scale-110 hover:shadow-xl transition-all duration-300 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 group-[.is-active]:translate-y-0 group-[.is-active]:opacity-100 delay-300 pointer-events-auto {{ $inCart ? 'bg-white text-red-600 border-red-200 hover:bg-red-50' : 'bg-white/40 text-black hover:bg-white' }}" title="{{ $inCart ? 'Remove from Cart' : 'Add to Cart' }}">
                        @if($inCart)
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        @else
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        @endif
                    </button>
                </div>

                <!-- Footer / Hover Action -->
                <div class="p-8 relative z-30 transition-opacity duration-300" id="card-footer-{{ $product->id }}">
                    <div class="w-full flex justify-between items-center opacity-0 group-hover:opacity-100 transition-opacity duration-500 transform translate-y-4 group-hover:translate-y-0">
                        <span class="font-instrument text-xs uppercase tracking-widest text-gray-500">View Details</span>
                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="w-full flex justify-center py-16">
            {{ $products->onEachSide(1)->links('pagination.custom') }} 
    </div>
@else
    <div class="flex flex-col items-center justify-center min-h-[50vh] text-center px-6">
        <span class="font-gloock text-6xl text-gray-200 mb-6">∅</span>
        <h3 class="font-gloock text-3xl text-black mb-2">No Artifacts Found</h3>
        <p class="font-instrument text-gray-500 mb-8 max-w-md">
            We couldn't find any items matching your criteria. Our collection is rare, but check back soon.
        </p>
        <a href="{{ route('products.index') }}" class="px-8 py-3 bg-black text-white rounded-full font-space-mono text-xs uppercase tracking-widest hover:bg-gray-800 transition">
            Clear Filters
        </a>
    </div>
@endif

<script>
window.initProductGrid = function() {
    const cards = document.querySelectorAll('[id^="card-media-"]');
    
    // Core logic for activating/deactivating a card's visual state
    function setCardState(card, isActive) {
        const id = card.id.replace('card-media-', '');
        const video = document.getElementById('card-vid-' + id);
        const img = document.getElementById('card-img-' + id);

        if (isActive) {
            card.classList.add('is-active');
            if (video) {
                // LAZY LOAD: Set src from data-src if not already set or different
                const dataSrc = video.getAttribute('data-src');
                if (dataSrc && video.src !== dataSrc) {
                    video.src = dataSrc;
                    video.load();
                }

                if (img) img.style.opacity = '0';
                video.style.opacity = '1';
                
                // Force play from beginning
                video.currentTime = 0;
                video.play().catch(error => {
                    console.warn("Video play failed:", error);
                });
            }
        } else {
            card.classList.remove('is-active');
            if (video) {
                video.pause();
                video.style.opacity = '0';
                if (img) img.style.opacity = '1';
            }
        }
    }

    // Set up Hover listeners for Desktop (Using a function for safe attachment)
    cards.forEach(card => {
        // Cloning or replacing prevents double listener binding (though innerHTML replaces nodes)
        card.addEventListener('mouseenter', () => {
            // Only use hover logic if we aren't in small-screen "auto-activate" mode
            if (window.innerWidth >= 1024) setCardState(card, true);
        });
        card.addEventListener('mouseleave', () => {
            if (window.innerWidth >= 1024) setCardState(card, false);
        });
    });

    // Cleanup existing observer if it exists (for AJAX reloads)
    if (window.productGridObserver) {
        window.productGridObserver.disconnect();
    }

    // Intersection Observer for Mobile Auto-Hover on Scroll
    const observerOptions = {
        root: null,
        rootMargin: '-20% 0px -20% 0px', // Trigger near the center of the screen
        threshold: 0.6 // Card must be 60% visible
    };

    window.productGridObserver = new IntersectionObserver((entries) => {
        if (window.innerWidth < 1024) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setCardState(entry.target, true);
                } else {
                    setCardState(entry.target, false);
                }
            });
        }
    }, observerOptions);

    cards.forEach(card => window.productGridObserver.observe(card));

    // Handle Resize (Cleanup if switching from mobile to desktop)
    // Remove old listener if it exists to prevent stacking
    if (window.productGridResizeHandler) {
        window.removeEventListener('resize', window.productGridResizeHandler);
    }
    
    window.productGridResizeHandler = () => {
        if (window.innerWidth >= 1024) {
            cards.forEach(card => card.classList.remove('is-active'));
        }
    };
    
    window.addEventListener('resize', window.productGridResizeHandler);
};

// Initialize on first load
window.initProductGrid();
</script>
