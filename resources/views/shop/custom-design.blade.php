<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>Custom Design | {{ config('app.name', 'Cavari') }}</title>

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

        /* Styling to make autofill backgrounds transparent */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus,
        select:-webkit-autofill,
        select:-webkit-autofill:hover,
        select:-webkit-autofill:focus {
          -webkit-text-fill-color: black;
          -webkit-box-shadow: 0 0 0px 1000px transparent inset;
          transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient flex flex-col min-h-screen">

    <x-navbar />

    <main class="flex-grow pt-48 pb-24 px-6 md:px-12 w-full max-w-[1600px] mx-auto flex items-center justify-center">

        <div class="w-full relative z-10 max-w-6xl">
            <div class="flex flex-col lg:flex-row bg-white/50 backdrop-blur-md shadow-2xl overflow-hidden rounded-sm border border-black/10 w-full">
                <!-- Left Side: Image / Vibe -->
                <div class="w-full lg:w-5/12 relative hidden md:block bg-gray-50 flex-shrink-0">
                    <div class="absolute inset-0">
                        <img src="{{ asset('images/about_gem.png') }}" class="w-full h-full object-cover object-center transform hover:scale-105 transition-transform duration-[2s] ease-out brightness-90" alt="Custom Jewelry Design">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>
                    </div>
                    <!-- Text Over Image -->
                    <div class="absolute bottom-12 left-10 right-10 text-white z-10">
                        <span class="font-space-mono text-[10px] uppercase tracking-[0.3em] block mb-4 opacity-80 decoration-1 underline underline-offset-8">Cavari Bespoke</span>
                        <h2 class="font-gloock text-3xl md:text-4xl mb-4 leading-tight">The Art of the Extraordinary</h2>
                        <p class="font-instrument text-sm opacity-90 leading-relaxed font-light mt-4">From sketch to reality. Partner with our master artisans to translate your vision into a timeless masterpiece, crafted exclusively for you.</p>
                    </div>
                </div>

                <!-- Right Side: Form -->
                <div class="w-full lg:w-7/12 p-8 md:p-14 lg:p-20 bg-transparent flex flex-col justify-center">
                    
                    <div class="mb-10">
                        <span class="font-space-mono text-[10px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-4 block">
                            Start your journey
                        </span>
                        <h1 class="font-gloock text-4xl md:text-6xl text-black mb-6 leading-none tracking-tight">
                            Commission <br> <span class="italic text-gray-500 font-light text-3xl md:text-5xl mt-2 block">Your Design.</span>
                        </h1>
                    </div>

                    @if(session('success'))
                        <div class="mb-8 p-4 bg-green-50/50 border border-green-100 text-green-800 rounded-sm font-instrument text-sm flex items-start shadow-sm">
                            <svg class="w-5 h-5 mr-3 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="leading-relaxed">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('shop.custom-design.submit') }}" method="POST" class="space-y-8 relative">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-12">
                            <!-- Name -->
                            <div class="relative group">
                                <input type="text" id="name" name="name" required placeholder=" "
                                       class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-all pb-2 px-0">
                                <label for="name" class="absolute left-0 bottom-2.5 font-space-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 origin-left pointer-events-none">
                                    Full Name
                                </label>
                                <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full"></div>
                            </div>
                            
                            <!-- Email -->
                            <div class="relative group">
                                <input type="email" id="email" name="email" required placeholder=" "
                                       class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-all pb-2 px-0">
                                <label for="email" class="absolute left-0 bottom-2.5 font-space-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 origin-left pointer-events-none">
                                    Email Address
                                </label>
                                <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full"></div>
                            </div>

                            <!-- WhatsApp -->
                            <div class="relative group">
                                <input type="tel" id="whatsapp" name="phone" placeholder=" "
                                       class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-all pb-2 px-0">
                                <label for="whatsapp" class="absolute left-0 bottom-2.5 font-space-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 origin-left pointer-events-none">
                                    WhatsApp Number
                                </label>
                                <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full"></div>
                            </div>

                            <!-- Inquiry Type -->
                            <div class="relative group">
                                <select id="type" name="type" required
                                        class="block w-full py-2 px-0 bg-transparent bg-none border-0 border-b border-gray-200 hover:border-black/50 focus:ring-0 focus:border-black outline-none font-instrument text-lg text-black appearance-none rounded-none cursor-pointer transition-colors relative z-10 pr-8">
                                    <option value="" disabled {{ !request('type') ? 'selected' : '' }} class="text-gray-400">Inquiry Type...</option>
                                    <option value="ring" {{ request('type') == 'ring' ? 'selected' : '' }}>Custom Ring</option>
                                    <option value="necklace" {{ request('type') == 'necklace' ? 'selected' : '' }}>Custom Necklace</option>
                                    <option value="earrings" {{ request('type') == 'earrings' ? 'selected' : '' }}>Custom Earrings</option>
                                    <option value="sourcing" {{ request('type') == 'sourcing' ? 'selected' : '' }}>Gemstone Sourcing</option>
                                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other Inquiry</option>
                                </select>
                                <div class="absolute right-0 bottom-3 pointer-events-none z-0">
                                    <svg class="w-4 h-4 text-gray-400 group-focus-within:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 group-focus-within:w-full"></div>
                            </div>
                        </div>

                        <!-- Vision Textarea -->
                        <div class="relative group pt-4">
                            <textarea id="message" name="message" rows="4" required placeholder=" "
                                      class="block w-full bg-transparent border-0 border-b border-gray-200 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-all pb-2 px-0 resize-none"></textarea>
                            <label for="message" class="absolute left-0 top-3 font-space-mono text-[10px] uppercase tracking-[0.2em] text-gray-400 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 origin-left pointer-events-none">
                                Describe Your Vision
                            </label>
                            <div class="absolute bottom-0 left-0 h-0.5 w-0 bg-black transition-all duration-500 peer-focus:w-full"></div>
                        </div>

                        <div class="pt-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                            <p class="font-instrument text-[11px] text-gray-500 tracking-wide leading-relaxed max-w-xs">
                                Upon submission, a Cavari consultant will contact you within 24 hours to arrange an initial consultation.
                            </p>
                            <button type="submit" class="group relative px-10 py-4 bg-black text-white hover:bg-gray-900 transition-colors overflow-hidden shrink-0 flex items-center justify-center rounded-sm">
                                <span class="relative z-10 font-space-mono text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] flex items-center">
                                    Get a Quote
                                    <svg class="w-3 h-3 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </span>
                                <div class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500 ease-out"></div>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
