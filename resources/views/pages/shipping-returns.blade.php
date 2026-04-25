<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shipping & Returns | {{ config('app.name', 'Cavari') }}</title>

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
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient flex flex-col min-h-screen">

    <x-navbar />

    <main class="flex-grow pt-48 pb-24 px-6 md:px-12 w-full max-w-5xl mx-auto">
        
        <div class="mb-20 text-center">
            <span class="font-space-mono text-xs font-bold uppercase tracking-[0.3em] text-gray-400 mb-4 block">
                Logistics & Care
            </span>
            <h1 class="font-gloock text-5xl md:text-7xl text-black mb-6 leading-none">
                Shipping & <br> <span class="italic text-gray-500">Returns.</span>
            </h1>
        </div>

        <div class="space-y-16">
            
            <!-- Section 1: Shipping -->
            <section>
                <div class="flex items-center gap-4 mb-8">
                    <span class="font-space-mono text-xs font-bold text-black border-b border-black pb-1">01</span>
                    <h2 class="font-gloock text-3xl">Global Delivery</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 font-instrument text-gray-600 leading-relaxed">
                    <div>
                        <h4 class="font-bold text-black mb-4 uppercase text-[10px] tracking-widest font-space-mono">Bespoke Handling</h4>
                        <p class="mb-6 text-justify">
                            Every Cavari piece is an artifact of significant value. We partner exclusively with premium secure couriers (DHL Express, FedEx) to ensure that your acquisition is tracked and insured from the moment it leaves our atelier in Sri Lanka until it reaches your door.
                        </p>
                        <p class="text-justify">
                            All shipments are fully insured and require a signature upon delivery. We do not ship to P.O. boxes or freight forwarding addresses.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-bold text-black mb-4 uppercase text-[10px] tracking-widest font-space-mono">Timelines & Duty</h4>
                        <ul class="space-y-4">
                            <li class="flex justify-between border-b border-black/5 pb-2">
                                <span>Loose Gemstones</span>
                                <span class="text-black font-medium">3-5 Business Days</span>
                            </li>
                            <li class="flex justify-between border-b border-black/5 pb-2">
                                <span>In-Stock Jewelry</span>
                                <span class="text-black font-medium">5-7 Business Days</span>
                            </li>
                            <li class="flex justify-between border-b border-black/5 pb-2">
                                <span>Bespoke / Custom</span>
                                <span class="text-black font-medium">4-8 Weeks</span>
                            </li>
                        </ul>
                        <p class="mt-6 text-[11px] italic">
                            * Please note that international orders may be subject to local customs duties and taxes, which are the responsibility of the recipient.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Section 2: Returns -->
            <section>
                <div class="flex items-center gap-4 mb-8">
                    <span class="font-space-mono text-xs font-bold text-black border-b border-black pb-1">02</span>
                    <h2 class="font-gloock text-3xl">Our Return Policy</h2>
                </div>
                <div class="p-8 bg-white/40 backdrop-blur-sm border border-black/5 rounded-sm">
                    <p class="font-instrument text-lg text-black mb-8 leading-relaxed italic">
                        "Luxury is found in the detail, and our satisfaction guarantee reflects our commitment to excellence."
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 font-instrument text-gray-600 leading-relaxed text-sm">
                        <div class="space-y-4">
                            <h4 class="font-bold text-black uppercase text-[10px] tracking-widest font-space-mono italic underline decoration-1 underline-offset-4">Eligible Items</h4>
                            <p>
                                Standard collection items and loose gemstones may be returned or exchanged within **7 days** of delivery, provided they are in their original, unworn condition with all security tags and certifications intact.
                            </p>
                        </div>
                        <div class="space-y-4">
                            <h4 class="font-bold text-black uppercase text-[10px] tracking-widest font-space-mono italic underline decoration-1 underline-offset-4">Non-Returnable</h4>
                            <p>
                                Due to their unique nature, **Bespoke creations, Custom-designed jewelry, and Engraved pieces** are considered final sale and cannot be returned or exchanged.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 3: Process -->
            <section class="border-t border-black/10 pt-16">
                <h3 class="font-gloock text-2xl mb-8">Initiating a Return</h3>
                <ol class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <li class="space-y-3">
                        <span class="font-space-mono text-[10px] font-bold text-gray-300 block">Step 1</span>
                        <p class="text-sm text-gray-600">Contact our concierge at <a href="mailto:concierge@cavari.com" class="text-black underline">concierge@cavari.com</a> to request a Return Authorization Number.</p>
                    </li>
                    <li class="space-y-3">
                        <span class="font-space-mono text-[10px] font-bold text-gray-300 block">Step 2</span>
                        <p class="text-sm text-gray-600">Securely pack the item with all original packaging, certificates, and invoices.</p>
                    </li>
                    <li class="space-y-3">
                        <span class="font-space-mono text-[10px] font-bold text-gray-300 block">Step 3</span>
                        <p class="text-sm text-gray-600">Ship via an insured, trackable service according to the instructions provided by our team.</p>
                    </li>
                </ol>
            </section>

        </div>
    </main>

    <x-footer />
</body>
</html>
