<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>The Credit | {{ config('app.name', 'Cavari') }}</title>

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

        [x-cloak] { display: none !important; }

        .glass-card {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient flex flex-col min-h-screen overflow-x-hidden">

    <x-navbar />

    <main class="flex-grow w-full max-w-6xl mx-auto pt-32 pb-24 px-6 md:px-12">
        
        <!-- Header -->
        <div class="mb-16 text-center">
            <h1 class="font-gloock text-6xl md:text-8xl mb-4 tracking-tighter">The Cavari Credit.</h1>
            <p class="font-space-mono text-[9px] uppercase tracking-[0.5em] text-gray-400">
                A legacy of elegance, shared.
            </p>
        </div>

        @if(session('success'))
        <div class="max-w-xl mx-auto mb-16 space-y-4 animate-fade-in-up" x-data="{ copied: false, shareUrl: '{{ session('share_url') }}' }">
            <div class="glass-card p-6 rounded-3xl flex items-center gap-6">
                <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="font-gloock text-xl text-black">A Gift of Choice Sent.</p>
                    <p class="text-xs font-space-mono uppercase tracking-widest text-gray-500 mt-1 leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>

            @if(session('gift_code'))
            <div class="glass-card p-8 rounded-3xl border-rose-100 flex flex-col items-center text-center space-y-6">
                <div class="space-y-2">
                    <p class="text-[10px] font-space-mono text-gray-400 uppercase tracking-widest">Your Exclusive Credit Code</p>
                    <h3 class="text-3xl font-space-mono font-bold tracking-[0.3em] text-black">{{ session('gift_code') }}</h3>
                </div>
                
                <div class="w-full h-[1px] bg-black/5"></div>
                
                <div class="space-y-4 w-full">
                    <p class="text-[10px] font-space-mono text-gray-400 uppercase tracking-widest">Personal Sharing Link</p>
                    <div class="flex items-center gap-2 bg-white/40 p-2 rounded-xl border border-white">
                        <input type="text" readonly value="{{ session('share_url') }}" class="flex-grow bg-transparent border-none text-xs font-space-mono text-gray-500 focus:ring-0 overflow-hidden text-ellipsis">
                        <button @click="navigator.clipboard.writeText(shareUrl); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="bg-black text-white px-4 py-2 rounded-lg text-[9px] font-space-mono uppercase tracking-widest hover:bg-gray-800 transition-all active:scale-95 flex-shrink-0">
                            <span x-show="!copied">Copy Link</span>
                            <span x-show="copied" x-cloak>Copied!</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-12 items-start max-w-5xl mx-auto">
            
            <!-- Gift Card Preview (Left) -->
            <div class="w-full lg:w-1/2 space-y-10">
                <div class="group relative aspect-[1.586/1] w-full rounded-[1.5rem] bg-gradient-to-br from-[#1a1a1a] via-[#2d2d2d] to-black p-6 text-white overflow-hidden shadow-[0_30px_60px_-10px_rgba(0,0,0,0.3)] transition-all duration-700 hover:scale-[1.01] flex flex-col justify-between">
                    
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 mix-blend-overlay"></div>
                    
                    <div class="relative z-10 flex justify-between items-start">
                        <div class="space-y-1">
                             <span class="block font-space-mono text-[7px] uppercase tracking-[0.5em] opacity-40">Gift Credit</span>
                             <span class="block font-gloock text-2xl italic tracking-tighter">Cavari.</span>
                        </div>
                        <div class="w-10 h-10 border border-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                             <span class="font-gloock text-lg">C.</span>
                        </div>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="text-[7px] uppercase tracking-[0.4em] opacity-40 mb-1">Available Credit</div>
                        <div class="text-4xl md:text-5xl font-space-mono font-bold tracking-tighter">$<span id="preview-amount">100</span>.00</div>
                    </div>

                    <div class="relative z-10 flex justify-between items-end pt-5 border-t border-white/10">
                        <div>
                            <div class="text-[6px] uppercase tracking-[0.3em] opacity-40 mb-1">To The Honorable</div>
                            <div class="text-xs font-space-mono tracking-widest truncate max-w-[200px]" id="preview-recipient">Recipient Name</div>
                        </div>
                        <div class="flex flex-col items-end">
                             <div class="text-[6px] uppercase tracking-[0.3em] opacity-40 mb-1">Authentication</div>
                             <div class="font-space-mono text-[7px] tracking-[0.2em]">C{{ date('Y') }}-XXXX-XXXX</div>
                        </div>
                    </div>

                    <!-- Luxury Glow -->
                    <div class="absolute -right-24 -bottom-24 w-80 h-80 bg-rose-500/10 rounded-full blur-[100px] group-hover:bg-rose-500/20 transition-all duration-700"></div>
                </div>

                <div class="glass-panel p-8 rounded-[2rem] border-white/40">
                    <h3 class="font-gloock text-2xl text-black mb-6">Redemption Protocol</h3>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <span class="w-2 h-2 rounded-full bg-rose-400 mt-2"></span>
                            <p class="text-sm text-gray-500 leading-relaxed"><span class="font-bold text-black">A minimum of $100.00</span> is required for all initial credit acquisitions.</p>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="w-2 h-2 rounded-full bg-gray-300 mt-2"></span>
                            <p class="text-sm text-gray-500 leading-relaxed">Universally accepted across our <span class="text-black italic">Loose Gems</span> and <span class="text-black italic">Bespoke Jewelry</span> suites.</p>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="w-2 h-2 rounded-full bg-gray-300 mt-2"></span>
                            <p class="text-sm text-gray-500 leading-relaxed">Digital delivery occurs instantly upon successful authentication of funds.</p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Form Suite (Right) -->
            <div class="w-full lg:w-1/2">
                <form id="purchase-form" action="{{ route('gift-cards.initialize') }}" method="POST" class="glass-panel p-8 rounded-[2rem] border-white/60 shadow-xl space-y-10">
                    @csrf
                    
                    <!-- Section: Valuation -->
                    <div class="space-y-6">
                        <h4 class="font-space-mono text-[9px] font-bold uppercase tracking-[0.3em] text-gray-400 border-b border-black/5 pb-4">I. Valuation</h4>
                        <div class="relative">
                            <span class="absolute left-0 bottom-2 text-3xl font-space-mono text-black">$</span>
                            <input type="number" name="amount" min="100" step="1" required 
                                   oninput="document.getElementById('preview-amount').textContent = this.value || '100'"
                                   onblur="if(this.value < 100) { this.value = 100; document.getElementById('preview-amount').textContent = '100'; }"
                                   onchange="if(this.value < 100) { this.value = 100; document.getElementById('preview-amount').textContent = '100'; }"
                                   class="w-full bg-transparent border-t-0 border-l-0 border-r-0 border-b-2 border-black focus:ring-0 focus:border-rose-400 text-4xl font-space-mono py-2 pl-10 placeholder-gray-100" placeholder="100">
                            @error('amount') <p class="text-rose-500 text-[9px] uppercase font-bold mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="py-6 border-y border-black/5">
                         <p class="font-instrument text-gray-400 text-sm leading-relaxed italic">
                            Authentication of your account will be required in the next step to complete the legacy pack transfer.
                         </p>
                    </div>

                    <button type="submit" id="purchase-btn" class="w-full bg-black text-white py-4 rounded-xl font-space-mono text-[9px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 shadow-2xl transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-4 group">
                        Initialize Transfer
                        <svg class="w-3 h-3 transition-transform group-hover:translate-x-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>

                    <div class="text-center">
                        <span class="text-[9px] text-gray-400 font-space-mono uppercase tracking-[0.2em] flex items-center justify-center gap-2">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Encrypted Transaction Suite
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('purchase-form');
            const amountInput = document.querySelector('input[name="amount"]');
            const previewAmount = document.getElementById('preview-amount');

            // Restore saved amount after login
            const savedAmount = sessionStorage.getItem('pending_gift_card_amount');
            if (savedAmount) {
                if (amountInput) amountInput.value = savedAmount;
                if (previewAmount) previewAmount.textContent = savedAmount;
                
                @auth
                    sessionStorage.removeItem('pending_gift_card_amount');
                @endauth
            }

            // Intercept form submission for guests
            if (form) {
                form.addEventListener('submit', function(e) {
                    @guest
                        e.preventDefault(); // Stop submission
                        
                        // Save the current amount
                        if (amountInput) {
                            sessionStorage.setItem('pending_gift_card_amount', amountInput.value);
                        }
                        
                        // Open the login modal
                        if (typeof openAuthModal === 'function') {
                            openAuthModal();
                        }
                    @endguest
                });
            }
        });
    </script>
</body>
</html>
