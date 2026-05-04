<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="google-site-verification" content="0gfxPnkgGArjdF7fCzg1fkUZ4VvT6ZD7HO5M5VW1ryU" />
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">
    <title>@yield('title', 'Cavari | Luxury Gems & Bespoke Jewelry')</title>
    <meta name="description" content="@yield('meta_description', 'Cavari offers exquisite 18k gold diamond rings, handcrafted gems, and luxury jewelry. Discover rare treasures perfect for engagements and collectors.')">
    <meta name="keywords" content="Cavari, Cavari Gems, Luxury Jewelry, Sri Lanka Gems, Bespoke Jewelry, Diamond Rings, Rare Gemstones">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name', 'Cavari'))">
    <meta property="og:description" content="@yield('meta_description', 'Discover exquisite 18k gold diamond rings at Cavari. Handcrafted gems and luxury jewelry perfect for engagements and special occasions.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.png'))">
    <meta property="og:site_name" content="Cavari">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name', 'Cavari'))">
    <meta property="twitter:description" content="@yield('meta_description', 'Discover exquisite 18k gold diamond rings at Cavari. Handcrafted gems and luxury jewelry perfect for engagements and special occasions.')">
    <meta property="twitter:image" content="@yield('og_image', asset('images/og-image.png'))">

    @yield('meta_tags')

    <!-- Organization Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Cavari",
      "alternateName": "Cavari Gems",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/cavarilogo.png') }}",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+94-77-123-4567",
        "contactType": "customer service",
        "areaServed": "Worldwide",
        "availableLanguage": ["English", "Sinhala"]
      },
      "description": "Cavari is a luxury jewelry brand specializing in ethically sourced rare gemstones and bespoke handcrafted 18k gold jewelry from Sri Lanka.",
      "sameAs": [
        "https://www.instagram.com/cavari",
        "https://www.facebook.com/cavari",
        "https://www.pinterest.com/cavari"
      ]
    }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 overflow-x-hidden">
    <div class="min-h-screen flex flex-col pt-24">
        <x-navbar />

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
        <x-footer />
        <x-gift-card-popup />
    </div>
</body>
</html>
