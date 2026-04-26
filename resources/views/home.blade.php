<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>{{ config('app.name', 'Cavari') }} | Exquisite Gems & Luxury Jewelry</title>
    <meta name="description"
        content="Discover exquisite 18k gold diamond rings, sapphires, emeralds and bespoke luxury jewelry at Cavari. Handcrafted gems perfect for engagements and collectors.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'Cavari') }} | Exquisite Gems & Luxury Jewelry">
    <meta property="og:description"
        content="Discover exquisite 18k gold diamond rings, sapphires, emeralds and bespoke luxury jewelry at Cavari. Handcrafted gems perfect for engagements and collectors.">
    <meta property="og:image" content="{{ asset('images/og-home.png') }}">
    <meta property="og:site_name" content="Cavari">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ config('app.name', 'Cavari') }} | Exquisite Gems & Luxury Jewelry">
    <meta property="twitter:description"
        content="Discover exquisite 18k gold diamond rings, sapphires, emeralds and bespoke luxury jewelry at Cavari. Handcrafted gems perfect for engagements and collectors.">
    <meta property="twitter:image" content="{{ asset('images/og-home.png') }}">

    <!-- WebSite Schema JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "WebSite",
      "name": "Cavari",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/products?search={search_term_string}') }}",
        "query-input": "required name=search_term_string"
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

        /* Custom Text Outline */
        .text-outline-hero {
            color: transparent;
            -webkit-text-stroke: 1px #000;
            /* Black stroke */
        }

        /* Floating Animation */
        @keyframes float-gem {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(1deg);
            }
        }

        .animate-float-gem {
            animation: float-gem 6s ease-in-out infinite;
        }

        /* Global Continuous Luxury Pink Atmosphere */
        .bg-hero-gradient {
            background: linear-gradient(135deg, #fff 0%, #fff1f2 30%, #ffe4e6 70%, #fff 100%);
            background-attachment: fixed;
            background-size: cover;
        }

        /* Marquee Animation */
        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .animate-marquee-infinite {
            display: flex;
            animation: marquee 40s linear infinite;
        }

        /* Responsive About Section Heading */
        .about-heading {
            font-family: 'Gloock', serif;
            font-style: italic;
            line-height: 1.1;
            color: #000;
            margin-bottom: 3rem;
            text-align: left;
        }

        /* Mobile (default) */
        .about-heading {
            font-size: clamp(2.5rem, 12vw, 4rem);
            margin-left: 0;
        }

        /* Tablet (768px to 1024px) - Make it same as mobile layout but slightly bigger */
        @media (min-width: 768px) and (max-width: 1023px) {
            .about-heading {
                font-size: clamp(4rem, 12vw, 6rem);
                text-align: center;
                margin-left: 0;
            }

            .about-tagline {
                text-align: center;
                margin-top: 3rem;
            }

            .about-indented-box {
                border-left: none !important;
                padding-left: 0 !important;
                text-align: center;
                margin-left: auto;
                margin-right: auto;
            }
        }

        /* Laptop (1024px and up) */
        @media (min-width: 1024px) {
            .about-heading {
                font-size: clamp(3.5rem, 8vw, 5.5rem);
                margin-left: 0;
                text-align: left;
            }
        }

        /* Desktop (1440px and up) */
        @media (min-width: 1440px) {
            .about-heading {
                font-size: clamp(4rem, 7vw, 6rem);
                margin-left: -2rem;
            }
        }

        /* Large Desktop (1920px and up) */
        @media (min-width: 1920px) {
            .about-heading {
                font-size: clamp(4rem, 8vw, 6.5rem);
                margin-left: -3rem;
            }
        }

        /* Large Desktop (2560px and up) - PERFECT LAYOUT */
        @media (min-width: 2560px) {
            .about-heading {
                font-size: clamp(4rem, 9vw, 7rem);
                margin-left: -5rem;
            }
        }

        /* Responsive Tagline */
        .about-tagline {
            margin-left: 0;
            text-align: left;
        }

        @media (min-width: 1440px) {
            .about-tagline {
                margin-left: -2rem;
            }
        }

        @media (min-width: 1920px) {
            .about-tagline {
                margin-left: -3rem;
            }
        }

        @media (min-width: 2560px) {
            .about-tagline {
                margin-left: -5rem;
            }
        }

        /* Mobile & Tablet Adjustments (up to 1024px) */
        @media (max-width: 1023px) {
            .text-outline-hero {
                font-size: 28vw !important;
            }

            @media (min-width: 768px) {
                .text-outline-hero {
                    font-size: 24vw !important;
                }
            }

            .about-heading {
                text-align: center;
                margin-bottom: 2rem;
            }

            #hero-gem-container model-viewer {
                width: 85vw !important;
                height: 85vw !important;
            }

            @media (min-width: 768px) {
                #hero-gem-container model-viewer {
                    width: 60vw !important;
                    height: 60vw !important;
                }
            }

            .about-tagline {
                text-align: center;
                margin-top: 2rem;
            }

            .about-indented-box {
                border-left: none !important;
                padding-left: 0 !important;
                text-align: center;
                margin-left: auto;
                margin-right: auto;
            }

            .reviews-heading {
                font-size: 4rem !important;
                line-height: 1.1 !important;
                white-space: normal !important;
                text-align: center;
                margin-bottom: 2rem;
            }

            @media (min-width: 768px) {
                .reviews-heading {
                    font-size: 5.5rem !important;
                }
            }

            .reviews-image-container {
                display: none !important;
            }

            .reviews-quote-container {
                padding-left: 0 !important;
                text-align: center;
            }

            .reviews-quote-container .absolute {
                right: 0 !important;
                left: 0 !important;
                display: flex;
                justify-content: center;
                width: 100%;
            }
        }

        /* ── model-viewer: kill default grey poster/loading bar ── */
        model-viewer {
            background: transparent !important;
            --poster-color: transparent !important;
        }

        model-viewer::part(default-progress-bar) {
            display: none !important;
        }

        model-viewer::part(default-progress-mask) {
            display: none !important;
        }

        /* Restore soft glow for mobile */
        @media (max-width: 1024px) {
            .hero-glow {
                filter: blur(80px) !important;
                -webkit-filter: blur(80px) !important;
                opacity: 0.35 !important;
            }
        }
    </style>

    <!-- Model Viewer for 3D Gem -->
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.3.0/model-viewer.min.js"></script>

    <!-- Smooth Scroll (Lenis) -->
    <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.33/dist/lenis.min.js"></script>
</head>

<body class="font-instrument antialiased text-gray-900 bg-hero-gradient overflow-x-hidden">

    <!-- Header -->
    <!-- Header -->
    <x-navbar />

    <!-- Main Hero Section -->
    <main class="relative w-full h-[100vh] min-h-[700px] flex items-center justify-center z-40">

        <!-- Screen-width Background Text Container (to prevent scrollbars but allow gem to exit main) -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Large Background Text (CAVARI) -->
            <h1 class="absolute left-1/2 top-[35%] lg:top-1/2 transform -translate-x-1/2 -translate-y-1/2 font-gloock text-outline-hero z-0 pointer-events-none select-none"
                style="font-size: 25vw; line-height: 1;">
                CAVARI
            </h1>
        </div>

        <!-- Central 3D Gemstone -->
        <div class="relative z-50 w-full max-w-4xl flex justify-center px-4 mt-12 md:mt-3 min-h-[500px] lg:min-h-0" id="hero-gem-container">
            <!-- Luxury Pink Glow Behind Gem -->
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-pink-300/20 blur-[120px] rounded-full pointer-events-none -z-10 hero-glow">
            </div>

            <model-viewer id="main-3d-gem" src="{{ asset('images/perfect_ruby.glb') }}" alt="3D Rare Gemstone"
                disable-zoom camera-orbit="0deg 85deg auto" field-of-view="28deg" environment-image="neutral"
                tone-mapping="aces" exposure="1.2" shadow-intensity="0" shadow-softness="0" loading="eager" 
                power-preference="high-performance" minimum-render-scale="1" auto-rotate
                rotation-per-second="3deg" interaction-prompt="none" auto-rotate-delay="0"
                class="w-[550px] h-[550px] max-w-[100vw] sm:w-[80vw] sm:h-[80vw] md:w-[70vw] md:h-[70vw] lg:w-[40vw] lg:h-[40vw] object-contain drop-shadow-2xl cursor-default pointer-events-none will-change-transform"
                style="--poster-color: transparent;">
            </model-viewer>
        </div>

        <!-- Tagline (Right) -->
        <div class="absolute right-6 z-20 hidden lg:block text-right" style="top: 27%; right: 7%;">
            <p
                class="font-space-mono text-[10px] md:text-xs tracking-[0.25em] font-bold uppercase leading-relaxed text-black whitespace-nowrap">
                The Art of Earth's Rarest Treasures.
            </p>
        </div>

        <!-- Bottom Hero HUD -->
        <div
            class="absolute bottom-[14%] md:bottom-[10%] lg:bottom-[22%] left-6 right-6 lg:left-[8%] lg:right-[7%] z-20 flex flex-col lg:flex-row items-center lg:items-end justify-between gap-5 lg:gap-0">
            <!-- Description (Left) -->
            <div class="max-w-full lg:max-w-md text-center lg:text-left mx-auto lg:mx-0">
                <p
                    class="font-instrument text-[10px] sm:text-[11px] md:text-[13px] lg:text-xs tracking-[0.05em] lg:tracking-widest leading-loose text-gray-900 uppercase font-medium">
                    Uncover exceptional, ethically sourced gemstones<br>
                    curated for the world's most discerning collectors.
                </p>
            </div>

            <!-- CTA Button (Right on Desktop, Left on Mobile) -->
            <div class="flex justify-center lg:block">
                <a href="{{ route('products.index') }}"
                    class="group flex items-center bg-black text-white pl-8 pr-6 py-4 rounded-full hover:bg-gray-900 transition shadow-xl scale-110 md:scale-125 lg:scale-100">
                    <span class="font-space-mono text-xs font-bold tracking-widest uppercase mr-3">Discover the
                        Collection</span>
                    <svg class="w-4 h-4 transform group-hover:rotate-45 transition duration-500 ease-out" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 19L19 5M19 5H9M19 5V15"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Social Icons (Bottom Left) -->
        <div class="absolute bottom-8 md:-bottom-4 lg:bottom-8 left-6 md:left-20 z-20 flex items-center">
            <!-- Back/Chevron Circle -->
            <button id="social-toggle"
                class="relative z-30 w-10 h-10 border border-gray-900 rounded-full flex items-center justify-center bg-transparent transition-colors duration-300 hover:bg-black hover:text-white group">
                <svg id="social-chevron" class="w-4 h-4 transition-transform duration-500 transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <!-- Socials -->
            <div id="social-icons"
                class="flex items-center space-x-5 overflow-hidden transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] origin-left px-6"
                style="max-width: 200px; opacity: 1; transform: translateX(0);">
                <a href="#" class="text-gray-900 hover:text-gray-600 transition"><svg class="w-5 h-5"
                        fill="currentColor" viewBox="0 0 24 24">
                        <title>Instagram</title>
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069-3.204 0-3.584-.012-4.849-.069-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                    </svg></a>
                <a href="#" class="text-gray-900 hover:text-gray-600 transition"><svg class="w-5 h-5"
                        fill="currentColor" viewBox="0 0 24 24">
                        <title>Facebook</title>
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg></a>
                <a href="#" class="text-gray-900 hover:text-gray-600 transition"><svg class="w-5 h-5"
                        fill="currentColor" viewBox="0 0 24 24">
                        <title>X</title>
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg></a>
            </div>
        </div>
    </main>

    <!-- Moving Category Strip -->
    <section
        class="w-full mt-2 md:mt-16 lg:mt-10 border-t border-b border-black py-6 overflow-hidden relative z-0 bg-transparent">
        <div class="flex overflow-hidden w-full select-none">

            @php
                $gems = ['RUBY', 'SAPPHIRE', 'EMERALD', 'DIAMOND', 'TOURMALINE', 'OPAL', 'AMETHYST', 'AQUAMARINE'];
            @endphp

            <!-- Infinite Loop Wrapper -->
            <!-- We duplicate the set enough times to ensure no gaps on wide screens -->
            @for ($i = 0; $i < 4; $i++)
                <div class="animate-marquee-infinite flex items-center shrink-0 whitespace-nowrap will-change-transform">
                    @foreach($gems as $gem)
                        <div class="flex items-center">
                            <a href="{{ route('products.index', ['search' => $gem]) }}"
                                class="group/item cursor-pointer transition-opacity duration-500"
                                style="padding: 0 3rem; display: inline-block;">
                                <span
                                    class="font-gloock text-xl md:text-3xl lg:text-4xl text-black tracking-wide">{{ $gem }}</span>
                            </a>
                            <!-- Minimalist Separator Dot -->
                            <div class="w-1.5 h-1.5 rounded-full bg-black"></div>
                        </div>
                    @endforeach
                </div>
            @endfor

        </div>
    </section>

    <!-- About Section -->
    <section class="relative w-full pt-8 pb-0 md:pt-16 md:pb-16 lg:pt-24 lg:pb-36 bg-transparent overflow-hidden">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Main Content Flex Container -->
            <div class="flex flex-col lg:flex-row items-center lg:items-center justify-between gap-12 lg:gap-20">

                <!-- Left Column: Text Content -->
                <div class="w-full lg:w-3/5 relative z-10 lg:-ml-12">

                    <!-- Main Heading -->
                    <h2 class="about-heading">
                        Nature's Art,<br>
                        <span
                            style="font-style: italic; font-weight: normal; display: inline-block; margin-top: 0.25rem;">Refined.</span>
                    </h2>

                    <!-- Indented Paragraph with Vertical Line -->
                    <div
                        class="mt-8 ml-4 md:ml-0 lg:ml-0 border-l border-black pl-8 lg:pl-12 max-w-xl lg:max-w-2xl about-indented-box">
                        <p
                            class="font-space-mono text-[11px] sm:text-[12px] md:text-[13px] leading-loose tracking-wide text-black text-justify uppercase">
                            Born from the legendary gem lands of Sri Lanka, <span class="font-bold">CAVARi</span> offers
                            ethically sourced, naturally rare gemstones and world-class jewellery crafted to endure
                            time, specializing in exceptional loose gems, bespoke jewellery creations, and personalized
                            gem sourcing for collectors and connoisseurs worldwide—from mine to masterpiece, each piece
                            is a singular expression, hand-selected, expertly crafted, and destined for its rightful
                            owner.
                        </p>
                    </div>

                    <!-- Tagline -->
                    <div class="mt-16 md:mt-20 about-tagline">
                        <p class="font-gloock text-sm md:text-base italic text-black leading-relaxed">
                            NOT MADE FOR EVERYONE.<br>
                            MADE FOR THE ONE IT WAS BORN FOR.
                        </p>
                    </div>

                </div>

                <!-- Right Column: Gemstone Image Placeholder -->
                <div class="w-full lg:w-1/2 flex items-center justify-center lg:justify-end lg:translate-x-16">
                    <div id="about-gem-placeholder"
                        class="relative w-full max-w-lg lg:max-w-2xl h-[400px] md:h-[500px] lg:h-[600px] flex items-center justify-center">
                        <!-- The 3D gem will dynamically move into this container on scroll -->
                    </div>
                </div>

            </div>

        </div>

    </section>

    <!-- Featured Gems (The Darkroom Invert) -->
    <section class="w-full max-w-[95%] mx-auto px-6 pb-24 select-none relative -mt-20 md:-mt-20 lg:mt-0"
        style="padding-top: 0px;">

        <!-- Section Header -->
        <div
            class="flex flex-col lg:flex-row justify-between items-center lg:items-end mb-16 border-b border-black pb-6 text-center lg:text-left">
            <h2 class="font-gloock text-5xl md:text-7xl lg:text-8xl text-black leading-none">
                Fresh Cuts.
            </h2>
            <div class="hidden lg:flex items-center gap-4 pb-2">
                <span class="font-space-mono text-xs uppercase tracking-widest text-black/60">New Arrivals / Vol.
                    4</span>
            </div>
        </div>

        <!-- The Invert Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 border-l border-black">

            @foreach($featuredProducts as $index => $product)
                @php
                    $mainImage = $product->images[0] ?? null;
                    $imageUrl = asset('images/sapphire.png'); // Default fallback

                    if ($mainImage) {
                        if (Str::startsWith($mainImage, ['http', 'https'])) {
                            $imageUrl = $mainImage;
                        } elseif (Str::startsWith($mainImage, ['/storage/', 'storage/'])) {
                            $imageUrl = asset($mainImage);
                        } elseif (Str::startsWith($mainImage, 'images/')) {
                            $imageUrl = asset($mainImage);
                        } else {
                            $imageUrl = asset('storage/' . $mainImage);
                        }
                    }

                    $videoUrl = null;
                    if ($product->video) {
                        if (Str::startsWith($product->video, ['http', 'https'])) {
                            $videoUrl = $product->video;
                        } else {
                            $videoUrl = asset($product->video);
                        }
                    }
                @endphp

                <div class="group relative border-r border-black p-8 md:p-12 min-h-[600px] flex flex-col justify-between transition-colors duration-500 cursor-pointer overflow-hidden group-[.is-active]:bg-white"
                    id="fresh-media-{{ $product->id }}"
                    onclick="window.location.href='{{ route('products.show', $product->slug) }}'">

                    {{-- Optional Video Background (Same logic as grid) --}}
                    @if($videoUrl)
                        <video id="fresh-vid-{{ $product->id }}" src="{{ $videoUrl }}" muted loop playsinline preload="none"
                            disablePictureInPicture oncontextmenu="return false;"
                            class="absolute inset-0 w-full h-full object-cover z-0 opacity-0 group-hover:opacity-100 group-[.is-active]:opacity-100 transition-opacity duration-500"></video>

                    @endif

                    <!-- Header Info -->
                    <div class="relative z-10 flex justify-between items-start">
                        <span
                            class="font-space-mono text-xs font-bold uppercase tracking-widest">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span
                            class="font-space-mono text-[10px] uppercase tracking-widest opacity-60">{{ $product->category->name ?? 'Collection' }}
                            {{ $product->origin ? '/ ' . $product->origin : '' }}</span>
                    </div>

                    <!-- Fixed-Square Image Frame -->
                    <div class="relative z-10 w-full flex-grow flex items-center justify-center py-12 overflow-hidden">
                        <div class="relative flex-shrink-0 transition-opacity duration-500 @if($videoUrl) group-hover:opacity-0 group-[.is-active]:opacity-0 @endif"
                            style="width:320px;height:320px;background:#fff;">
                            <img src="{{ $imageUrl }}" id="fresh-img-{{ $product->id }}" loading="lazy"
                                class="absolute inset-0 w-full h-full object-cover transform scale-125 group-hover:scale-[140%] group-[.is-active]:scale-[140%] transition-transform duration-700 ease-in-out"
                                alt="{{ $product->name }} luxury {{ strtolower($product->category->name ?? 'jewelry') }} by Cavari">
                        </div>
                    </div>

                    <!-- Footer Info -->
                    <div class="relative z-10">
                        <h3
                            class="font-gloock text-3xl mb-2 group-hover:-translate-y-1 group-[.is-active]:-translate-y-1 transition-transform duration-500">
                            {{ $product->name }}
                        </h3>
                        <div class="flex justify-between items-end border-t border-black pt-4 opacity-80">
                            <span class="font-instrument text-sm">
                                @if($product->weight) {{ $product->weight }} ct @elseif($product->size) Size
                                {{ $product->size }} @else Luxury Piece @endif
                            </span>
                            <span
                                class="font-space-mono text-sm font-bold">{{ \App\Helpers\CurrencyHelper::format($product->price) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </section>

    <script>
        (function () {
            const cards = document.querySelectorAll('[id^="fresh-media-"]');

            function setCardState(card, isActive) {
                const id = card.id.replace('fresh-media-', '');
                const video = document.getElementById('fresh-vid-' + id);
                const img = document.getElementById('fresh-img-' + id);

                if (isActive) {
                    card.classList.add('is-active');
                    if (video) {
                        if (img) img.style.opacity = '0';
                        video.style.opacity = '1';
                        if (video.readyState === 0) video.load();
                        video.currentTime = 0;
                        const playPromise = video.play();
                        if (playPromise !== undefined) playPromise.catch(() => { });
                    }
                } else {
                    card.classList.remove('is-active');
                    if (video) {
                        video.pause();
                        video.currentTime = 0;
                        video.style.opacity = '0';
                        if (img) img.style.opacity = '1';
                    }
                }
            }

            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    if (window.innerWidth >= 1024) setCardState(card, true);
                });
                card.addEventListener('mouseleave', () => {
                    if (window.innerWidth >= 1024) setCardState(card, false);
                });
            });

            const observerOptions = {
                root: null,
                rootMargin: '-20% 0px -20% 0px',
                threshold: 0.6
            };

            const observer = new IntersectionObserver((entries) => {
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

            cards.forEach(card => observer.observe(card));

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    cards.forEach(card => card.classList.remove('is-active'));
                }
            });
        })();
    </script>

    <!-- Reviews Section -->
    <section class="relative w-full py-16 lg:py-32 overflow-hidden -mt-24 lg:-mt-[60px]"
        style="background-color: transparent;">
        <div class="max-w-7xl mx-auto px-6 relative"> <!-- Standard container -->

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                <!-- Left Details (Title) -->
                <!-- Spanning 5 columns, large text -->
                <div
                    class="lg:col-span-5 relative z-20 lg:flex lg:items-center lg:-ml-12 xl:-ml-16 2xl:-ml-20 lg:-mt-4 xl:-mt-6">
                    <h2 class="font-gloock text-black tracking-tight reviews-heading"
                        style="font-size: 5.5rem; line-height: 1.1;">
                        Sparkle in<br>
                        Their Words
                    </h2>
                </div>

                <!-- Center Image -->
                <!-- Image overlaps the title, shifted left on desktop -->
                <div class="hidden lg:block lg:col-span-3 relative z-10 reviews-image-container lg:-ml-16 xl:-ml-24">
                    <!-- Rectangular image container with slight radius and balanced height -->
                    <div class="relative overflow-hidden shadow-2xl bg-gray-200 w-full mx-auto border border-black/5"
                        style="max-width: 420px; height: 630px; border-radius: 4px;">
                        <img src="{{ asset('images/reviews-ring.png') }}" loading="lazy"
                            alt="Testimonial of Cavari Luxury Rings" class="w-full h-full object-cover">
                    </div>
                </div>

                @php
                    $fallbackReviews = [
                        [
                            'comment' => 'CAVARI brought my dream engagement ring to life down to the tiniest details. It feels like it was made just for me.',
                            'author_name' => 'Ahmad Faizien',
                            'location' => 'New York'
                        ]
                    ];
                    $sliderReviews = $websiteReviews->isNotEmpty()
                        ? $websiteReviews->map(function ($r) {
                            return [
                                'comment' => $r->comment,
                                'author_name' => $r->author_name,
                                'location' => $r->location ?: 'Verified Customer'
                            ];
                        })->values()
                        : collect($fallbackReviews);
                @endphp

                <!-- Right Details (Quote content) -->
                <div class="lg:col-span-4 pl-8 flex flex-col justify-center relative z-20 reviews-quote-container"
                    x-data="{
                        reviews: {{ Illuminate\Support\Js::from($sliderReviews) }},
                        currentIndex: 0,
                        interval: null,
                        next() {
                            this.currentIndex = (this.currentIndex + 1) % this.reviews.length;
                            this.resetInterval();
                        },
                        prev() {
                            this.currentIndex = (this.currentIndex - 1 + this.reviews.length) % this.reviews.length;
                            this.resetInterval();
                        },
                        resetInterval() {
                            clearInterval(this.interval);
                            if(this.reviews.length > 1) {
                                this.interval = setInterval(() => {
                                    this.currentIndex = (this.currentIndex + 1) % this.reviews.length;
                                }, 10000);
                            }
                        },
                        init() {
                            if(this.reviews.length > 1) {
                                this.interval = setInterval(() => {
                                    this.currentIndex = (this.currentIndex + 1) % this.reviews.length;
                                }, 10000);
                            }
                        }
                     }">

                    <!-- Top Quote Mark -->
                    <div class="-mb-6 -mt-2 relative z-10">
                        <span class="font-gloock text-black" style="font-size: 4rem; line-height: 1;">“</span>
                    </div>

                    <!-- Content -->
                    <div class="relative min-h-[160px]">
                        <template x-for="(review, index) in reviews" :key="index">
                            <div x-show="currentIndex === index"
                                x-transition:enter="transition ease-out duration-700 delay-100"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0"
                                x-transition:leave="transition ease-in duration-300 absolute inset-0"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-x-4"
                                class="w-full">
                                <p class="font-instrument text-gray-900 leading-relaxed mb-8 break-words text-lg md:text-xl text-justify mx-auto max-w-[85%] lg:max-w-none"
                                    x-text="review.comment"></p>
                            </div>
                        </template>

                        <!-- Bottom Quote Mark -->
                        <div class="absolute" style="bottom: -2.25rem; right: 1.5rem; pointer-events: none;">
                            <span class="font-gloock text-black" style="font-size: 4rem; line-height: 1;">”</span>
                        </div>
                    </div>

                    <!-- Author -->
                    <div class="mt-4 mb-4 lg:mb-10 relative min-h-[60px]">
                        <template x-for="(review, index) in reviews" :key="index">
                            <div x-show="currentIndex === index"
                                x-transition:enter="transition ease-out duration-700 delay-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-300 absolute inset-0"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="w-full">
                                <h4 class="font-gloock text-xl text-black" x-text="review.author_name"></h4>
                                <p class="font-space-mono text-xs text-gray-500 uppercase tracking-widest mt-1"
                                    x-text="review.location"></p>
                            </div>
                        </template>
                    </div>

                    <!-- Navigation -->
                    <div class="flex flex-col items-end gap-2 pr-4 relative z-30">
                        <a href="{{ route('reviews.public') }}"
                            class="font-space-mono text-xs font-bold uppercase tracking-widest border-b border-black pb-0.5 hover:text-gray-600 transition mb-2">
                            View All
                        </a>

                        <div class="flex gap-4">
                            <!-- Left Button -->
                            <button @click="prev()"
                                class="rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition"
                                style="width: 3.5rem; height: 3.5rem;" :disabled="reviews.length <= 1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7 7-7 M3 12h18" />
                                </svg>
                            </button>
                            <!-- Right Button -->
                            <button @click="next()"
                                class="rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition"
                                style="width: 3.5rem; height: 3.5rem;" :disabled="reviews.length <= 1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7-7 7 M21 12H3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- The Atelier (New Jewelry Section - Horizontal Scroll) -->
    <!-- h-screen ensures a fixed reference frame for pinning -->
    <section id="atelier-section"
        class="w-full bg-transparent py-0 overflow-hidden relative flex flex-col justify-center"
        style="margin-top: -70px;">
        <div class="max-w-[95%] mx-auto px-6 w-full h-full flex flex-col justify-center">

            <!-- Header -->
            <div
                class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12 border-b border-black/10 pb-6 shrink-0 pt-24">
                <div>
                    <span
                        class="font-space-mono text-xs md:text-base lg:text-xs font-bold tracking-[0.2em] uppercase text-gray-500 mb-2 block">Bespoke
                        Creations</span>
                    <h2 class="font-gloock text-5xl md:text-7xl lg:text-8xl text-black leading-none">
                        The Atelier
                    </h2>
                </div>
                <div class="hidden lg:block">
                    <p class="font-instrument text-gray-600 max-w-sm text-right leading-relaxed text-sm">
                        Where imagination meets mastery. Explore our exclusive collection of hand-forged treasures.
                    </p>
                </div>
            </div>

            <!-- Horizontal Scroll Container (Window) -->
            <div class="w-full overflow-hidden grow flex items-center">
                <!-- Moving Track -->
                <div id="atelier-track" class="flex space-x-8 w-max pl-4 pr-24 lg:pr-48 items-start">


                    @forelse($atelierProducts as $ap)
                        @php
                            $imageUrl = '/images/placeholder.png';
                            if ($ap->images && count($ap->images) > 0) {
                                $firstImg = $ap->images[0];
                                if (\Illuminate\Support\Str::startsWith($firstImg, ['http', 'https'])) {
                                    $imageUrl = $firstImg;
                                } else {
                                    $imageUrl = asset($firstImg);
                                }
                            }
                        @endphp
                        <!-- Atelier Product -->
                        <div class="w-[300px] md:w-[360px] lg:w-[400px] shrink-0 group cursor-pointer"
                            onclick="window.location='{{ route('products.show', $ap->slug) }}'">
                            <div class="relative aspect-[3/4] overflow-hidden bg-white rounded-sm border border-black/5">
                                <img src="{{ $imageUrl }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    alt="{{ $ap->name }}">
                                <!-- Hover Overlay -->
                                <div
                                    class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-500">
                                </div>
                            </div>
                            <div class="mt-6 flex justify-between items-start">
                                <div>
                                    <h3 class="font-gloock text-xl text-black mb-1">{{ $ap->name }}</h3>
                                    <p class="font-space-mono text-xs text-gray-500 uppercase tracking-widest">
                                        {{ $ap->category->name ?? 'Collection' }}
                                    </p>
                                </div>
                                <span
                                    class="font-instrument text-black text-lg">{{ \App\Helpers\CurrencyHelper::format($ap->price) }}</span>
                            </div>

                        </div>
                    @empty
                        <!-- Fallback if empty -->
                        <div class="flex flex-col items-center justify-center w-full py-20 text-center">
                            <p class="font-space-mono text-xs text-gray-400 uppercase tracking-widest">More creations coming
                                soon to the atelier</p>
                        </div>
                    @endforelse

                </div>
            </div>

            <!-- View All Link -->
            <div class="flex justify-center mt-12 shrink-0 pb-12">
                <a href="{{ route('products.index') }}"
                    class="group flex items-center gap-4 text-black hover:text-gray-600 transition-colors">
                    <span class="font-space-mono text-xs font-bold tracking-[0.2em] uppercase">View Full
                        Collection</span>
                    <span class="w-8 h-[1px] bg-black group-hover:w-16 transition-all duration-300"></span>
                </a>
            </div>

        </div>
    </section>

    <!-- The Concierge (Avant-Garde Services) -->
    <section class="w-full py-24 lg:py-32 bg-transparent relative z-10 mt-28 lg:mt-[150px]">
        <div class="max-w-[95%] mx-auto px-6">
            <h2
                class="font-gloock text-[12vw] leading-[0.8] text-black/5 mb-6 lg:mb-12 select-none pointer-events-none absolute -top-12 left-0 w-full text-center mix-blend-overlay">
                CONCIERGE
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 pt-0 lg:pt-12 relative">
                <div class="lg:col-span-4 lg:self-end -mt-14 lg:mt-0 mb-6 lg:mb-0">
                    <p class="font-instrument text-lg text-black leading-relaxed max-w-sm">
                        We offer more than just jewelry; we offer a gateway to the extraordinary. Service as rare as our
                        gems.
                    </p>
                </div>

                <div class="lg:col-span-8 flex flex-col -mt-10 lg:mt-0">
                    <!-- Service 01 -->
                    <a href="{{ route('shop.custom-design') }}"
                        class="group border-b border-black pt-8 pb-0 lg:py-12 flex flex-col lg:flex-row lg:items-center justify-between cursor-pointer hover:pl-8 transition-all duration-500">
                        <div class="flex items-baseline gap-6 lg:gap-12">
                            <span class="font-space-mono text-xs font-bold text-black/40">01</span>
                            <h3 class="font-gloock text-3xl md:text-4xl lg:text-5xl text-black">Bespoke Commissions</h3>
                        </div>
                        <div
                            class="mt-4 lg:mt-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center gap-4">
                            <span class="font-space-mono text-xs uppercase tracking-widest hidden lg:block">Start
                                Design</span>
                            <svg class="w-8 h-8 rotate-[-45deg] group-hover:rotate-0 transition-transform duration-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </a>

                    <!-- Service 02 -->
                    <a href="{{ route('shop.custom-design') }}"
                        class="group border-b border-black pt-8 pb-0 lg:py-12 flex flex-col lg:flex-row lg:items-center justify-between cursor-pointer hover:pl-8 transition-all duration-500">
                        <div class="flex items-baseline gap-6 lg:gap-12">
                            <span class="font-space-mono text-xs font-bold text-black/40">02</span>
                            <h3 class="font-gloock text-3xl md:text-4xl lg:text-5xl text-black">Global Sourcing</h3>
                        </div>
                        <div
                            class="mt-4 lg:mt-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center gap-4">
                            <span
                                class="font-space-mono text-xs uppercase tracking-widest hidden lg:block">Inquire</span>
                            <svg class="w-8 h-8 rotate-[-45deg] group-hover:rotate-0 transition-transform duration-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </a>

                    <!-- Service 03 -->
                    <div
                        class="group border-b border-black pt-8 pb-0 lg:py-12 flex flex-col lg:flex-row lg:items-center justify-between cursor-pointer hover:pl-8 transition-all duration-500">
                        <div class="flex items-baseline gap-6 lg:gap-12">
                            <span class="font-space-mono text-xs font-bold text-black/40">03</span>
                            <h3 class="font-gloock text-3xl md:text-4xl lg:text-5xl text-black">Private Viewing</h3>
                        </div>
                        <div
                            class="mt-4 lg:mt-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center gap-4">
                            <span class="font-space-mono text-xs uppercase tracking-widest hidden lg:block">Book
                                Now</span>
                            <svg class="w-8 h-8 rotate-[-45deg] group-hover:rotate-0 transition-transform duration-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- The Journey (Editorial Overlap) -->
    <section class="w-full py-24 lg:py-32 -mt-24 lg:-mt-20 bg-transparent relative overflow-hidden">
        <div class="max-w-[95%] mx-auto px-6 relative">
            <div class="flex flex-col lg:flex-row items-center">

                <!-- Large Text Area -->
                <div class="w-full lg:w-7/12 relative z-20">
                    <span
                        class="font-space-mono text-xs font-bold tracking-[0.2em] uppercase text-black mb-6 lg:mb-8 block bg-white/0 inline-block">Since
                        1982</span>
                    <h2 class="font-gloock text-5xl md:text-7xl lg:text-8xl text-black leading-[0.9] mb-10 lg:mb-12">
                        Unearthing<br>
                        <span class="italic ml-12 lg:ml-24 block">The Eternal.</span>
                    </h2>
                    <div
                        class="max-w-md ml-0 lg:ml-24 font-instrument text-lg text-black/80 leading-relaxed bg-white/30 backdrop-blur-sm p-6 lg:p-0 rounded-lg lg:bg-transparent lg:backdrop-blur-0">
                        <p class="mb-6">
                            Deep in the mist-laden mountains of Sri Lanka, our story begins. Not in a boardroom, but in
                            the hands of artisans whose lineage stretches back centuries.
                        </p>
                        <a href="#"
                            class="group inline-flex items-center gap-2 border-b border-black pb-1 hover:pb-2 transition-all">
                            <span class="font-space-mono text-xs font-bold uppercase tracking-widest">Read The
                                Story</span>
                        </a>
                    </div>
                </div>

                <!-- Floating Image -->
                <div class="w-full lg:w-5/12 mt-12 lg:mt-0 relative z-10 lg:-ml-24">
                    <div
                        class="relative overflow-hidden grayscale hover:grayscale-0 transition-all duration-1000 shadow-2xl h-[400px] lg:h-[600px]">
                        <img src="{{ asset('images/journey-craft.png') }}" loading="lazy"
                            class="w-full h-full object-cover transform scale-110 hover:scale-100 transition-transform duration-[2s]"
                            alt="Artisan Jeweler Hands Crafting Cavari Jewelry">
                    </div>
                    <!-- Decorative Element -->
                    <div
                        class="absolute -bottom-6 -right-6 w-full h-full border border-black/20 pointer-events-none -z-10 hidden lg:block">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <x-footer />

    <script>
        // Wait for EVERYTHING to load (including images) before initializing ScrollTrigger calculations
        window.addEventListener('load', () => {

            // Social Toggle Logic
            const toggleBtn = document.getElementById('social-toggle');
            const iconsContainer = document.getElementById('social-icons');
            const chevron = document.getElementById('social-chevron');
            let isOpen = true;

            toggleBtn?.addEventListener('click', () => {
                isOpen = !isOpen;
                if (!isOpen) {
                    iconsContainer.style.maxWidth = '0px';
                    iconsContainer.style.opacity = '0';
                    iconsContainer.style.paddingLeft = '0px';
                    iconsContainer.style.transform = 'translateX(-20px)';
                    chevron.style.transform = 'rotate(180deg)';
                } else {
                    iconsContainer.style.maxWidth = '200px';
                    iconsContainer.style.opacity = '1';
                    iconsContainer.style.paddingLeft = '1.5rem';
                    iconsContainer.style.transform = 'translateX(0)';
                    chevron.style.transform = 'rotate(0deg)';
                }
            });

            // GSAP 'Horizontal Scroll' Effect ONLY
            const initScrollEffects = () => {
                if (window.gsap && window.ScrollTrigger) {
                    gsap.registerPlugin(ScrollTrigger);

                    // --- 0. Smooth Scroll (Lenis) Initialization ---
                    const isTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
                    let lenis = null;

                    if (!isTouch) {
                        lenis = new Lenis({
                            duration: 1.2,
                            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                            smoothWheel: true
                        });

                        function raf(time) {
                            lenis.raf(time);
                            requestAnimationFrame(raf);
                        }
                        requestAnimationFrame(raf);

                        // Sync ScrollTrigger with Lenis
                        lenis.on('scroll', ScrollTrigger.update);
                        gsap.ticker.add((time) => {
                            lenis.raf(time * 1000);
                        });
                        gsap.ticker.lagSmoothing(0);
                    }

                    // 3. The Atelier Horizontal Scroll (Pin & Translate)
                    const atelierTrack = document.getElementById('atelier-track');
                    const atelierSection = document.getElementById('atelier-section');

                    if (atelierTrack && atelierSection) {

                        function getScrollAmount() {
                            let trackWidth = atelierTrack.scrollWidth;
                            // Translation distance = total track width - viewable width
                            return -(trackWidth - window.innerWidth);
                        }


                        const tween = gsap.to(atelierTrack, {
                            x: getScrollAmount,
                            ease: "none",
                        });

                        ScrollTrigger.create({
                            trigger: atelierSection,
                            start: "center 40%", // Center of section hits 40% viewport height (slightly lower than center)
                            end: () => `+=${Math.abs(getScrollAmount())}`, // Helper for exact scroll length
                            pin: true,
                            animation: tween,
                            scrub: 1,
                            invalidateOnRefresh: true, // Recalculate on window resize
                            anticipatePin: 1
                        });
                    }

                    // FORCE refresh to update start/end positions after load
                    setTimeout(() => ScrollTrigger.refresh(), 100);

                    // --- 4. 3D Gem Scrolling Transfer (Single Gem Animation) ---
                    const gem3d = document.getElementById('main-3d-gem');
                    const heroContainer = document.getElementById('hero-gem-container');
                    const aboutPlaceholder = document.getElementById('about-gem-placeholder');

                    if (gem3d && heroContainer && aboutPlaceholder) {

                        let transferTween = null;

                        function createGemTransferAnimation() {
                            if (transferTween) transferTween.kill();

                            // Temporarily remove transforms to calculate absolute bounds accurately
                            gsap.set(gem3d, { clearProps: "all" });

                            let heroRect = heroContainer.getBoundingClientRect();
                            let aboutRect = aboutPlaceholder.getBoundingClientRect();
                            let gemRect = gem3d.getBoundingClientRect();

                            // Center-to-center mathematical difference
                            let endX = (aboutRect.left + aboutRect.width / 2) - (heroRect.left + heroRect.width / 2);
                            let endY = (aboutRect.top + aboutRect.height / 2) - (heroRect.top + heroRect.height / 2);

                            // Mobile/Tablet Adjustment: Move the gem a bit higher when it lands in the About section
                            if (window.innerWidth < 1024) {
                                endY -= 60;
                            }

                            let targetSize = Math.min(aboutRect.width, aboutRect.height) * 1.35; // Matches the massive Hero size
                            let scale = targetSize / gemRect.width;

                            transferTween = gsap.to(gem3d, {
                                x: endX,
                                y: endY,
                                scale: scale,
                                ease: "none", // Perfectly steady speed
                                paused: true
                            });

                            ScrollTrigger.create({
                                trigger: heroContainer,
                                start: "top top",
                                endTrigger: aboutPlaceholder,
                                end: "center center",
                                scrub: 2.5, // The slowest, most luxurious glide
                                animation: transferTween,
                                onUpdate: (self) => {
                                    const p = self.progress;
                                    
                                    // 1. Handle Auto-Rotate
                                    const isTransitioning = p > 0.01 && p < 0.99;
                                    if (isTransitioning) {
                                        if (gem3d.autoRotate) gem3d.autoRotate = false;
                                    } else {
                                        if (!gem3d.autoRotate) gem3d.autoRotate = true;
                                    }

                                    // 2. Internal Camera Rotation
                                    let theta = gsap.utils.interpolate(0, 42, p);
                                    let phi = gsap.utils.interpolate(85, 52, p);
                                    gem3d.cameraOrbit = `${theta}deg ${phi}deg auto`;
                                }
                            });
                        }

                        // Short delay ensures layout is fully settled before calculating bounds
                        setTimeout(createGemTransferAnimation, 300);

                        let lastWidth = window.innerWidth;
                        window.addEventListener('resize', () => {
                            // Only refresh if the width changes (prevents address-bar jumps)
                            if (window.innerWidth !== lastWidth) {
                                lastWidth = window.innerWidth;
                                clearTimeout(resizeTimer);
                                resizeTimer = setTimeout(() => {
                                    ScrollTrigger.getAll().forEach(t => t.kill());
                                    createGemTransferAnimation();
                                    initScrollEffects();
                                }, 300);
                            }
                        });
                    }

                } else {
                    // Retry if GSAP isn't loaded yet
                    setTimeout(initScrollEffects, 50);
                }
            };

            initScrollEffects();
        });
    </script>
</body>

</html>