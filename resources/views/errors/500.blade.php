<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A Momentary Pause | 500 Server Error</title>

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
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 flex items-center justify-center min-h-screen px-6">

    <div class="max-w-xl w-full text-center relative">
        
        <div class="glass p-12 md:p-20 rounded-[3rem] shadow-2xl relative overflow-hidden">
            
            <h1 class="font-gloock text-4xl md:text-5xl text-black mb-6 leading-tight">A Momentary <br><span class="italic text-gray-400">Interruption</span></h1>
            
            <p class="text-gray-500 mb-12 max-w-sm mx-auto leading-relaxed">
                Our artisans are currently adjusting the internal mechanisms of the site. We shall return shortly.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/') }}" class="w-full sm:w-auto px-10 py-4 bg-black text-white font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] rounded-full hover:bg-gray-800 transition-all shadow-lg">
                    Return Home
                </a>
            </div>

            <div class="mt-16 opacity-10">
                <h2 class="font-gloock text-2xl tracking-[0.5em] uppercase">CAVARI</h2>
            </div>
        </div>
    </div>

</body>
</html>
