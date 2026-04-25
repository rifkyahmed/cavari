<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>{{ $journal->meta_title ?? $journal->title }} | {{ config('app.name', 'Cavari') }}</title>
    <meta name="description" content="{{ $journal->meta_description ?? Str::limit(strip_tags($journal->excerpt ?? $journal->content), 150) }}">

    <!-- Open Graph for Social Sharing -->
    <meta property="og:title" content="{{ $journal->title }}">
    <meta property="og:description" content="{{ $journal->meta_description ?? Str::limit(strip_tags($journal->excerpt), 150) }}">
    <meta property="og:image" content="{{ asset($journal->cover_image) }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta name="twitter:card" content="summary_large_image">

    <!-- BlogPosting JSON-LD Schema (Crucial for Content SEO) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BlogPosting",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ request()->url() }}"
      },
      "headline": "{{ $journal->title }}",
      "description": "{{ $journal->meta_description ?? Str::limit(strip_tags($journal->excerpt), 150) }}",
      "image": "{{ asset($journal->cover_image) }}",  
      "author": {
        "@type": "Organization",
        "name": "Cavari Master Jewelers"
      },  
      "publisher": {
        "@type": "Organization",
        "name": "Cavari",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ asset('images/logo.png') }}"
        }
      },
      "datePublished": "{{ $journal->published_at ? $journal->published_at->toIso8601String() : $journal->created_at->toIso8601String() }}",
      "dateModified": "{{ $journal->updated_at->toIso8601String() }}"
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
        
        .bg-hero-gradient {
            background: linear-gradient(135deg, #fff 0%, #fff0f5 50%, #fff 100%);
        }

        /* Typography spacing inside blog content */
        .prose p { margin-bottom: 2rem; line-height: 2; color: #4b5563; font-size: 1.125rem; font-family: 'Instrument Sans', sans-serif; }
        .prose h2 { margin-top: 4rem; margin-bottom: 2rem; font-family: 'Gloock', serif; font-size: 2.5rem; color: #000; line-height: 1.2; }
        .prose h3 { margin-top: 3rem; margin-bottom: 1.5rem; font-family: 'Space Mono', monospace; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.1em; color: #000; }
        .prose strong { color: #000; font-weight: 600; }
        .prose img { width: 100%; height: auto; border-radius: 4px; margin: 3rem 0; mix-blend-mode: multiply; }
        .prose blockquote { border-left: 2px solid #000; padding-left: 2rem; margin: 3rem 0; font-family: 'Gloock', serif; font-style: italic; font-size: 1.5rem; color: #000; }
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient min-h-screen flex flex-col">

    <x-navbar />

    <main class="flex-grow w-full pt-32 pb-24">
        
        <!-- Article Header -->
        <article class="max-w-4xl mx-auto px-6">
            <nav class="flex items-center space-x-2 text-[10px] font-space-mono uppercase tracking-widest text-gray-400 mb-16">
                <a href="{{ route('home') }}" class="hover:text-black">Home</a>
                <span>/</span>
                <a href="{{ route('journal.index') }}" class="hover:text-black">Journal</a>
                <span>/</span>
                <span class="text-black">{{ Str::limit($journal->title, 30) }}</span>
            </nav>

            <header class="mb-16 text-center">
                <h1 class="font-gloock text-5xl md:text-7xl lg:text-8xl text-black leading-[0.9] mb-8">
                    {{ $journal->title }}
                </h1>
                
                <div class="flex items-center justify-center gap-8 border-t border-b border-black/10 py-6">
                    <div class="flex flex-col items-center">
                        <span class="font-space-mono text-[10px] uppercase tracking-widest text-gray-400 mb-1">Published</span>
                        <span class="font-instrument text-sm text-black">{{ $journal->published_at ? $journal->published_at->format('M d, Y') : $journal->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="w-px h-8 bg-black/10"></div>
                    <div class="flex flex-col items-center">
                        <span class="font-space-mono text-[10px] uppercase tracking-widest text-gray-400 mb-1">Author</span>
                        <span class="font-instrument text-sm text-black">Cavari Editor</span>
                    </div>
                    <div class="w-px h-8 bg-black/10"></div>
                     <div class="flex flex-col items-center">
                        <span class="font-space-mono text-[10px] uppercase tracking-widest text-gray-400 mb-1">Time</span>
                        <span class="font-instrument text-sm text-black">4 min read</span>
                    </div>
                </div>
            </header>

            <!-- Cover Image -->
            <div class="w-full aspect-[16/9] bg-gray-100 mb-20 overflow-hidden relative shadow-lg rounded-[2px]">
                <img src="{{ $journal->cover_image ? asset($journal->cover_image) : asset('images/hero-gem.png') }}" 
                     alt="{{ $journal->title }} Cover Image" 
                     loading="lazy"
                     class="w-full h-full object-cover mix-blend-multiply opacity-95 hover:scale-105 transition-transform duration-[2s] ease-in-out">
            </div>

            <!-- Content Body -->
            <div class="prose max-w-2xl mx-auto">
                {!! $journal->content !!}
            </div>

            <!-- Share Component (Social Signals + Backlinks Generation) -->
            <div class="max-w-2xl mx-auto mt-16 mb-12 pt-12 flex items-center justify-between">
                <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black">Share the Knowledge</span>
                
                <div class="flex items-center gap-6">
                    <!-- Twitter -->
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($journal->title) }}&url={{ urlencode(request()->url()) }}" target="_blank" class="text-gray-400 hover:text-black transition-colors" title="Share on Twitter" aria-label="Share on Twitter">
                         <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <!-- Pinterest -->
                    <a href="http://pinterest.com/pin/create/button/?url={{ urlencode(request()->url()) }}&media={{ urlencode(asset($journal->cover_image)) }}&description={{ urlencode($journal->meta_description ?? $journal->title) }}" target="_blank" class="text-gray-400 hover:text-black transition-colors" title="Pin this" aria-label="Pin this image">
                         <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.372 0 0 5.373 0 12c0 4.084 2.54 7.575 6.2 9.176-.084-.75-.157-1.9.034-2.73l1.196-5.071s-.308-.616-.308-1.527c0-1.428.828-2.492 1.861-2.492.88 0 1.304.66 1.304 1.45 0 .884-.564 2.203-.854 3.428-.242 1.026.514 1.865 1.524 1.865 1.832 0 3.237-1.933 3.237-4.72 0-2.47-1.777-4.194-4.288-4.194-2.888 0-4.582 2.166-4.582 4.4 0 .882.34 1.83 1.135 2.13.111.042.128.093.097.202l-.304 1.258c-.027.112-.08.134-.183.085-1.127-.517-1.832-2.146-1.832-3.465 0-2.822 2.05-5.415 5.92-5.415 3.11 0 5.526 2.214 5.526 5.166 0 3.091-1.95 5.586-4.663 5.586-.88 0-1.76-.474-2.102-1.077l-.544 2.12c-.22.868-.838 1.955-1.25 2.618C10.604 23.868 11.292 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/></svg>                    
                    </a>
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="text-gray-400 hover:text-black transition-colors" title="Share on Facebook" aria-label="Share on Facebook">
                         <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Author Block -->
            <div class="max-w-2xl mx-auto mb-16 bg-white/60 backdrop-blur-md p-10 border border-black/5 flex flex-col md:flex-row items-center gap-8 shadow-sm">
                <div class="w-24 h-24 rounded-full bg-black flex items-center justify-center shrink-0">
                    <span class="font-gloock text-4xl text-white">C</span>
                </div>
                <div class="text-center md:text-left">
                    <h4 class="font-gloock text-2xl mb-2 text-black">Cavari Master Jewelers</h4>
                    <p class="font-instrument text-gray-500 leading-relaxed text-sm">
                        Curators of Earth's rarest treasures. We traverse the globe sourcing ethical, unparalleled gems to craft legacies.
                    </p>
                </div>
            </div>
            
        </article>

        <!-- Up Next / Read More Section -->
        @if($recentJournals->count() > 0)
            <div class="w-full pt-12 pb-6 bg-gray-50/50">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="flex items-end justify-between mb-12">
                        <h2 class="font-gloock text-5xl text-black">Keep <span class="italic text-gray-400">Reading</span></h2>
                        <a href="{{ route('journal.index') }}" class="font-space-mono text-[10px] font-bold uppercase tracking-widest border-b border-black pb-1 hover:text-gray-500 hover:border-gray-500 transition-colors">
                            The Archives
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-l border-black/10">
                        @foreach($recentJournals as $recent)
                            <a href="{{ route('journal.show', $recent->slug) }}" class="group border-r border-black/10 p-8 flex flex-col hover:bg-white transition-colors duration-500 h-[450px]">
                                <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-6 group-hover:text-black transition-colors">{{ $recent->published_at ? $recent->published_at->format('M d, Y') : 'Recent' }}</span>
                                
                                <div class="w-full h-40 mb-8 bg-gray-100 overflow-hidden rounded-[2px]">
                                    <img src="{{ $recent->cover_image ? asset($recent->cover_image) : asset('images/hero-gem.png') }}" 
                                         alt="{{ $recent->title }}"
                                         loading="lazy" 
                                         class="w-full h-full object-cover mix-blend-multiply opacity-80 group-hover:scale-110 group-hover:opacity-100 transition-all duration-[1s]">
                                </div>
                                
                                <h3 class="font-gloock text-3xl text-black mt-auto group-hover:text-gray-600 transition-colors line-clamp-3 leading-tight">{{ $recent->title }}</h3>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </main>

    <x-footer />
</body>
</html>
