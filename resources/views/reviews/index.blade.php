<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Experiences | {{ config('app.name', 'Cavari') }}</title>
    <meta name="description" content="Read what Cavari clients say about their experience with our exquisite gems and bespoke jewelry.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gloock&family=Instrument+Sans:wght@400;500;600&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-gloock     { font-family: 'Gloock', serif; }
        .font-instrument { font-family: 'Instrument Sans', sans-serif; }
        .font-space-mono { font-family: 'Space Mono', monospace; }

        /* Background — identical to homepage */
        body {
            background: linear-gradient(135deg, #fff 0%, #fff0f5 50%, #fff 100%);
        }

        /* ── Marquee ──────────────────────────────── */
        @keyframes marqueeLeft {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        @keyframes marqueeRight {
            from { transform: translateX(-50%); }
            to   { transform: translateX(0); }
        }
        .marquee-left  { animation: marqueeLeft  55s linear infinite; }
        .marquee-right { animation: marqueeRight 55s linear infinite; }
        .marquee-track:hover .marquee-left,
        .marquee-track:hover .marquee-right { animation-play-state: paused; }

        /* ── Review Card — borderless, open ──────── */
        .review-card {
            width: 380px;
            flex-shrink: 0;
            padding: 2.5rem 2rem;
            border-left: 1px solid rgba(0,0,0,0.08);
            transition: background 0.4s;
        }
        .review-card:hover {
            background: rgba(255,255,255,0.5);
        }

        /* ── Floating-label input ─────────────────── */
        .field {
            position: relative;
            padding-top: 1.4rem;
        }
        .field input,
        .field textarea {
            display: block;
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1px solid rgba(0,0,0,0.15);
            padding: 0 0 0.6rem;
            font-family: 'Instrument Sans', sans-serif;
            font-size: 1rem;
            color: #111;
            outline: none;
            resize: none;
            transition: border-color 0.3s;
        }
        .field input:focus,
        .field textarea:focus { border-bottom-color: #000; }
        .field label {
            position: absolute;
            left: 0;
            top: 1.4rem;
            font-family: 'Space Mono', monospace;
            font-size: 0.55rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: rgba(0,0,0,0.35);
            pointer-events: none;
            transition: all 0.25s ease;
        }
        .field input:focus ~ label,
        .field input:not(:placeholder-shown) ~ label,
        .field textarea:focus ~ label,
        .field textarea:not(:placeholder-shown) ~ label {
            top: 0;
            font-size: 0.48rem;
            color: #000;
        }
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 flex flex-col min-h-screen">

    <x-navbar />

    <main class="flex-grow overflow-hidden">

        <div class="max-w-4xl mx-auto px-6 text-center pt-32 pb-16">
            <span class="font-space-mono text-xs font-bold uppercase tracking-[0.3em] text-gray-500 mb-6 block">
                The Ledger
            </span>
            <h1 class="font-gloock text-5xl md:text-7xl text-black leading-none mb-6">
                Client<br><span class="italic text-gray-600">Admiration.</span>
            </h1>
            <p class="font-instrument text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Legacy is built upon trust. Read the experiences of connoisseurs who have chosen Cavari to commemorate their most treasured moments.
            </p>
        </div>

        {{-- ══════════════════════════════════
             MARQUEE ROWS OF REVIEWS
        ══════════════════════════════════════ --}}
        <div class="w-full py-16">

            @if($reviews->count() > 0)

                @php
                    $splitAt   = ceil($reviews->count() / 2);
                    $topRow    = $reviews->count() > 6 ? $reviews->slice(0, $splitAt) : $reviews;
                    $bottomRow = $reviews->count() > 6 ? $reviews->slice($splitAt) : collect();
                    $repeat    = 4;
                @endphp

                {{-- Row 1 — scrolls LEFT --}}
                <div class="flex overflow-hidden marquee-track mb-0 border-t border-black/8">
                    <div class="flex marquee-left">
                        @for($i = 0; $i < $repeat; $i++)
                            @foreach($topRow as $review)
                                @include('reviews.partials.card', ['review' => $review])
                            @endforeach
                        @endfor
                        @for($i = 0; $i < $repeat; $i++)
                            @foreach($topRow as $review)
                                @include('reviews.partials.card', ['review' => $review])
                            @endforeach
                        @endfor
                    </div>
                </div>

                @if($bottomRow->count() > 0)
                    {{-- Row 2 — scrolls RIGHT --}}
                    <div class="flex overflow-hidden marquee-track border-t border-black/8">
                        <div class="flex marquee-right">
                            @for($i = 0; $i < $repeat; $i++)
                                @foreach($bottomRow as $review)
                                    @include('reviews.partials.card', ['review' => $review])
                                @endforeach
                            @endfor
                            @for($i = 0; $i < $repeat; $i++)
                                @foreach($bottomRow as $review)
                                    @include('reviews.partials.card', ['review' => $review])
                                @endforeach
                            @endfor
                        </div>
                    </div>
                @endif

                <div class="border-t border-black/8 w-full"></div>

            @else
                <div class="max-w-[95%] mx-auto px-6 py-24 border-t border-b border-black/8 text-center">
                    <p class="font-space-mono text-[10px] uppercase tracking-widest text-black/25">
                        Be the first to share your Cavari experience.
                    </p>
                </div>
            @endif

        </div>

        <div class="max-w-4xl mx-auto px-6 py-20 mt-16 border-t border-black/10">
             
            <div class="bg-white/50 backdrop-blur-md p-8 md:p-14 border border-black/10 shadow-xl rounded-sm">
                <div class="text-center mb-10">
                    <h2 class="font-gloock text-3xl md:text-4xl text-black mb-4">Leave Your Legacy</h2>
                    <p class="font-instrument text-gray-500 text-sm">Have you received a piece from us? We invite you to share your story.</p>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-sm font-instrument text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('reviews.public.store') }}" method="POST" class="space-y-8" x-data="{ rating: 0, hoverRating: 0, reviewText: '' }">
                    @csrf
                    
                    <!-- Rating Stars -->
                    <div class="flex flex-col items-center justify-center space-y-3 mb-8">
                        <span class="font-space-mono text-[10px] uppercase tracking-widest text-black/40">Select Rating</span>
                        <div class="flex gap-2">
                             <template x-for="i in 5">
                                <svg @click="rating = i" 
                                     @mouseenter="hoverRating = i" 
                                     @mouseleave="hoverRating = 0"
                                     class="w-10 h-10 transition-transform duration-200 cursor-pointer transform hover:scale-110"
                                     :class="(hoverRating >= i || rating >= i) ? 'fill-black drop-shadow-md' : 'fill-transparent stroke-black/20 stroke-1'"
                                     viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            </template>
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                        @error('rating') <span class="text-red-500 text-xs font-instrument">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="relative group pt-4">
                            <input type="text" id="author_name" name="author_name" required placeholder=" "
                                   class="block w-full bg-transparent border-0 border-b border-black/30 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-colors pb-2 px-0">
                            <label for="author_name" class="absolute left-0 bottom-3 font-space-mono text-[10px] md:text-xs uppercase tracking-[0.15em] text-gray-500 duration-300 transform peer-focus:-translate-y-6 peer-focus:text-[9px] peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-6 peer-[&:not(:placeholder-shown)]:text-[9px] origin-left pointer-events-none">
                                Full Name
                            </label>
                            @error('author_name') <span class="text-red-500 text-xs font-instrument block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="relative group pt-4">
                            <input type="text" id="location" name="location" placeholder=" "
                                   class="block w-full bg-transparent border-0 border-b border-black/30 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-colors pb-2 px-0">
                            <label for="location" class="absolute left-0 bottom-3 font-space-mono text-[10px] md:text-xs uppercase tracking-[0.15em] text-gray-500 duration-300 transform peer-focus:-translate-y-6 peer-focus:text-[9px] peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-6 peer-[&:not(:placeholder-shown)]:text-[9px] origin-left pointer-events-none">
                                Location/Country (Optional)
                            </label>
                        </div>
                    </div>

                    <div class="relative group pt-6">
                        <textarea id="comment" name="comment" rows="4" required placeholder=" " x-model="reviewText" maxlength="250"
                                  class="block w-full bg-transparent border-0 border-b border-black/30 focus:ring-0 focus:border-black outline-none font-instrument text-lg peer transition-colors pb-2 px-0 resize-none"></textarea>
                        <label for="comment" class="absolute left-0 top-6 font-space-mono text-[10px] md:text-xs uppercase tracking-[0.15em] text-gray-500 duration-300 transform peer-focus:-translate-y-8 peer-focus:text-[9px] peer-focus:text-black peer-placeholder-shown:translate-y-0 peer-[&:not(:placeholder-shown)]:-translate-y-8 peer-[&:not(:placeholder-shown)]:text-[9px] origin-left pointer-events-none">
                            Your Experience
                        </label>
                        <div class="absolute right-0 top-6 font-space-mono text-[10px] text-gray-400">
                             <span x-text="reviewText.length"></span>/250
                        </div>
                        @error('comment') <span class="text-red-500 text-xs font-instrument block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-8 text-center">
                        <button type="submit" class="group relative px-12 py-4 bg-black text-white hover:bg-gray-900 transition-colors overflow-hidden rounded-sm inline-flex items-center">
                            <span class="relative z-10 font-space-mono text-xs font-bold uppercase tracking-[0.2em] flex items-center">
                                Submit Story
                                <svg class="w-3 h-3 ml-3 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                            <div class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-500 ease-out"></div>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <x-footer />
</body>
</html>
