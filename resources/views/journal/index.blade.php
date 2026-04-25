<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>The Journal | {{ config('app.name', 'Cavari') }}</title>
    <meta name="description" content="Discover the world of luxury gemstones and bespoke jewelry through the Cavari Journal. Educational insights, gemology guides, and our heritage.">

    <!-- JSON-LD Collection Schema for SEO -->
    @if($journals->count() > 0)
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ItemList",
      "itemListElement": [
        @foreach($journals as $index => $post)
        {
          "@type": "ListItem",
          "position": {{ $index + 1 }},
          "item": {
            "@type": "BlogPosting",
            "url": "{{ route('journal.show', $post->slug) }}",
            "name": "{{ $post->title }}",
            "headline": "{{ $post->title }}",
            "description": "{{ $post->meta_description ?? Str::limit(strip_tags($post->excerpt), 150) }}",
            "image": "{{ $post->cover_image ? asset($post->cover_image) : asset('images/logo.png') }}",
            "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}"
          }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    }
    </script>
    @endif

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
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient min-h-screen flex flex-col">

    <x-navbar />

    <!-- Hero Section -->
    <header class="relative w-full pt-32 pb-16 md:pt-40 md:pb-24 px-6 border-b border-black/10">
        <div class="max-w-7xl mx-auto text-center">
            <span class="font-space-mono text-xs font-bold uppercase tracking-[0.3em] text-gray-400 mb-4 block">
                Editorial
            </span>
            <h1 class="font-gloock text-6xl md:text-9xl text-black mb-6 leading-none">
                The Journal
            </h1>
            <p class="font-instrument text-sm md:text-lg text-gray-600 max-w-xl mx-auto leading-relaxed">
                Insights into the world of luxury gemology, sustainable sourcing, and the timeless art of bespoke jewelry crafting.
            </p>
        </div>
    </header>

    <!-- Journals Grid -->
    <main class="flex-grow w-full max-w-7xl mx-auto px-6 pt-24 pb-12">
        @if($journals->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                @foreach($journals as $post)
                    <a href="{{ route('journal.show', $post->slug) }}" class="group block">
                        <!-- Image Container -->
                        <div class="w-full aspect-[4/5] bg-gray-100 mb-6 overflow-hidden relative rounded-sm">
                            <img src="{{ $post->cover_image ? asset($post->cover_image) : asset('images/hero-gem.png') }}" 
                                 alt="{{ $post->title }} - Cavari Journal" 
                                 loading="lazy"
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700 ease-in-out opacity-90 group-hover:opacity-100 mix-blend-multiply">
                            
                            <div class="absolute bottom-4 left-4 z-10">
                                <span class="bg-white/90 backdrop-blur-sm px-3 py-1 font-space-mono text-[10px] uppercase tracking-widest text-black shadow-sm">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Recent' }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div>
                            <h3 class="font-gloock text-3xl text-black mb-3 group-hover:text-gray-600 transition-colors duration-300 leading-tight">
                                {{ $post->title }}
                            </h3>
                            <p class="font-instrument text-gray-600 leading-relaxed mb-6">
                                {{ Str::limit($post->excerpt, 120) }}
                            </p>
                            
                            <div class="flex items-center gap-4 text-black group-hover:text-gray-600 transition-colors">
                                <span class="font-space-mono text-xs font-bold tracking-[0.2em] uppercase">Read Article</span>
                                <span class="w-8 h-[1px] bg-black group-hover:bg-gray-600 group-hover:w-12 transition-all duration-300"></span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="w-full flex justify-center py-10 mt-8">
                {{ $journals->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <span class="font-gloock text-6xl text-gray-200 mb-6 block">∅</span>
                <h3 class="font-gloock text-3xl text-black mb-2">The Archives are Resting</h3>
                <p class="font-instrument text-gray-500 max-w-md mx-auto">
                    We are currently preparing our next editorial pieces. Please check back soon.
                </p>
            </div>
        @endif
    </main>

    <x-footer />
</body>
</html>
