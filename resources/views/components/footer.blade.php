<section class="w-full bg-transparent border-t border-black/10 px-6 py-12 md:py-24">
    <div class="max-w-[98%] mx-auto">
        
        <div class="flex flex-col lg:flex-row gap-24 border-b border-black/10 pb-24">
            
            <!-- 1. The Newsletter (Left Side) -->
            <div class="w-full lg:w-1/3 flex flex-col justify-between">
                <div>
                    <span class="font-space-mono text-xs font-bold uppercase tracking-widest text-black mb-6 block">
                        01 — Correspondence
                    </span>
                    <h2 class="font-gloock text-4xl text-black mb-6">
                        Rare updates, <br><span class="italic text-gray-500">directly to you.</span>
                    </h2>
                    <form class="mt-12 relative w-full border-b border-black">
                         <input type="email" placeholder="Email Address" class="w-full bg-transparent py-4 font-instrument text-lg text-black placeholder-gray-400 focus:outline-none">
                         <button type="submit" class="absolute right-0 top-1/2 -translate-y-1/2 font-space-mono text-xs font-bold uppercase tracking-widest text-black hover:text-gray-500">
                             Submit
                         </button>
                    </form>
                </div>
                <p class="font-instrument text-xs text-gray-500 mt-8 leading-relaxed max-w-xs">
                    By subscribing, you acknowledge that you have read and agreed to our Privacy Policy.
                </p>
            </div>

            <!-- 2. The Index (Right Side / Footer Links) -->
            <div class="w-full lg:w-2/3">
                <span class="font-space-mono text-xs font-bold uppercase tracking-widest text-black mb-12 block">
                    02 — Index
                </span>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-12 md:gap-8">
                    
                    <!-- Col A: Links -->
                    <div>
                        <h4 class="font-gloock text-xl text-black mb-6">Links</h4>
                        <ul class="space-y-4 font-instrument text-sm text-gray-600">

                            <li><a href="{{ route('products.index') }}" class="hover:text-black hover:translate-x-1 transition-transform block">Shop</a></li>
                            <li><a href="{{ route('about') }}" class="hover:text-black hover:translate-x-1 transition-transform block">About</a></li>
                            <li><a href="{{ route('journal.index') }}" class="hover:text-black hover:translate-x-1 transition-transform block">Journal</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-black hover:translate-x-1 transition-transform block">Contact</a></li>
                        </ul>
                    </div>

                    <!-- Col B: Collections -->
                    <div>
                        <h4 class="font-gloock text-xl text-black mb-6">Collections</h4>
                        <ul class="space-y-4 font-instrument text-sm text-gray-600">
                            <li><a href="{{ route('shop.gems') }}" class="hover:text-black hover:translate-x-1 transition-transform block">Loose Gems</a></li>
                            <li><a href="{{ route('shop.jewelry') }}" class="hover:text-black hover:translate-x-1 transition-transform block">Jewelry</a></li>
                            <li><a href="{{ route('shop.custom-design') }}" class="hover:text-black hover:translate-x-1 transition-transform block">Custom Design</a></li>
                            <li><a href="{{ route('gift-cards.index') }}" class="hover:text-black hover:translate-x-1 transition-transform block">Gift Cards</a></li>
                        </ul>
                    </div>

                    <!-- Col C: Service -->
                    <div>
                        <h4 class="font-gloock text-xl text-black mb-6">Service</h4>
                        <ul class="space-y-4 font-instrument text-sm text-gray-600">
                            <li><a href="{{ route('contact') }}" class="hover:text-black hover:translate-x-1 transition-transform block">Contact Concierge</a></li>
                            <li><a href="{{ route('shop.custom-design', ['type' => 'sourcing']) }}" class="hover:text-black hover:translate-x-1 transition-transform block">Gemstone Sourcing</a></li>
                            <li><a href="{{ route('shipping-returns') }}" class="hover:text-black hover:translate-x-1 transition-transform block">Shipping & Returns</a></li>
                            <li><a href="{{ route('legal-privacy') }}" class="hover:text-black hover:translate-x-1 transition-transform block">Legal & Privacy</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <!-- 3. Bottom Anchor -->
        <div class="pt-12 flex flex-col md:flex-row justify-between items-center md:items-end text-center md:text-left">
            <div>
                 <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black block mb-2">
                     Designed in Sri Lanka
                 </span>
                 <span class="font-space-mono text-[10px] uppercase tracking-widest text-gray-400 block">
                     © 2026 Cavari Gemstones
                 </span>
            </div>
            
            <!-- Massive Brand -->
            <h1 class="font-gloock text-[12vw] leading-none text-black select-none pointer-events-none mb-0 md:-mb-10 text-center md:text-right opacity-90 mt-8 md:mt-0">
                CAVARI
            </h1>
        </div>

    </div>
</section>
