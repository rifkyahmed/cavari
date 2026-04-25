@auth
    @php
        $showPopup = false;
        $birthdayCoupon = null;

        if (auth()->check() && auth()->user()->birthday) {
            $userBirthdayDate = \Carbon\Carbon::parse(auth()->user()->birthday);
            $birthdayThisYear = $userBirthdayDate->copy()->year(now()->year);
            
            $startOfWindow = $birthdayThisYear->copy()->startOfDay();
            $endOfWindow = $birthdayThisYear->copy()->addDays(7)->endOfDay();
            
            $inWindow = now()->between($startOfWindow, $endOfWindow);
            if (!$inWindow) {
                // If the birthday is near end of year, check if the 7-day window crosses into the new year.
                $birthdayLastYear = $userBirthdayDate->copy()->year(now()->year - 1);
                $inWindow = now()->between($birthdayLastYear->copy()->startOfDay(), $birthdayLastYear->copy()->addDays(7)->endOfDay());
            }

            if ($inWindow) {
                $birthdayCoupon = \App\Models\Coupon::where('user_email', auth()->user()->email)
                    ->where('is_birthday_offer', true)
                    ->where('usage_limit', '>', 0)
                    ->where(function($q) {
                        $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
                    })
                    ->first();
                
                if ($birthdayCoupon && !session()->has('birthday_popup_seen_' . $birthdayCoupon->id)) {
                    $showPopup = true;
                }
            }
        }
    @endphp

    @if($showPopup && $birthdayCoupon)
        <div x-data="{ 
                showBirthdayPopup: true, 
                closePopup() { 
                    this.showBirthdayPopup = false; 
                    fetch('{{ route('api.coupon.seen', $birthdayCoupon->id) }}', { 
                        method: 'POST', 
                        headers: { 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        } 
                    }); 
                } 
             }" 
             x-show="showBirthdayPopup" 
             style="display: none;"
             class="fixed inset-0 z-[9999] flex items-center justify-center pointer-events-auto p-4 md:p-0">
             
            <!-- Transparent Backdrop (No Blur) -->
            <div class="absolute inset-0 bg-black/30 transition-opacity" 
                 x-show="showBirthdayPopup"
                 x-transition:enter="ease-out duration-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closePopup()"></div>
            
            <!-- Pink & White Glass Modal Content -->
            <div class="relative w-full max-w-[90%] md:max-w-md mx-auto bg-gradient-to-br from-pink-50/80 via-white/70 to-pink-100/80 backdrop-blur-lg border border-pink-200/50 shadow-[0_15px_40px_-5px_rgba(236,72,153,0.2)] rounded-3xl overflow-hidden pointer-events-auto p-1"
                 x-show="showBirthdayPopup"
                 x-transition:enter="ease-out duration-1000 delay-200"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="ease-in duration-500"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-8">
                 
                 <!-- Inner Card -->
                 <div class="bg-gradient-to-tr from-white/60 to-pink-50/40 rounded-[1.4rem] overflow-hidden relative flex flex-col items-center p-6 md:p-8 text-center border border-white/60 shadow-inner">
                 
                     <!-- Close Button -->
                     <button @click="closePopup()" class="absolute top-3 right-3 text-gray-400 hover:text-black transition-colors z-50 p-1.5 bg-white/40 backdrop-blur-sm rounded-full cursor-pointer hover:bg-white/60 shadow-sm">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                         </svg>
                     </button>
    
                     <!-- Subtle animated background effect -->
                     <div class="absolute inset-0 opacity-10 pointer-events-none select-none mix-blend-multiply" style="background-image: radial-gradient(circle at center, rgba(236,72,153,0.3) 0%, transparent 70%); animation: float-gem 6s ease-in-out infinite alternate;"></div>
                     
                     <!-- Top Emoji icon -->
                     <div class="text-4xl md:text-5xl mb-3 animate-bounce relative z-10" style="animation-duration: 2s;">🎁</div>
                     
                     <span class="font-space-mono text-[8px] md:text-[9px] uppercase font-bold tracking-[0.3em] text-gray-400 mb-2 relative z-10 block">Cavari Exclusives</span>
                     
                     <h2 class="font-gloock text-2xl md:text-4xl mb-2 relative z-10 text-gray-900" style="line-height: 1.1;">
                        Happy Birthday,<br>
                        <span class="italic text-gray-600">{{ auth()->user()->name }}! 🎉</span>
                     </h2>
                     <p class="font-instrument text-gray-600 mt-1.5 leading-relaxed max-w-sm mx-auto text-sm md:text-base relative z-10 font-medium px-2">
                         We're thrilled to celebrate you today! Here's a special gift from us to you. ✨
                     </p>
    
                     <!-- Coupon Area -->
                     <div class="w-full mt-6 relative z-10 flex flex-col items-center">
                         
                         <!-- The Code Button -->
                         <div class="w-full max-w-[240px] bg-white/80 backdrop-blur-md border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center relative group cursor-pointer transition-all duration-300 hover:shadow-lg hover:scale-105"
                              onclick="navigator.clipboard.writeText('{{ $birthdayCoupon->code }}'); alert('Birthday code copied to clipboard!');">
                             <span class="text-[9px] font-space-mono text-gray-400 uppercase tracking-widest mb-1.5 font-bold">Your EXCLUSIVE CODE</span>
                             <h3 class="font-space-mono text-lg md:text-xl font-bold text-gray-900 tracking-wider">
                                 {{ $birthdayCoupon->code }}
                             </h3>
                             <!-- Overlay Tooltip -->
                             <div class="absolute inset-0 bg-white/95 backdrop-blur-sm rounded-xl items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex border border-gray-100 shadow-inner">
                                 <span class="text-black text-[10px] font-space-mono font-bold uppercase tracking-widest flex items-center gap-1.5">
                                     <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                     Copy Code
                                 </span>
                             </div>
                         </div>
                         
                         <!-- Offer Text -->
                         <p class="mt-5 font-instrument text-base md:text-lg text-gray-900 font-semibold mb-1">
                             Enjoy 
                             <span class="font-bold underline decoration-pink-300 decoration-2 underline-offset-4">
                                 {{ $birthdayCoupon->discount_type === 'percentage' ? $birthdayCoupon->discount_value . '%' : '$' . $birthdayCoupon->discount_value }} OFF
                             </span> 
                             your next purchase! 🥂
                         </p>
                         <p class="text-[9px] font-space-mono text-gray-500 uppercase tracking-widest bg-white/50 px-3 py-1 rounded-full border border-gray-100 mt-1.5">
                             Valid for 7 days (Until {{ \Carbon\Carbon::parse($birthdayCoupon->expiry_date)->format('M d') }})
                         </p>
                         
                         <a href="{{ route('products.index') }}" @click="closePopup()" class="mt-6 bg-black/90 backdrop-blur-md text-white font-space-mono text-[10px] md:text-xs uppercase font-bold tracking-[0.2em] py-3 px-8 rounded-full hover:bg-black transition-all duration-300 shadow-xl hover:-translate-y-1 block w-full sm:w-auto text-center border border-black group">
                             Redeem Gift Now
                             <span class="inline-block ml-1.5 group-hover:translate-x-1 transition-transform">→</span>
                         </a>
                     </div>
                 </div>
            </div>
        </div>
    @endif
@endauth
