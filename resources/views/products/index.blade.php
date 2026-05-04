<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>Shop Luxury Gems & Jewelry | Cavari</title>
    <meta name="description" content="Explore the Cavari collection. Shop for the finest loose gemstones, 18k gold diamond rings, and bespoke luxury jewelry handcrafted for collectors.">
    <meta name="keywords" content="Cavari Shop, Cavari Gems, Buy Gemstones Online, Luxury Jewelry Shop, Diamond Rings Cavari, Sri Lanka Sapphires">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Shop | {{ config('app.name', 'Cavari') }}">
    <meta property="og:description" content="Shop the finest collection of authentic gemstones and bespoke luxury jewelry at Cavari. Discover perfectly cut rubies, sapphires, emeralds, and diamond rings.">
    <meta property="og:image" content="{{ asset('images/og-shop.png') }}">
    <meta property="og:site_name" content="Cavari">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="Shop | {{ config('app.name', 'Cavari') }}">
    <meta property="twitter:description" content="Shop the finest collection of authentic gemstones and bespoke luxury jewelry at Cavari. Discover perfectly cut rubies, sapphires, emeralds, and diamond rings.">
    <meta property="twitter:image" content="{{ asset('images/og-shop.png') }}">

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
    <link href="https://fonts.googleapis.com/css2?family=Gloock&family=Instrument+Sans:wght@400;500;600&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-gloock { font-family: 'Gloock', serif; }
        .font-instrument { font-family: 'Instrument Sans', sans-serif; }
        .font-space-mono { font-family: 'Space Mono', monospace; }
        
        /* Shared Background Gradient */
        .bg-hero-gradient {
            background: linear-gradient(135deg, #fff 0%, #fff0f5 50%, #fff 100%);
        }

        /* Custom Scrollbar for filters */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        @media (max-width: 1050px) {
            .grid-product-title {
                font-size: 1.25rem !important; /* Equivalent to text-xl */
            }
        }

        .mask-linear {
            mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
        }
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient min-h-screen flex flex-col">

    <x-navbar />

    <!-- Shop Hero -->
    <header class="relative w-full pt-32 pb-16 md:pt-40 md:pb-24 px-6 border-b border-black/10">
        <div class="max-w-7xl mx-auto text-center">
            <span class="font-space-mono text-xs font-bold uppercase tracking-[0.3em] text-gray-400 mb-4 block">
                The Collection
            </span>
            <h1 class="font-gloock text-6xl md:text-9xl text-black mb-6 leading-none">
                Treasury
            </h1>
            <p class="font-instrument text-sm md:text-lg text-gray-600 max-w-xl mx-auto leading-relaxed">
                A curated selection of earth's rarest artifacts. <br class="hidden md:block">
                Ethically sourced gemstones and bespoke creations.
            </p>
        </div>
        
    </header>

    <!-- Filters & Sort -->
    <!-- Filters & Sort -->
    <!-- Sticky at top-20 to sit below the fixed glass navbar -->
    <section class="relative z-40 bg-white/80 backdrop-blur-md border-b border-black/10 transition-all duration-300" id="filter-bar">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            
            <!-- Category Filters + Filter Toggle -->
            <div class="flex items-center gap-4 md:gap-8 h-full">
                
                <!-- Toggle Advanced Filters -->
                <button type="button" id="filter-toggle" class="group flex items-center gap-2 font-space-mono text-xs font-bold uppercase tracking-widest text-black hover:text-gray-600 transition">
                    <span class="mr-1">Filters</span>
                    <svg id="filter-icon" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>


            </div>

            <!-- Sort / Search (Hidden on small mobile if needed, or compact) -->
            <div class="flex items-center gap-6">
                <!-- Search -->
                <form action="{{ route('products.index') }}" method="GET" class="relative group" id="search-form">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" name="search" id="search-input" placeholder="SEARCH..." value="{{ request('search') }}"
                           class="bg-transparent border-b border-gray-200 focus:border-black outline-none font-space-mono text-xs uppercase tracking-widest w-24 md:w-40 py-1 transition-all placeholder:text-gray-300 text-black">
                    <button type="submit" class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-300 group-hover:text-black transition">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Advanced Filter Drawer -->
        <div id="filter-drawer" class="max-h-0 overflow-hidden transition-all duration-500 bg-gray-50 border-b border-black/5">
             <div class="max-w-7xl mx-auto px-6 py-8">
                 <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                     
                     <!-- Preserve Search -->
                     @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                     
                     <!-- Detect Context -->
                     @php
                        $currentType = request('type');
                        
                        // Define parent category groups for inference
                        $metalParentIds = $categories->whereNull('parent_id')
                            ->filter(fn($c) => str_contains($c->slug, 'gold') || str_contains($c->slug, 'platinum') || str_contains($c->slug, 'silver'))
                            ->pluck('id')->toArray();
                        
                        $gemParentIds = $categories->whereNull('parent_id')
                            ->filter(fn($c) => !str_contains($c->slug, 'gold') && !str_contains($c->slug, 'platinum') && !str_contains($c->slug, 'silver'))
                            ->pluck('id')->toArray();

                        // If type not explicitly provided, infer from route or category
                        if (!$currentType) {
                            if (request()->routeIs('shop.gems')) {
                                $currentType = 'gem';
                            } elseif (request()->routeIs('shop.jewelry')) {
                                $currentType = 'jewelry';
                            } elseif ($cat = request('category')) {
                                // Check if category belongs to gems or jewelry
                                $isGem = $categories->where('name', $cat)->whereIn('parent_id', $gemParentIds)->isNotEmpty() 
                                      || $categories->where('slug', $cat)->whereIn('parent_id', $gemParentIds)->isNotEmpty()
                                      || $cat == 'loose-gems';
                                
                                $isJewelry = $categories->where('name', $cat)->whereIn('parent_id', $metalParentIds)->isNotEmpty()
                                          || $categories->where('slug', $cat)->whereIn('parent_id', $metalParentIds)->isNotEmpty();
                                
                                if ($isGem) $currentType = 'gem';
                                elseif ($isJewelry) $currentType = 'jewelry';
                            }
                        }
                     @endphp

                     <div class="space-y-8">
                         
                         <!-- Top Row: Type & General -->
                         <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 border-b border-gray-200 pb-8">
                             <!-- Type Selection -->
                             <div class="col-span-2 md:col-span-1">
                                 <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black mb-2 block">Collection Type</label>
                                 <select name="type" id="type-select" data-current="{{ $currentType }}" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black appearance-none cursor-pointer transition-colors" style="">
                                     <option value="">All Collections</option>
                                     <option value="gem" {{ $currentType == 'gem' ? 'selected' : '' }}>Loose Gemstones</option>
                                     <option value="jewelry" {{ $currentType == 'jewelry' ? 'selected' : '' }}>Jewelry</option>
                                 </select>
                             </div>

                             <!-- Price Range -->
                             <div class="col-span-2 md:col-span-1">
                                 <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black mb-2 block">Price Range</label>
                                 <div class="flex items-center gap-2">
                                     <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black">
                                     <span class="text-black">-</span>
                                     <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black">
                                 </div>
                             </div>



                             <!-- Status Flags -->
                             <div class="col-span-2 flex flex-wrap gap-4 items-center">
                                 <label class="flex items-center gap-2 cursor-pointer group">
                                     <input type="checkbox" name="availability" value="in_stock" {{ request('availability') == 'in_stock' ? 'checked' : '' }} class="accent-black">
                                     <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black group-hover:text-black">In Stock</span>
                                 </label>
                                 <label class="flex items-center gap-2 cursor-pointer group">
                                     <input type="checkbox" name="new_arrivals" value="1" {{ request('new_arrivals') ? 'checked' : '' }} class="accent-black">
                                     <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black group-hover:text-black">New Arrivals</span>
                                 </label>
                                 <label class="flex items-center gap-2 cursor-pointer group">
                                     <input type="checkbox" name="featured" value="1" {{ request('featured') ? 'checked' : '' }} class="accent-black">
                                     <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black group-hover:text-black">Featured</span>
                                 </label>
                                 <label class="flex items-center gap-2 cursor-pointer group">
                                     <input type="checkbox" name="discount" value="1" {{ request('discount') ? 'checked' : '' }} class="accent-black">
                                     <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black group-hover:text-black">Offers</span>
                                 </label>
                                 <label class="flex items-center gap-2 cursor-pointer group">
                                     <input type="checkbox" name="best_sellers" value="1" {{ request('best_sellers') ? 'checked' : '' }} class="accent-black">
                                     <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black group-hover:text-black">Best Sellers</span>
                                 </label>
                             </div>
                         </div>

                         <!-- Conditional: Gem Filters -->
                         <div id="gem-filters" class="{{ $currentType == 'gem' ? '' : 'hidden' }} grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 animate-fade-in">
                             
                             <!-- Gem Type -->
                             <div>
                                 <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black mb-2 block">Gem Type</label>
                                 <select name="gemstone" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black appearance-none">
                                     <option value="">Any Type</option>
                                     @foreach($filterOptions['gemstones'] as $gem)
                                         <option value="{{ $gem }}" {{ request('gemstone') == $gem ? 'selected' : '' }}>{{ $gem }}</option>
                                     @endforeach
                                 </select>
                             </div>

                             <!-- Gem Category -->
                             <div>
                                 <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black mb-2 block">Category</label>
                                 <select name="category" id="gem-category-select" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black appearance-none">
                                     <option value="">All Gems</option>
                                     @php
                                          $gemParentIds = $categories->whereNull('parent_id')
                                              ->filter(fn($c) => !str_contains($c->slug, 'gold') && !str_contains($c->slug, 'platinum') && !str_contains($c->slug, 'silver'))
                                              ->pluck('id')
                                              ->toArray();
                                      @endphp
                                      @foreach($categories->wherein('parent_id', $gemParentIds)->unique('name') as $sub)
                                          <option value="{{ $sub->name }}" data-type="gem" {{ request('category') == $sub->name ? 'selected' : '' }}>{{ $sub->name }}</option>
                                      @endforeach
                                 </select>
                             </div>

                             <!-- Shape / Cut -->
                             <div>
                                 <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black mb-2 block">Shape / Cut</label>
                                 <select name="shape" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black appearance-none">
                                     <option value="">Any Shape</option>
                                     @foreach($filterOptions['shapes'] as $shape)
                                         <option value="{{ $shape }}" {{ request('shape') == $shape ? 'selected' : '' }}>{{ $shape }}</option>
                                     @endforeach
                                 </select>
                             </div>
                             
                             <!-- Treatment -->
                             <div>
                                 <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black mb-2 block">Treatment</label>
                                 <select name="treatment" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black appearance-none">
                                     <option value="">Any Treatment</option>
                                     @foreach($filterOptions['treatments'] as $treatment)
                                         <option value="{{ $treatment }}" {{ request('treatment') == $treatment ? 'selected' : '' }}>{{ $treatment }}</option>
                                     @endforeach
                                 </select>
                             </div>

                             <!-- Weight (Carat) -->
                             <div class="col-span-2">
                                 <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black mb-2 block">Weight (Carat)</label>
                                 <div class="flex items-center gap-2">
                                     <input type="number" step="0.01" name="min_weight" placeholder="Min Ct" value="{{ request('min_weight') }}" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black">
                                     <span class="text-black">-</span>
                                     <input type="number" step="0.01" name="max_weight" placeholder="Max Ct" value="{{ request('max_weight') }}" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black">
                                 </div>
                             </div>
                         </div>

                         <!-- Conditional: Jewelry Filters -->
                         <div id="jewelry-filters" class="{{ $currentType == 'jewelry' ? '' : 'hidden' }} grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 animate-fade-in">
                             
                             <!-- Metal -->
                             <div>
                                 <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black mb-2 block">Metal</label>
                                 <select name="metal" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black appearance-none">
                                      <option value="">Any Metal</option>
                                      @foreach($categories->whereNull('parent_id')->filter(fn($c) => str_contains($c->slug, 'gold') || str_contains($c->slug, 'platinum') || str_contains($c->slug, 'silver')) as $parent)
                                          <option value="{{ $parent->slug }}" {{ request('metal') == $parent->slug ? 'selected' : '' }}>{{ $parent->name }}</option>
                                      @endforeach
                                  </select>
                             </div>

                             <!-- Category (Sub-category for jewelry) -->
                             <div>
                                 <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black mb-2 block">Category</label>
                                 <select name="category" id="jewelry-category-select" class="w-full bg-white border border-gray-200 p-2 font-space-mono text-xs rounded-none focus:outline-none focus:border-black appearance-none">
                                      <option value="">All Categories</option>
                                      @php
                                          $jewelryParentIds = $categories->whereNull('parent_id')
                                              ->filter(fn($c) => str_contains($c->slug, 'gold') || str_contains($c->slug, 'platinum') || str_contains($c->slug, 'silver'))
                                              ->pluck('id')
                                              ->toArray();
                                      @endphp
                                      @foreach($categories->wherein('parent_id', $jewelryParentIds)->unique('name') as $sub)
                                          <option value="{{ $sub->name }}" data-type="jewelry" {{ request('category') == $sub->name ? 'selected' : '' }}>{{ $sub->name }}</option>
                                      @endforeach
                                  </select>
                             </div>
                         </div>

                         <!-- Actions -->
                         <div class="flex items-end justify-end gap-4 pt-4 border-t border-gray-100">
                             <a href="{{ route('products.index') }}" class="font-space-mono text-xs underline text-black hover:text-black transition">Reset</a>
                             <button type="submit" class="hidden bg-black text-white px-6 py-2 font-space-mono text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">Apply Filters</button>
                         </div>
                     </div>
                 </form>
             </div>
        </div>
    </section>

    <!-- Filter Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('filter-toggle');
            const drawer = document.getElementById('filter-drawer');
            const icon = document.getElementById('filter-icon');
            const form = document.getElementById('filter-form');
            const mainContent = document.querySelector('main');
            
            let isOpen = false;

            // Drawer Toggle
            toggleBtn.addEventListener('click', () => {
                isOpen = !isOpen;
                if (isOpen) {
                    drawer.style.maxHeight = '1500px'; 
                    icon.style.transform = 'rotate(45deg)';
                } else {
                    drawer.style.maxHeight = '0px';
                    icon.style.transform = 'rotate(0deg)';
                }
            });

            // Dynamic Type Filtering with Visual Feedback
            const typeSelect = document.getElementById('type-select');
            const gemFilters = document.getElementById('gem-filters');
            const jewelryFilters = document.getElementById('jewelry-filters');
            const jewelryCategorySelect = document.getElementById('jewelry-category-select');
            const gemCategorySelect = document.getElementById('gem-category-select');

            function updateFilterVisibility(typeValue) {
                // Hide all type-specific filters first
                gemFilters?.classList.add('hidden');
                jewelryFilters?.classList.add('hidden');
                
                // Disable hidden inputs so they aren't submitted and don't conflict (e.g. same 'category' name)
                disableInputs(gemFilters);
                disableInputs(jewelryFilters);

                // Show appropriate filters based on type
                if (typeValue === 'gem') {
                    gemFilters?.classList.remove('hidden');
                    enableInputs(gemFilters);
                    // Clear jewelry-specific inputs when switching
                    if (typeValue !== typeSelect.getAttribute('data-last')) {
                         clearInputs(jewelryFilters);
                    }
                } else if (typeValue === 'jewelry') {
                    jewelryFilters?.classList.remove('hidden');
                    enableInputs(jewelryFilters);
                    // Clear gem-specific inputs when switching
                    if (typeValue !== typeSelect.getAttribute('data-last')) {
                        clearInputs(gemFilters);
                    }
                }
                
                typeSelect.setAttribute('data-last', typeValue);
            }

            function disableInputs(container) {
                if (!container) return;
                const elements = container.querySelectorAll('input, select');
                elements.forEach(el => el.disabled = true);
            }

            function enableInputs(container) {
                if (!container) return;
                const elements = container.querySelectorAll('input, select');
                elements.forEach(el => el.disabled = false);
            }

            // Initialize on page load
            if (typeSelect) {
                const initialType = typeSelect.value;
                typeSelect.setAttribute('data-last', initialType);
                updateFilterVisibility(initialType);

                typeSelect.addEventListener('change', (e) => {
                    const val = e.target.value;
                    updateFilterVisibility(val);
                    
                    // Update visual state
                    if (val) {
                        typeSelect.style.borderColor = '#1f2937';
                        typeSelect.style.backgroundColor = '#f0f0f0';
                    } else {
                        typeSelect.style.borderColor = '#e5e7eb';
                        typeSelect.style.backgroundColor = '#ffffff';
                    }
                    
                    fetchProducts();
                });

                // Set initial visual state
                if (typeSelect.value) {
                    typeSelect.style.borderColor = '#1f2937';
                    typeSelect.style.backgroundColor = '#f0f0f0';
                }
            }

            // Auto-Submit / Fetch on Input Change
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.id === 'type-select') return;
                input.addEventListener('change', () => {
                    fetchProducts();
                });
            });

            // Search Logic
            const searchInput = document.getElementById('search-input');
            const searchForm = document.getElementById('search-form');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        // Update hidden input in main filter form if it exists, or append it
                        let hiddenSearch = form.querySelector('input[name="search"]');
                        if (!hiddenSearch) {
                            hiddenSearch = document.createElement('input');
                            hiddenSearch.type = 'hidden';
                            hiddenSearch.name = 'search';
                            form.appendChild(hiddenSearch);
                        }
                        hiddenSearch.value = e.target.value;
                        
                        fetchProducts();
                    }, 500); // 500ms debounce
                });

                // Prevent default form submit and use AJAX
                if (searchForm) {
                    searchForm.addEventListener('submit', (e) => {
                        e.preventDefault();
                        // Trigger immediate fetch
                        let hiddenSearch = form.querySelector('input[name="search"]');
                        if (!hiddenSearch) {
                            hiddenSearch = document.createElement('input');
                            hiddenSearch.type = 'hidden';
                            hiddenSearch.name = 'search';
                            form.appendChild(hiddenSearch);
                        }
                        hiddenSearch.value = searchInput.value;
                        fetchProducts();
                    });
                }
            }

            // Helper to clear hidden inputs
            function clearInputs(container) {
                const inputs = container.querySelectorAll('input, select');
                inputs.forEach(input => {
                    if(input.type === 'checkbox' || input.type === 'radio') input.checked = false;
                    else input.value = '';
                });
            }

            // AJAX Fetch Function
            function fetchProducts(url = null) {
                // Show loading state (opacity)
                mainContent.style.opacity = '0.5';
                mainContent.style.pointerEvents = 'none';

                let fetchUrl = url;
                if (!fetchUrl) {
                    // Build URL from form data
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    // Remove empty params
                    for (const [key, value] of [...params]) {
                        if (value === '') params.delete(key);
                    }
                    fetchUrl = `${form.action}?${params.toString()}`;
                }

                // Update Browser URL
                window.history.pushState(null, '', fetchUrl);

                fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    mainContent.innerHTML = html;
                    mainContent.style.opacity = '1';
                    mainContent.style.pointerEvents = 'auto';
                    
                    // Scroll to top if pagination
                    if (url) {
                        const filterBar = document.getElementById('filter-bar');
                        if (filterBar) {
                            filterBar.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        } else {
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    }
                    
                    // Re-attach pagination listeners if needed (if pagination is inside partial)
                    attachPaginationListeners();
                    
                    // Re-initialize product grid animations (video hover)
                    if (typeof window.initProductGrid === 'function') {
                        window.initProductGrid();
                    }
                })
                .catch(err => {
                    console.error('Filter error:', err);
                    mainContent.style.opacity = '1';
                });
            }

            // Handle Pagination Clicks via AJAX
            function attachPaginationListeners() {
                const paginationLinks = mainContent.querySelectorAll('a.page-link, .pagination a'); 
                // Laravel tailwind pagination uses specific classes, usually just anchor tags inside nav
                mainContent.querySelectorAll('nav[role="navigation"] a').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        fetchProducts(link.href);
                    });
                });
            }
            
            // Initial attach
            attachPaginationListeners();
        });
    </script>

    <!-- Product Grid -->
    <main class="flex-grow w-full">
        @include('products.partials.product-grid')
    </main>

    <x-footer />

    <x-toast />
    <script>
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
                    
                    // Update UI
                    const svg = btn.querySelector('svg');
                    if (data.action === 'added') {
                        btn.classList.remove('text-black', 'text-gray-400', 'hover:border-black');
                        btn.classList.add('text-red-600');
                        svg.classList.remove('fill-none');
                        svg.classList.add('fill-current');
                        btn.title = 'Remove from Wishlist';
                    } else {
                        btn.classList.remove('text-red-600');
                        // Restore default classes based on context (grid vs show)
                        // This handles the grid button primarily
                        btn.classList.add('text-black');
                        svg.classList.remove('fill-current');
                        svg.classList.add('fill-none');
                        btn.title = 'Add to Wishlist';
                    }
                } else {
                    showToast('Something went wrong.', 'error');
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
                    quantity: 1
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
                           if(textSpan) textSpan.innerText = 'Remove from Cart';
                            const pulseSpan = btn.querySelector('span:last-child');
                           if(pulseSpan) { 
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
                           if(textSpan) textSpan.innerText = 'Add to Cart';
                            const pulseSpan = btn.querySelector('span:last-child');
                           if(pulseSpan) {
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


    </script>

    <!-- Quick View Modal -->
    <div id="quickViewModal" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div onclick="closeQuickView()" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity opacity-0" id="quickViewBackdrop"></div>
        
        <!-- Content Container -->
        <div class="relative flex items-center justify-center min-h-screen p-4 opacity-0 scale-95 transition-all duration-300" id="quickViewContent">
             <!-- Dynamic Content Loaded Here -->
             <div class="text-white">Loading...</div>
        </div>
    </div>

    <script>
        function showQuickView(productId) {
            const modal = document.getElementById('quickViewModal');
            const backdrop = document.getElementById('quickViewBackdrop');
            const content = document.getElementById('quickViewContent');
            const contentContainer = content.querySelector('div') || content; // Fallback

            // Show Modal Container
            modal.classList.remove('hidden');
            
            // Animate In
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                content.classList.remove('opacity-0', 'scale-95');
                content.classList.add('opacity-100', 'scale-100');
            }, 10);

            // Fetch Content
            fetch(`/products/${productId}/quick-view`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(html => {
                    content.innerHTML = html;
                    
                    // Re-initialize any scripts/styles if necessary (not needed for simple HTML)
                    // But we might need to ensuring the new content has the right classes for animation if needed.
                    // The returned HTML is a <div class="relative bg-white..."> which fits perfectly.
                })
                .catch(error => {
                    console.error('Error loading quick view:', error);
                    content.innerHTML = '<div class="bg-white p-8 rounded-lg text-red-500 shadow-xl">Failed to load product details. Please try again.</div>';
                });
        }

        function closeQuickView() {
            const modal = document.getElementById('quickViewModal');
            const backdrop = document.getElementById('quickViewBackdrop');
            const content = document.getElementById('quickViewContent');

            // Animate Out
            backdrop.classList.add('opacity-0');
            content.classList.remove('opacity-100', 'scale-100');
            content.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                // Clear content to prevent old content flashing on next open
                content.innerHTML = '<div class="text-white">Loading...</div>';
            }, 300);
        }

        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeQuickView();
            }
        });
    </script>
</body>
</html>
