<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>The Heritage | {{ config('app.name', 'Cavari') }}</title>

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
    </style>
</head>

<body class="font-instrument antialiased text-gray-900 bg-hero-gradient">

    <x-navbar />

    <!-- Background Orb for Glass Effect -->
    <div
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-purple-200/20 rounded-full blur-[100px] pointer-events-none -z-10">
    </div>

    <!-- Main Content Layout -->
    <div class="relative w-full max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-12">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">

            <!-- Left: Sticky Image -->
            <div class="hidden lg:block lg:col-span-5 sticky top-32">
                <div class="relative aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('images/journey-craft.png') }}"
                        class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-700"
                        alt="Craftsmanship">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="absolute bottom-8 left-8 text-white">
                        <p class="font-space-mono text-xs uppercase tracking-widest opacity-80 mb-2">The Atelier</p>
                        <h3 class="font-gloock text-3xl">Ratnapura,<br>Sri Lanka</h3>
                    </div>
                </div>
                <!-- Decorative Element underneath -->
                <div class="absolute -bottom-6 -right-6 w-full h-full border border-black/5 rounded-2xl -z-10"></div>
            </div>

            <!-- Right: Scrolling Glass Narrative -->
            <div class="col-span-1 lg:col-span-7 space-y-12">

                <!-- Header -->
                <div class="mb-16">
                    <span class="font-space-mono text-xs font-bold uppercase tracking-[0.3em] text-gray-500 mb-4 block">
                        Our History
                    </span>
                    <h1 class="font-gloock text-6xl md:text-8xl text-black leading-none mb-6">
                        Beyond the<br> <span class="italic text-gray-600">Facet.</span>
                    </h1>
                </div>

                <!-- Glass Card 1: Origin -->
                <div class="p-8 md:p-12 mb-8"
                    style="background: rgba(255, 255, 255, 0.35); border-radius: 16px; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.23);">
                    <h3 class="font-gloock text-3xl mb-6">The Origin</h3>
                    <p class="font-instrument text-lg text-gray-800 leading-relaxed text-justify mb-6">
                        Cavari began not as a brand, but as a pursuit of the extraordinary in the gem-rich soils of Sri
                        Lanka.
                        Known historically as the "City of Gems," Ratnapura is where our family began unearthing
                        treasures that had lain dormant for millions of years.
                    </p>
                    <p class="font-instrument text-lg text-gray-800 leading-relaxed text-justify">
                        We don't simply source stones; we curate history. Every gem is selected for its character, its
                        fire, and its untold story.
                    </p>
                </div>

                <!-- Glass Card 2: Philosophy -->
                <div class="p-8 md:p-12"
                    style="background: rgba(255, 255, 255, 0.35); border-radius: 16px; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.23);">
                    <h3 class="font-gloock text-3xl mb-8">Our Code</h3>

                    <ul class="space-y-8">
                        <li class="flex gap-6 items-start group">
                            <span
                                class="font-space-mono text-xl text-gray-300 font-bold group-hover:text-black transition-colors">01</span>
                            <div>
                                <h4 class="font-instrument font-bold text-lg mb-2">Ethical Sourcing</h4>
                                <p class="font-instrument text-gray-600 text-sm leading-relaxed">
                                    Direct relationships with miners. Fair wages. Complete traceability from earth to
                                    adornment.
                                </p>
                            </div>
                        </li>
                        <li class="flex gap-6 items-start group">
                            <span
                                class="font-space-mono text-xl text-gray-300 font-bold group-hover:text-black transition-colors">02</span>
                            <div>
                                <h4 class="font-instrument font-bold text-lg mb-2">Uncompromising Quality</h4>
                                <p class="font-instrument text-gray-600 text-sm leading-relaxed">
                                    We reject the mediocre. Only the top 1% of gemstones characterize the Cavari
                                    collection.
                                </p>
                            </div>
                        </li>
                        <li class="flex gap-6 items-start group">
                            <span
                                class="font-space-mono text-xl text-gray-300 font-bold group-hover:text-black transition-colors">03</span>
                            <div>
                                <h4 class="font-instrument font-bold text-lg mb-2">Bespoke Artistry</h4>
                                <p class="font-instrument text-gray-600 text-sm leading-relaxed">
                                    Handcrafted settings designed to cradle the specific contours of each unique gem.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </div>



    <!-- The Founder / Artisan Focus -->
    <section class="w-full py-32 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 text-center">

            <div class="inline-block relative mb-12">
                <!-- Using logo as a watermark symbol if no founder image provided -->
                <img src="{{ asset('images/cavarilogo.png') }}" alt="Cavari" class="h-24 w-auto opacity-20 mx-auto">
            </div>

            <h2 class="font-gloock text-4xl md:text-6xl text-black mb-8">
                "We don't just sell jewelry.<br>
                We curate heirlooms."
            </h2>

            <p class="font-space-mono text-xs uppercase tracking-widest text-gray-500">
                — The Cavari Family
            </p>

            <div class="mt-8">
                <a href="{{ route('products.index') }}"
                    class="group inline-flex items-center gap-4 px-8 py-4 bg-black text-white rounded-full hover:bg-gray-800 transition">
                    <span class="font-space-mono text-xs font-bold uppercase tracking-widest">Explore The
                        Collection</span>
                    <svg class="w-4 h-4 transform group-hover:rotate-45 transition duration-500" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

        </div>
    </section>

    <x-footer />

</body>

</html>