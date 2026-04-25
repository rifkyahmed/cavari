<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Legal & Privacy | {{ config('app.name', 'Cavari') }}</title>

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

        .legal-content h3 {
            font-family: 'Gloock', serif;
            font-size: 1.5rem;
            color: black;
            margin-top: 3rem;
            margin-bottom: 1.5rem;
        }

        .legal-content p, .legal-content li {
            font-family: 'Instrument Sans', sans-serif;
            font-size: 0.95rem;
            color: #4b5563; /* gray-600 */
            line-height: 1.8;
            margin-bottom: 1.25rem;
            text-align: justify;
        }
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient flex flex-col min-h-screen">

    <x-navbar />

    <main class="flex-grow pt-48 pb-24 px-6 md:px-12 w-full max-w-4xl mx-auto">
        
        <div class="mb-20">
            <span class="font-space-mono text-[10px] font-bold uppercase tracking-[0.4em] text-gray-400 mb-4 block">
                The Foundation
            </span>
            <h1 class="font-gloock text-5xl md:text-7xl text-black leading-none">
                Legal & <span class="italic text-gray-400">Privacy.</span>
            </h1>
            <p class="font-space-mono text-[10px] uppercase tracking-widest text-gray-400 mt-8">
                Last updated: April 09, 2026
            </p>
        </div>

        <div class="legal-content">
            
            <section id="terms">
                <h3>01. Terms of Service</h3>
                <p>
                    By accessing and using the Cavari website, you agree to be bound by these terms and conditions. Our website is a platform for the display and sale of authentic gemstones and bespoke jewelry. All descriptions, pricing, and availability are subject to change without prior notice.
                </p>
                <p>
                    All content on this website, including but not limited to designs, photography, and text, is the exclusive intellectual property of Cavari and is protected by international copyright laws.
                </p>
            </section>

            <section id="privacy">
                <h3>02. Privacy Policy</h3>
                <p>
                    At Cavari, your privacy is as guarded as our rarest artifacts. We collect only the information necessary to provide you with a bespoke experience and to fulfill your orders securely.
                </p>
                <ul class="list-disc pl-5 space-y-2 mb-6">
                    <li>Personal Identification: Name, Email, WhatsApp number.</li>
                    <li>Transaction Data: Payment processing through secure, encrypted gateways.</li>
                    <li>Experience Optimization: Minimal usage of cookies to remember your preferences and cart contents.</li>
                </ul>
                <p>
                    We never sell, rent, or trade your personal data to third parties for marketing purposes. Your information is shared only with logistics partners (DHL, FedEx) and payment processors required to complete your transaction.
                </p>
            </section>

            <section id="ethical">
                <h3>03. Ethical Standards</h3>
                <p>
                    We are committed to the highest ethical mounting standards. Every gemstone sourced by Cavari is vetted for its origin, ensuring it is conflict-free and sourced through fair-labor practices in compliance with international regulations.
                </p>
            </section>

            <section id="disclaimer" class="mt-16 pt-12 border-t border-black/5">
                <p class="text-[11px] font-space-mono uppercase tracking-widest text-gray-400 leading-relaxed italic">
                    For further inquiries regarding our legal framework or data handling practices, please contact our legal council at <a href="mailto:legal@cavari.com" class="text-black underline">legal@cavari.com</a>.
                </p>
            </section>

        </div>
    </main>

    <x-footer />
</body>
</html>
