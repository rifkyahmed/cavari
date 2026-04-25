<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>Secure Checkout | {{ config('app.name', 'Cavari') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gloock&family=Instrument+Sans:wght@400;500;600&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://js.stripe.com/v3/"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-gloock { font-family: 'Gloock', serif; }
        .font-instrument { font-family: 'Instrument Sans', sans-serif; }
        .font-space-mono { font-family: 'Space Mono', monospace; }
        
        .bg-hero-gradient {
            background: linear-gradient(135deg, #fff 0%, #fff0f5 50%, #fff 100%);
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient flex flex-col min-h-screen">

    <x-navbar />

    <main class="flex-grow w-full max-w-7xl mx-auto pt-36 pb-32 px-6 md:px-12">
        
        <!-- Header -->
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between border-b border-black/5 pb-8">
            <div>
                 <h1 class="font-gloock text-4xl md:text-5xl mb-2 flex items-center gap-3">
                    <svg class="w-8 h-8 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Secure Checkout
                </h1>
                <p class="font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 pl-11">
                    Finalize Transaction
                </p>
            </div>
            <div class="mt-6 md:mt-0 pl-11 md:pl-0">
                <a href="{{ route('cart.index') }}" class="text-xs font-space-mono uppercase tracking-widest text-gray-500 hover:text-black border-b border-gray-300 pb-0.5 hover:border-black transition-colors">
                    ← Return to Bag
                </a>
            </div>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="payment-form" class="flex flex-col lg:flex-row gap-16 items-start">
            @csrf
            
            <!-- Left: Shipping & Data -->
            <div class="w-full lg:w-2/3 space-y-12">
                
                <!-- Section 1: Identity -->
                <div class="relative bg-white/40 backdrop-blur-md border border-white/60 rounded-[2rem] p-8 md:p-10 shadow-sm">
                    <h2 class="font-gloock text-2xl mb-8 flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-black text-white text-xs font-space-mono flex items-center justify-center">1</span>
                        Identity
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                             <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Full Name</label>
                             <div class="w-full bg-white/50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-instrument text-gray-700 cursor-not-allowed">
                                 {{ auth()->user()->name ?? 'Guest' }}
                             </div>
                        </div>
                        <div>
                             <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Email Address</label>
                             <div class="w-full bg-white/50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-instrument text-gray-700 cursor-not-allowed">
                                 {{ auth()->user()->email ?? 'Guest' }}
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Destination -->
                <div x-data="{ 
                    shippingSameAsBilling: true, 
                    selectedAddress: '',
                    addresses: {{ $addresses->toJson() }},
                    billing: {
                        address: '',
                        city: '',
                        zip: '',
                        country: 'USA'
                    },
                    shipping: {
                        address: '',
                        city: '',
                        zip: '',
                        country: 'USA'
                    },
                    updateBillingFromSaved() {
                        const addr = this.addresses.find(a => a.id == this.selectedAddress);
                        if(addr) {
                            this.billing.address = addr.address_line1 + (addr.address_line2 ? ', ' + addr.address_line2 : '');
                            this.billing.city = addr.city;
                            this.billing.zip = addr.postal_code;
                            this.billing.country = addr.country;
                        } else {
                            this.billing.address = '';
                            this.billing.city = '';
                            this.billing.zip = '';
                            this.billing.country = 'USA';
                        }
                    }
                }" class="space-y-8">
                    
                    <!-- Billing Address -->
                    <div class="relative bg-white/40 backdrop-blur-md border border-white/60 rounded-[2rem] p-8 md:p-10 shadow-sm">
                        <h2 class="font-gloock text-2xl mb-8 flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-black text-white text-xs font-space-mono flex items-center justify-center">2</span>
                            Billing Address
                        </h2>

                        @if($addresses->count() > 0)
                        <div class="mb-8 p-6 bg-black/5 rounded-2xl border border-black/5">
                            <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-3">Saved Addresses</label>
                            <select x-model="selectedAddress" @change="updateBillingFromSaved" class="w-full bg-white/80 border border-white rounded-xl px-4 py-3 text-sm font-instrument outline-none focus:ring-1 focus:ring-black transition-shadow shadow-sm">
                                <option value="">Enter new address...</option>
                                <template x-for="addr in addresses" :key="addr.id">
                                    <option :value="addr.id" x-text="`${addr.address_line1}, ${addr.city}`"></option>
                                </template>
                            </select>
                        </div>
                        @endif
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Street Address</label>
                                <input type="text" name="billing_address" x-model="billing.address" required class="w-full bg-white/80 border border-white rounded-xl px-4 py-3 text-sm font-instrument outline-none focus:ring-1 focus:ring-black transition-shadow shadow-sm placeholder-gray-300" placeholder="123 Luxury Ave">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">City</label>
                                    <input type="text" name="billing_city" x-model="billing.city" required class="w-full bg-white/80 border border-white rounded-xl px-4 py-3 text-sm font-instrument outline-none focus:ring-1 focus:ring-black transition-shadow shadow-sm" placeholder="New York">
                                </div>
                                <div>
                                    <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Postal Code</label>
                                    <input type="text" name="billing_zip" x-model="billing.zip" required class="w-full bg-white/80 border border-white rounded-xl px-4 py-3 text-sm font-instrument outline-none focus:ring-1 focus:ring-black transition-shadow shadow-sm" placeholder="10001">
                                </div>
                                <div>
                                    <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Country</label>
                                    <input type="text" name="billing_country" x-model="billing.country" required class="w-full bg-white/80 border border-white rounded-xl px-4 py-3 text-sm font-instrument outline-none focus:ring-1 focus:ring-black transition-shadow shadow-sm" placeholder="United States">
                                </div>
                            </div>
                        </div>

                        <!-- Checkbox: Shipping same as billing -->
                        <div class="mt-8 pt-8 border-t border-black/5">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="shipping_same_as_billing" x-model="shippingSameAsBilling" class="w-5 h-5 rounded border-gray-200 text-black focus:ring-black transition-all">
                                <span class="font-instrument text-sm text-gray-600 group-hover:text-black transition-colors">My shipping address is the same as billing</span>
                            </label>
                        </div>
                    </div>

                    <!-- Shipping Address (Optional) -->
                    <template x-if="!shippingSameAsBilling">
                        <div class="relative bg-white/40 backdrop-blur-md border border-white/60 rounded-[2rem] p-8 md:p-10 shadow-sm" 
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 -translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <h2 class="font-gloock text-2xl mb-8 flex items-center gap-3">
                                <span class="w-6 h-6 rounded-full bg-black text-white text-xs font-space-mono flex items-center justify-center">2.1</span>
                                Shipping Address
                            </h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Street Address</label>
                                    <input type="text" name="shipping_address" x-model="shipping.address" :required="!shippingSameAsBilling" class="w-full bg-white/80 border border-white rounded-xl px-4 py-3 text-sm font-instrument outline-none focus:ring-1 focus:ring-black transition-shadow shadow-sm placeholder-gray-300" placeholder="123 Delivery Ln">
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">City</label>
                                        <input type="text" name="shipping_city" x-model="shipping.city" :required="!shippingSameAsBilling" class="w-full bg-white/80 border border-white rounded-xl px-4 py-3 text-sm font-instrument outline-none focus:ring-1 focus:ring-black transition-shadow shadow-sm" placeholder="Los Angeles">
                                    </div>
                                    <div>
                                        <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Postal Code</label>
                                        <input type="text" name="shipping_zip" x-model="shipping.zip" :required="!shippingSameAsBilling" class="w-full bg-white/80 border border-white rounded-xl px-4 py-3 text-sm font-instrument outline-none focus:ring-1 focus:ring-black transition-shadow shadow-sm" placeholder="90001">
                                    </div>
                                    <div>
                                        <label class="block font-space-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Country</label>
                                        <input type="text" name="shipping_country" x-model="shipping.country" :required="!shippingSameAsBilling" class="w-full bg-white/80 border border-white rounded-xl px-4 py-3 text-sm font-instrument outline-none focus:ring-1 focus:ring-black transition-shadow shadow-sm" placeholder="United States">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Section 3: Payment -->
                <div class="relative bg-white/40 backdrop-blur-md border border-white/60 rounded-[2rem] p-8 md:p-10 shadow-sm">
                    <h2 class="font-gloock text-2xl mb-8 flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-black text-white text-xs font-space-mono flex items-center justify-center">3</span>
                        Payment Method
                    </h2>

                    <!-- Payment Method Selector -->
                    <div x-data="{ method: 'stripe' }" class="space-y-8">
                        <input type="hidden" name="payment_method" :value="method">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            <button type="button" 
                                @click="method = 'stripe'"
                                :class="method === 'stripe' ? 'border-black bg-black text-white' : 'border-gray-200 bg-white/50 text-gray-500'"
                                class="flex items-center justify-center gap-3 p-4 rounded-xl border-2 transition-all font-space-mono text-[10px] font-bold uppercase tracking-widest">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Credit Card
                            </button>
                            <button type="button" 
                                @click="method = 'coinbase'"
                                :class="method === 'coinbase' ? 'border-black bg-black text-white' : 'border-gray-200 bg-white/50 text-gray-500'"
                                class="flex items-center justify-center gap-3 p-4 rounded-xl border-2 transition-all font-space-mono text-[10px] font-bold uppercase tracking-widest">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Cryptocurrency
                            </button>
                        </div>

                        <!-- Stripe Payment Element -->
                        <div x-show="method === 'stripe'" x-transition>
                            <div id="payment-element" class="w-full mb-6">
                                <!-- Stripe will inject its iframe here -->
                                <div class="animate-pulse bg-gray-200 h-32 w-full rounded-2xl"></div>
                            </div>
                        </div>

                        <!-- Coinbase Message -->
                        <div x-show="method === 'coinbase'" x-transition class="bg-black/5 rounded-2xl p-6 border border-black/5">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-gloock text-lg mb-1">Pay with Crypto</h4>
                                    <p class="text-sm text-gray-600 font-instrument">You will be redirected to Coinbase Commerce to complete your payment securely using Bitcoin, Ethereum, or other cryptocurrencies.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="payment-message" class="text-red-500 font-space-mono text-xs mt-4 hidden text-center"></div>

                    @if(!config('services.stripe.key'))
                    <p x-show="method === 'stripe'" class="text-center font-space-mono text-xs text-orange-500 mt-4">
                        <span class="inline-block w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                        Stripe API keys are missing in your environment.
                    </p>
                    @endif

                </div>

            </div>

            <!-- Right: Order Manifest (Sticky) -->
            <div class="w-full lg:w-1/3 lg:sticky lg:top-36">
                <div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-8 shadow-[0_20px_40px_-12px_rgba(0,0,0,0.1)]">
                    <h2 class="font-gloock text-2xl mb-6">Manifest</h2>
                    
                    <!-- Items List -->
                    <div class="space-y-4 mb-8 max-h-[40vh] overflow-y-auto pr-2 custom-scrollbar">
                         @foreach(session('cart', []) as $details)
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 border border-white">
                                     @if($details['image'])
                                        @php
                                            $mainImage = $details['image'];
                                            $imageUrl = asset('images/sapphire.png'); // Default fallback

                                            if ($mainImage) {
                                                if (\Illuminate\Support\Str::startsWith($mainImage, ['http', 'https'])) {
                                                    $imageUrl = $mainImage;                         // full URL
                                                } elseif (\Illuminate\Support\Str::startsWith($mainImage, ['/storage/', 'storage/'])) {
                                                    $imageUrl = asset($mainImage);                  // already has /storage/ prefix
                                                } elseif (\Illuminate\Support\Str::startsWith($mainImage, 'images/')) {
                                                    $imageUrl = asset($mainImage);                  // public/images/...
                                                } else {
                                                    $imageUrl = asset('storage/' . $mainImage);     // bare relative path
                                                }
                                            }
                                        @endphp
                                        <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                                     @endif
                                </div>
                                <div class="flex-grow min-w-0">
                                    <h4 class="font-instrument text-sm font-medium truncate">{{ $details['name'] }}</h4>
                                    <p class="font-space-mono text-[10px] text-gray-500">Qty: {{ $details['quantity'] }}</p>
                                </div>
                                <span class="font-instrument text-sm font-medium">{{ \App\Helpers\CurrencyHelper::format($details['price'] * $details['quantity']) }}</span>
                            </div>
                         @endforeach
                    </div>

                    <!-- Totals -->
                    <div class="border-t border-black/10 pt-6 mb-8 space-y-3">
                         <div class="flex justify-between font-instrument text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span>{{ \App\Helpers\CurrencyHelper::format($subtotal) }}</span>
                        </div>
                        @if($discount > 0)
                        <div class="flex justify-between font-instrument text-sm text-green-700">
                            <span>Discount ({{ session('coupon')['code'] ?? '' }})</span>
                            <span>-{{ \App\Helpers\CurrencyHelper::format($discount) }}</span>
                        </div>
                        @endif
                        @if($giftCardAmount > 0)
                        <div class="flex justify-between font-instrument text-sm text-indigo-700">
                            <span>Gift Card ({{ session('gift_card')['code'] ?? '' }})</span>
                            <span>-{{ \App\Helpers\CurrencyHelper::format($giftCardAmount) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between font-instrument text-sm text-gray-600">
                            <span>Shipping</span>
                            <span class="text-black font-medium">Free</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-3 border-t border-black/5 mt-3">
                             <span class="font-space-mono text-xs font-bold uppercase tracking-widest">Total</span>
                             <span class="font-gloock text-3xl">{{ \App\Helpers\CurrencyHelper::format($total) }}</span>
                        </div>
                    </div>

                    <button type="submit" id="submit-button" class="w-full bg-black text-white py-4 rounded-xl font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 shadow-lg transition-transform transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="button-text">Confirm Order</span>
                    </button>
                    
                    <p class="mt-4 text-center text-[9px] text-gray-400 font-space-mono max-w-xs mx-auto">
                        By confirming, you agree to our Terms of Service. Purchases are non-refundable in this demo environment.
                    </p>

                </div>
            </div>

        </form>

    </main>

    <x-footer />
    
    <style>
        /* Custom Scrollbar for Manifest */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { bg: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>

    <script>
        const stripeKey = "{{ config('services.stripe.key') }}";
        const clientSecret = "{{ $clientSecret ?? '' }}";

        let stripe, elements, paymentElement;

        if(stripeKey && clientSecret) {
            stripe = Stripe(stripeKey);
            const appearance = {
                theme: 'stripe',
                variables: {
                    fontFamily: '"Instrument Sans", sans-serif',
                    fontLineHeight: '1.5',
                    borderRadius: '0.75rem',
                    colorBackground: 'rgba(255, 255, 255, 0.8)',
                    colorPrimary: '#000000',
                    colorText: '#374151',
                    colorDanger: '#ef4444',
                },
                rules: {
                    '.Input': {
                        border: '1px solid #ffffff',
                        boxShadow: '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                        padding: '12px 16px',
                    },
                    '.Input:focus': {
                        border: '1px solid #000000',
                        boxShadow: '0 0 0 1px #000000',
                    }
                }
            };
            
            elements = stripe.elements({ appearance, clientSecret });
            paymentElement = elements.create("payment", { layout: "tabs" });
            paymentElement.mount("#payment-element");
        }

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            const submitButton = document.getElementById('submit-button');
            const btnText = document.getElementById('button-text');
            const messageContainer = document.querySelector('#payment-message');
            const paymentMethod = form.querySelector('input[name="payment_method"]').value;
            
            submitButton.disabled = true;
            btnText.innerHTML = 'PROCESSING...';
            messageContainer.classList.add('hidden');

            if (paymentMethod === 'stripe') {
                if(!stripe || !clientSecret) {
                    messageContainer.classList.remove('hidden');
                    messageContainer.textContent = "Stripe is not configured.";
                    submitButton.disabled = false;
                    btnText.innerHTML = 'CONFIRM ORDER';
                    return;
                }

                const { error, paymentIntent } = await stripe.confirmPayment({
                    elements,
                    redirect: "if_required"
                });

                if (error) {
                    messageContainer.classList.remove('hidden');
                    messageContainer.textContent = error.message;
                    submitButton.disabled = false;
                    btnText.innerHTML = 'CONFIRM ORDER';
                } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'payment_intent');
                    hiddenInput.setAttribute('value', paymentIntent.id);
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            } else if (paymentMethod === 'coinbase') {
                // For Coinbase, we first create the order in our DB
                const formData = new FormData(form);
                
                try {
                    // Step 1: Create local order
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to create order');
                    }

                    // Step 2: Create Coinbase Charge
                    const coinbaseResponse = await fetch("{{ route('checkout.coinbase') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ order_id: data.order_id })
                    });

                    const coinbaseData = await coinbaseResponse.json();

                    if (coinbaseData.success && coinbaseData.hosted_url) {
                        window.location.href = coinbaseData.hosted_url;
                    } else {
                        throw new Error(coinbaseData.message || 'Failed to initialize crypto payment');
                    }

                } catch (error) {
                    messageContainer.classList.remove('hidden');
                    messageContainer.textContent = error.message;
                    submitButton.disabled = false;
                    btnText.innerHTML = 'CONFIRM ORDER';
                }
            }
        });
    </script>

</body>
</html>
