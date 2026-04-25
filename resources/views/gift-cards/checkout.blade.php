<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>Checkout | The Credit | {{ config('app.name', 'Cavari') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gloock&family=Instrument+Sans:wght@400;500;600&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://js.stripe.com/v3/"></script>
    
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
        
        .StripeElement {
            background: rgba(255, 255, 255, 0.4);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid white;
        }
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient flex flex-col min-h-screen overflow-x-hidden">

    <x-navbar />

    <main class="flex-grow w-full max-w-6xl mx-auto pt-32 pb-24 px-6 md:px-12">
        
        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="font-gloock text-5xl md:text-6xl mb-4 tracking-tight">Finalize The Credit.</h1>
            <p class="font-space-mono text-[9px] uppercase tracking-[0.4em] text-gray-400">
                Secure Authentication & Delivery Details.
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-12 items-start max-w-5xl mx-auto">
            
            <!-- Gift Card Preview (Sticky Left) -->
            <div class="w-full lg:w-5/12 lg:sticky lg:top-36">
                <div class="group relative aspect-[1.586/1] w-full rounded-[1.5rem] bg-gradient-to-br from-[#1a1a1a] via-[#2d2d2d] to-black p-6 text-white overflow-hidden shadow-[0_30px_60px_-10px_rgba(0,0,0,0.3)] transition-all duration-700 flex flex-col justify-between">
                    
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
                        <div class="text-3xl font-space-mono font-bold tracking-tighter">${{ number_format($amount, 2) }}</div>
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

                    <div class="absolute -right-24 -bottom-24 w-80 h-80 bg-rose-500/10 rounded-full blur-[100px] group-hover:bg-rose-500/20 transition-all duration-700"></div>
                </div>

                <div class="mt-8 glass-card p-6 rounded-[1.5rem] border-white/40">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xs font-space-mono uppercase tracking-widest text-gray-400">Subtotal</span>
                        <span class="font-gloock text-xl text-black">${{ number_format($amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-4 border-y border-black/5">
                        <span class="text-xs font-space-mono uppercase tracking-widest text-gray-400">Processing</span>
                        <span class="text-[10px] font-space-mono text-black">INCLUDED</span>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-sm font-bold uppercase tracking-widest text-black">Grand Total</span>
                        <span class="font-gloock text-2xl text-black">${{ number_format($amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Suite (Right) -->
            <div class="w-full lg:w-7/12">
                <form id="payment-form" action="{{ route('gift-cards.purchase') }}" method="POST" class="glass-panel p-8 rounded-[2rem] border-white/60 shadow-xl space-y-8">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <input type="hidden" name="payment_intent" id="payment_intent">
                    
                    <!-- Section: Destination -->
                    <div class="space-y-6">
                         <h4 class="font-space-mono text-[9px] font-bold uppercase tracking-[0.3em] text-gray-400 border-b border-black/5 pb-4">I. Destination</h4>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-[8px] font-bold uppercase tracking-widest text-gray-400">Recipient Name</label>
                                <input type="text" name="recipient_name" required 
                                       oninput="document.getElementById('preview-recipient').textContent = this.value || 'Recipient Name'"
                                       class="w-full bg-white/40 border border-white rounded-xl px-4 py-3 text-sm font-instrument focus:ring-1 focus:ring-black outline-none transition-all" placeholder="Enter Full Name">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[8px] font-bold uppercase tracking-widest text-gray-400">Recipient Email</label>
                                <input type="email" name="recipient_email" required 
                                       class="w-full bg-white/40 border border-white rounded-xl px-4 py-3 text-sm font-instrument focus:ring-1 focus:ring-black outline-none transition-all" placeholder="email@address.com">
                            </div>
                         </div>
                    </div>

                    <!-- Section: Origin -->
                    <div class="space-y-6">
                         <h4 class="font-space-mono text-[9px] font-bold uppercase tracking-[0.3em] text-gray-400 border-b border-black/5 pb-4">II. Origin</h4>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-[8px] font-bold uppercase tracking-widest text-gray-400">Sender Name</label>
                                <input type="text" name="sender_name" required value="{{ auth()->user()->name ?? '' }}"
                                       class="w-full bg-white/40 border border-white rounded-xl px-4 py-3 text-sm font-instrument focus:ring-1 focus:ring-black outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[8px] font-bold uppercase tracking-widest text-gray-400">Sender Email</label>
                                <input type="email" name="sender_email" required value="{{ auth()->user()->email ?? '' }}"
                                       class="w-full bg-white/40 border border-white rounded-xl px-4 py-3 text-sm font-instrument focus:ring-1 focus:ring-black outline-none transition-all">
                            </div>
                         </div>
                         <div class="space-y-2">
                            <label class="block text-[8px] font-bold uppercase tracking-widest text-gray-400">Personal Inscription</label>
                            <textarea name="message" rows="3" 
                                      class="w-full bg-white/40 border border-white rounded-2xl p-4 text-sm font-instrument focus:ring-1 focus:ring-black outline-none transition-all leading-relaxed" placeholder="A message from the heart..."></textarea>
                        </div>
                    </div>

                    <!-- Section: Payment -->
                    <div class="space-y-6 pt-6 border-t border-black/5">
                        <h4 class="font-space-mono text-[9px] font-bold uppercase tracking-[0.3em] text-gray-400 mb-4">III. Settlement</h4>
                        
                        <div id="payment-element" class="w-full">
                            <!-- Stripe Injected -->
                             <div class="animate-pulse bg-white/40 h-32 w-full rounded-2xl"></div>
                        </div>
                        <div id="error-message" class="text-rose-500 text-[10px] font-space-mono uppercase text-center mt-4"></div>
                    </div>

                    <button type="submit" id="purchase-btn" class="w-full bg-black text-white py-4 rounded-xl font-space-mono text-[9px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 shadow-2xl transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-4 group">
                        Complete Acquisition & Pay
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
        const stripe = Stripe('{{ $stripeKey }}');
        const clientSecret = '{{ $clientSecret }}';
        
        let elements;
        if (clientSecret) {
            elements = stripe.elements({ clientSecret });
            const paymentElement = elements.create('payment');
            paymentElement.mount('#payment-element');
        }

        const form = document.getElementById('payment-form');
        const submitBtn = document.getElementById('purchase-btn');
        const errorDiv = document.getElementById('error-message');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing Legacy Transfer...';

            if (!clientSecret) {
                // If stripe is missing, simulate payment for dev
                form.submit();
                return;
            }

            const { error, paymentIntent } = await stripe.confirmPayment({
                elements,
                redirect: 'if_required',
                confirmParams: {
                    return_url: window.location.href,
                },
            });

            if (error) {
                errorDiv.textContent = error.message;
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Complete Acquisition & Pay';
            } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                document.getElementById('payment_intent').value = paymentIntent.id;
                form.submit();
            }
        });
    </script>
</body>
</html>
