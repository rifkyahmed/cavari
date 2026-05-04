<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>Contact Us | Cavari Luxury Gems & Jewelry</title>
    <meta name="description" content="Get in touch with Cavari. Start a conversation about our rare gemstones, bespoke jewelry creations, or visit our atelier in Ratnapura, Sri Lanka.">
    <meta name="keywords" content="Cavari Contact, Bespoke Jewelry Inquiry, Gemstone Concierge, Cavari Sri Lanka, Jewelry Support">

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
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient flex flex-col min-h-screen">

    <x-navbar />

    <!-- Centered Glass Portal Layout -->
    <main class="flex-grow flex items-center justify-center p-6 pt-40 pb-24 relative overflow-hidden">
        
        <!-- The Glass Portal Container -->
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-5 relative z-10"
             style="background: rgba(255, 255, 255, 0.35); border-radius: 24px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.05); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.23); overflow: hidden;">
            
            <!-- Left Panel: Info & Context (Slightly Darker Context) -->
            <div class="lg:col-span-2 bg-black/[0.03] p-10 md:p-14 flex flex-col justify-between relative group">
                
                <!-- Content -->
                <div class="relative z-10">
                    <span class="font-space-mono text-xs font-bold uppercase tracking-[0.3em] text-black/40 mb-8 block">
                        Touch Base
                    </span>
                    <h2 class="font-gloock text-5xl text-black mb-8 leading-none">
                        Start a<br>Conversation.
                    </h2>
                    
                    <div class="space-y-8 font-instrument text-gray-600 mt-12">
                        <div>
                            <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black block mb-2">Concierge</span>
                            <a href="mailto:concierge@cavari.com" class="text-lg hover:text-black transition-colors block">concierge@cavari.com</a>
                        </div>
                        <div>
                            <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black block mb-2">The Atelier</span>
                            <p class="text-lg leading-relaxed">
                                145 Main Street<br>Ratnapura, Sri Lanka
                            </p>
                        </div>
                        <div class="pt-8 flex gap-6">
                             <!-- Social Icons -->
                             <a href="#" class="text-gray-400 hover:text-black transition-colors">
                                 <span class="sr-only">Instagram</span>
                                 <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.76-6.162 6.162s2.76 6.163 6.162 6.163 6.162-2.76 6.162-6.163c0-3.403-2.76-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                             </a>
                             <a href="#" class="text-gray-400 hover:text-black transition-colors">
                                 <span class="sr-only">Twitter</span>
                                 <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                             </a>
                             <a href="#" class="text-gray-400 hover:text-black transition-colors">
                                 <span class="sr-only">LinkedIn</span>
                                 <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
                             </a>
                        </div>
                    </div>
                </div>

                <!-- Floating Gem (Visual Interest) -->
                <img src="{{ asset('images/cavarigem.png') }}" class="absolute -bottom-12 -right-12 w-64 h-64 object-contain mix-blend-multiply transition-transform duration-1000 group-hover:scale-110 group-hover:rotate-12" alt="Emerald">
            </div>

            <!-- Right Panel: The Form -->
            <div class="lg:col-span-3 p-10 md:p-14 bg-white/20">
                @if(session('success'))
                    <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-sm font-instrument">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- First Name -->
                        <div class="relative group">
                            <input type="text" id="first_name" name="first_name" required placeholder=" "
                                   class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-all pb-2 px-0">
                            <label for="first_name" class="absolute left-0 bottom-2.5 font-space-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 origin-left pointer-events-none">
                                First Name
                            </label>
                            <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full"></div>
                        </div>

                        <!-- Last Name -->
                        <div class="relative group">
                            <input type="text" id="last_name" name="last_name" required placeholder=" "
                                   class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-all pb-2 px-0">
                            <label for="last_name" class="absolute left-0 bottom-2.5 font-space-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 origin-left pointer-events-none">
                                Last Name
                            </label>
                            <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full"></div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="relative group pt-4">
                        <input type="email" id="email" name="email" required placeholder=" "
                               class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-all pb-2 px-0">
                        <label for="email" class="absolute left-0 bottom-2.5 font-space-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 origin-left pointer-events-none">
                            Email Address
                        </label>
                        <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full"></div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="relative group pt-4">
                        <input type="tel" id="whatsapp" name="phone" placeholder=" "
                               class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-all pb-2 px-0">
                        <label for="whatsapp" class="absolute left-0 bottom-2.5 font-space-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 origin-left pointer-events-none">
                            WhatsApp Number
                        </label>
                        <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full"></div>
                    </div>

                    <!-- Topic -->
                    <div class="relative group pt-4">
                        <input type="text" id="topic" name="topic" required placeholder=" "
                               class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-all pb-2 px-0">
                        <label for="topic" class="absolute left-0 bottom-2.5 font-space-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 origin-left pointer-events-none">
                            Subject / Topic
                        </label>
                        <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full"></div>
                    </div>

                    <!-- Message -->
                    <div class="relative group pt-6">
                        <textarea id="message" name="message" rows="4" required placeholder=" "
                                  class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-all pb-2 px-0 resize-none"></textarea>
                        <label for="message" class="absolute left-0 top-3 font-space-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 origin-left pointer-events-none">
                            Your Message
                        </label>
                        <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full"></div>
                    </div>

                    <div class="pt-6 flex items-center justify-between">
                        <p class="font-instrument text-xs text-gray-400 max-w-xs">
                            We aim to respond to all inquiries within 24 hours.
                        </p>
                        <button type="submit" class="px-10 py-4 bg-black text-white font-space-mono text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-xl rounded-sm">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </main>

    <!-- FAQ Section -->
    <section class="w-full pb-32 px-6 pt-16">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <span class="font-space-mono text-xs font-bold uppercase tracking-[0.3em] text-gray-400 mb-4 block">Knowledge</span>
                <h2 class="font-gloock text-4xl md:text-5xl text-black">Common Inquiries</h2>
            </div>
            
            <div class="space-y-4" x-data="{ activeAccordion: null }">
                
                <!-- FAQ Item 1 -->
                <div class="border-b border-black/10 overflow-hidden">
                    <button @click="activeAccordion = activeAccordion === 1 ? null : 1" class="w-full flex items-center justify-between py-6 text-left focus:outline-none">
                        <span class="font-instrument text-xl text-black pr-8">Where are Cavari gemstones sourced?</span>
                        <span class="relative flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            <span class="absolute w-4 h-px bg-black transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 1 }"></span>
                            <span class="absolute w-px h-4 bg-black transition-transform duration-300" :class="{ 'rotate-90': activeAccordion === 1 }"></span>
                        </span>
                    </button>
                    <div class="overflow-hidden transition-all duration-500 max-h-0" :style="activeAccordion === 1 ? 'max-height: 200px' : ''">
                        <p class="font-instrument text-gray-600 leading-relaxed pb-6 pr-12">
                            The vast majority of our gemstones are sourced directly from Ratnapura, Sri Lanka, known historically as the City of Gems. We maintain direct relationships with ethical local miners to ensure complete traceability.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border-b border-black/10 overflow-hidden">
                    <button @click="activeAccordion = activeAccordion === 2 ? null : 2" class="w-full flex items-center justify-between py-6 text-left focus:outline-none">
                        <span class="font-instrument text-xl text-black pr-8">How long does a bespoke commission take?</span>
                        <span class="relative flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            <span class="absolute w-4 h-px bg-black transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 2 }"></span>
                            <span class="absolute w-px h-4 bg-black transition-transform duration-300" :class="{ 'rotate-90': activeAccordion === 2 }"></span>
                        </span>
                    </button>
                    <div class="overflow-hidden transition-all duration-500 max-h-0" :style="activeAccordion === 2 ? 'max-height: 200px' : ''">
                        <p class="font-instrument text-gray-600 leading-relaxed pb-6 pr-12">
                            A bespoke piece typically takes between 4 to 8 weeks from the initial design consultation to final delivery, depending on the complexity of the design and the specific gemstone requirements.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border-b border-black/10 overflow-hidden">
                    <button @click="activeAccordion = activeAccordion === 3 ? null : 3" class="w-full flex items-center justify-between py-6 text-left focus:outline-none">
                        <span class="font-instrument text-xl text-black pr-8">Do you ship internationally?</span>
                        <span class="relative flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            <span class="absolute w-4 h-px bg-black transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 3 }"></span>
                            <span class="absolute w-px h-4 bg-black transition-transform duration-300" :class="{ 'rotate-90': activeAccordion === 3 }"></span>
                        </span>
                    </button>
                    <div class="overflow-hidden transition-all duration-500 max-h-0" :style="activeAccordion === 3 ? 'max-height: 200px' : ''">
                        <p class="font-instrument text-gray-600 leading-relaxed pb-6 pr-12">
                            Yes, we offer complimentary, fully-insured worldwide shipping via premium logistics partners on all orders to ensure your piece arrives safely and securely.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="border-b border-black/10 overflow-hidden">
                    <button @click="activeAccordion = activeAccordion === 4 ? null : 4" class="w-full flex items-center justify-between py-6 text-left focus:outline-none">
                        <span class="font-instrument text-xl text-black pr-8">Can I view a jewel before purchasing?</span>
                        <span class="relative flex-shrink-0 w-6 h-6 flex items-center justify-center">
                            <span class="absolute w-4 h-px bg-black transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 4 }"></span>
                            <span class="absolute w-px h-4 bg-black transition-transform duration-300" :class="{ 'rotate-90': activeAccordion === 4 }"></span>
                        </span>
                    </button>
                    <div class="overflow-hidden transition-all duration-500 max-h-0" :style="activeAccordion === 4 ? 'max-height: 200px' : ''">
                        <p class="font-instrument text-gray-600 leading-relaxed pb-6 pr-12">
                            We offer private, high-resolution virtual viewings by appointment. Select pieces may also be viewed in person at The Atelier in Ratnapura by scheduling a visit.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <x-footer />
    
</body>
</html>
