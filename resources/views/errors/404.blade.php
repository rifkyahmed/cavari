<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lost in Elegance | 404 Not Found</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gloock&family=Instrument+Sans:wght@400;500&family=Space+Mono&display=swap" rel="stylesheet">

    <!-- Out-of-box Tailwind via Vite if possible, or CDN for error pages is safer -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .font-gloock { font-family: 'Gloock', serif; }
        .font-instrument { font-family: 'Instrument Sans', sans-serif; }
        .font-space-mono { font-family: 'Space Mono', monospace; }
        
        body {
            background: linear-gradient(135deg, #fff 0%, #fff0f5 50%, #fff 100%);
            overflow: hidden;
        }

        .glass {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gem-glow {
            filter: drop-shadow(0 0 20px rgba(255, 182, 193, 0.3));
        }
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 flex items-center justify-center min-h-screen px-6">

    <div class="max-w-xl w-full text-center relative">
        
        <!-- Subtle Background Decorative Element -->
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-64 h-64 bg-pink-100 rounded-full blur-[100px] opacity-50 -z-10"></div>

        <div class="glass p-12 md:p-20 rounded-[3rem] shadow-2xl relative overflow-hidden">
            
            <!-- Icon -->
            <div class="mb-10 inline-block relative">
                 <div class="w-16 h-16 border border-black/10 rounded-full flex items-center justify-center relative z-10">
                    <svg class="w-8 h-8 text-black/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                 </div>
                 <div class="absolute -top-2 -right-2 bg-black text-white px-2 py-1 rounded-full font-space-mono text-[9px] uppercase tracking-tighter">404</div>
            </div>

            <h1 class="font-gloock text-6xl md:text-7xl text-black mb-6 leading-none">Lost in <br><span class="italic text-gray-400">Elegance</span></h1>
            
            <p class="text-gray-500 mb-12 max-w-sm mx-auto leading-relaxed">
                The artifact you seek has vanished into our private archives or has never existed in this realm.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/') }}" class="w-full sm:w-auto px-8 py-4 bg-black text-white font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] rounded-full hover:bg-gray-800 transition-all shadow-lg hover:-translate-y-0.5">
                    Return Home
                </a>
                <a href="{{ route('products.index') }}" class="w-full sm:w-auto px-8 py-4 border border-black text-black font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] rounded-full hover:bg-black hover:text-white transition-all">
                    The Treasury
                </a>
            </div>

            <!-- Subtle Logo -->
            <div class="mt-16 opacity-10">
                <h2 class="font-gloock text-2xl tracking-[0.5em] uppercase">CAVARI</h2>
            </div>
        </div>
    </div>

</body>
</html>
