<div x-data="{ 
    show: false,
    delay: 15000, 
    lastShown: localStorage.getItem('gift_card_popup_last_shown'),
    sessionShown: sessionStorage.getItem('gift_card_popup_session_shown'),

    init() {
        const now = new Date().getTime();
        const oneHour = 60 * 60 * 1000;

        if (!this.sessionShown && (!this.lastShown || (now - this.lastShown > oneHour))) {
            setTimeout(() => {
                this.show = true;
                sessionStorage.setItem('gift_card_popup_session_shown', 'true');
                localStorage.setItem('gift_card_popup_last_shown', now.toString());
            }, this.delay);
        }
    }
}" x-show="show" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-6">
    
    <!-- Backdrop with advanced blur -->
    <div @click="show = false" class="absolute inset-0 bg-black/60 backdrop-blur-[12px] transition-opacity duration-1000" 
         x-transition:enter="ease-out duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <!-- Popup Glass Panel -->
    <div class="relative w-full max-w-lg bg-white/70 backdrop-blur-3xl border border-white/60 rounded-[3.5rem] overflow-hidden shadow-[0_50px_100px_-20px_rgba(0,0,0,0.4)] transform transition-all duration-1000"
         x-transition:enter="ease-out duration-1000" 
         x-transition:enter-start="opacity-0 scale-90 translate-y-32 blur-xl" 
         x-transition:enter-end="opacity-100 scale-100 translate-y-0 blur-0"
         x-transition:leave="ease-in duration-500"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95 translate-y-12 blur-lg">
        
        <button @click="show = false" class="absolute top-10 right-10 text-black/20 hover:text-black transition-colors z-20 hover:rotate-90 duration-500">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="relative aspect-[4/3] bg-[#fdf2f8] overflow-hidden">
            <!-- Simulated Luxury Gift Card Image -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#1a1a1a] via-[#333] to-black m-8 rounded-3xl shadow-2xl p-8 flex flex-col justify-between text-white overflow-hidden group">
                 <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
                 <div class="flex justify-between items-start relative z-10">
                      <span class="font-space-mono text-[8px] uppercase tracking-[0.4em] text-white/40">The Legacy Pack</span>
                      <span class="font-gloock text-xl italic tracking-tighter">Cavari.</span>
                 </div>
                 <div class="relative z-10">
                      <div class="text-[6px] uppercase tracking-[0.3em] text-white/40 mb-1">Authentication</div>
                      <div class="font-space-mono text-2xl tracking-tighter">$100.00</div>
                 </div>
                 <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-rose-500/20 rounded-full blur-3xl group-hover:bg-rose-500/30 transition-all"></div>
            </div>
            
            <div class="absolute inset-x-0 bottom-0 p-12 text-center">
                <span class="font-space-mono text-[9px] font-bold uppercase tracking-[0.3em] text-black/40 mb-2 block">Exclusive Invitation</span>
                <h2 class="font-gloock text-3xl text-black tracking-tight">The Gift of Pure Choice.</h2>
            </div>
        </div>

        <div class="px-12 pb-14 text-center">
            <p class="font-instrument text-gray-500 text-sm leading-relaxed mb-10 max-w-sm mx-auto">
                Indulge someone special in the Cavari experience. Our digital credits offer immediate access to our world of handcrafted gemstones and bespoke mastery.
            </p>

            <div class="space-y-6">
                <a href="{{ route('gift-cards.index') }}" class="block w-full bg-black text-white text-center py-5 rounded-2xl font-space-mono text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition shadow-xl transform hover:-translate-y-1 active:scale-95">
                    Acquire Credit
                </a>
                <button @click="show = false" class="text-[9px] font-space-mono font-bold uppercase tracking-[0.3em] text-gray-400 hover:text-black transition-colors">
                    Continue Browsing
                </button>
            </div>
        </div>

        <!-- Background Pink Glow -->
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-rose-300/10 rounded-full blur-[100px] pointer-events-none"></div>
    </div>
</div>
