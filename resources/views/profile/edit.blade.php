<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>My Atelier | {{ config('app.name', 'Cavari') }}</title>

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
    </style>
</head>
<body class="font-instrument antialiased text-gray-900 bg-hero-gradient flex flex-col min-h-screen">

    <x-navbar />

    <main class="flex-grow w-full max-w-[1600px] mx-auto pt-32 pb-24 px-6 md:px-12">
        
        <!-- Header -->
        <div class="mb-12 md:mb-16 text-center md:text-left relative">
            <h1 class="font-gloock text-5xl md:text-7xl mb-4 text-transparent bg-clip-text bg-gradient-to-br from-black to-gray-600">
                Atelier Profile
            </h1>
            <p class="font-space-mono text-xs font-bold uppercase tracking-widest text-gray-400">
                Member ID: {{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }} // {{ $user->created_at->format('Y') }}
            </p>
            
            <!-- Floating Decor -->
            <div class="absolute top-0 right-0 hidden md:block opacity-20 pointer-events-none">
                <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="animate-spin-slow">
                    <circle cx="100" cy="100" r="90" stroke="black" stroke-width="1" stroke-dasharray="10 10"/>
                    <circle cx="100" cy="100" r="60" stroke="black" stroke-width="1"/>
                </svg>
            </div>
        </div>

        <!-- Crystal Masonry Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- COL 1: IDENTITY (Width: 4/12) -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- IDENTITY MODULE -->
                <div class="group relative overflow-hidden rounded-3xl p-8 transition-all duration-500 hover:shadow-[0_20px_40px_rgba(0,0,0,0.05)]"
                     style="background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);">
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-100 to-white border border-white flex items-center justify-center shadow-inner">
                            <span class="font-gloock text-3xl">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h2 class="font-gloock text-2xl">Identity</h2>
                            <p class="text-xs font-space-mono text-gray-400 uppercase tracking-widest">Personal Details</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div class="space-y-2">
                            <label class="ml-4 text-[10px] font-space-mono font-bold uppercase tracking-widest text-gray-400">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                   class="w-full bg-white/60 border border-white focus:border-black/20 rounded-2xl px-6 py-4 font-instrument text-sm outline-none transition-all focus:bg-white shadow-sm hover:shadow-md">
                            <x-input-error class="text-xs ml-4" :messages="$errors->get('name')" />
                        </div>

                        <div class="space-y-2">
                            <label class="ml-4 text-[10px] font-space-mono font-bold uppercase tracking-widest text-gray-400">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                   class="w-full bg-white/60 border border-white focus:border-black/20 rounded-2xl px-6 py-4 font-instrument text-sm outline-none transition-all focus:bg-white shadow-sm hover:shadow-md">
                            <x-input-error class="text-xs ml-4" :messages="$errors->get('email')" />
                        </div>

                         <div class="space-y-2">
                            <label class="ml-4 text-[10px] font-space-mono font-bold uppercase tracking-widest text-gray-400">Birthday</label>
                            <input type="date" name="birthday" value="{{ old('birthday', $user->birthday) }}" 
                                   class="w-full bg-white/60 border border-white focus:border-black/20 rounded-2xl px-6 py-4 font-instrument text-sm outline-none transition-all focus:bg-white shadow-sm hover:shadow-md">
                            <x-input-error class="text-xs ml-4" :messages="$errors->get('birthday')" />
                        </div>
                        
                         @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                             <div class="bg-yellow-50/80 p-4 rounded-2xl border border-yellow-100/50">
                                 <p class="text-xs text-yellow-800 mb-2">{{ __('Email unverified.') }}</p>
                                 <button form="send-verification" class="text-[10px] font-bold uppercase tracking-widest underline hover:text-yellow-900">
                                     {{ __('Resend Verification') }}
                                 </button>
                             </div>
                         @endif

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-black text-white rounded-full py-4 font-space-mono text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 hover:scale-[1.02] transition-all shadow-lg">
                                Save Update
                            </button>
                            @if (session('status') === 'profile-updated')
                                <p class="text-center mt-3 text-[10px] font-space-mono text-green-600 uppercase tracking-widest">Saved</p>
                            @endif
                        </div>
                    </form>
                </div>
                
                 <!-- SECURITY MODULE -->
                <div class="relative overflow-hidden rounded-3xl p-8 transition-all duration-500 hover:shadow-[0_20px_40px_rgba(0,0,0,0.05)]"
                     style="background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);">
                    
                     <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <h2 class="font-gloock text-xl">Security</h2>
                    </div>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                        @csrf
                        @method('put')
                        
                        <input type="password" name="current_password" placeholder="Current" required 
                               class="w-full bg-white/60 border border-white focus:border-black/20 rounded-xl px-4 py-3 font-instrument text-sm outline-none transition-all focus:bg-white shadow-sm">
                        <x-input-error class="text-xs ml-4" :messages="$errors->updatePassword->get('current_password')" />

                         <input type="password" name="password" placeholder="New Password" required 
                               class="w-full bg-white/60 border border-white focus:border-black/20 rounded-xl px-4 py-3 font-instrument text-sm outline-none transition-all focus:bg-white shadow-sm">
                        <x-input-error class="text-xs ml-4" :messages="$errors->updatePassword->get('password')" />

                         <input type="password" name="password_confirmation" placeholder="Confirm" required 
                               class="w-full bg-white/60 border border-white focus:border-black/20 rounded-xl px-4 py-3 font-instrument text-sm outline-none transition-all focus:bg-white shadow-sm">
                        <x-input-error class="text-xs ml-4" :messages="$errors->updatePassword->get('password_confirmation')" />

                        <button type="submit" class="w-full bg-white border border-gray-200 text-black rounded-full py-3 font-space-mono text-[10px] font-bold uppercase tracking-widest hover:border-black transition-all">
                            Update
                        </button>
                        
                         @if (session('status') === 'password-updated')
                            <p class="text-center mt-2 text-[10px] font-space-mono text-green-600 uppercase tracking-widest">Updated</p>
                        @endif
                    </form>

                     <!-- DANGER ZONE (Integrated) -->
                    <div class="mt-8 pt-8 border-t border-gray-200/50 text-center">
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="text-red-300 hover:text-red-600 font-space-mono text-[9px] font-bold uppercase tracking-widest transition-colors duration-300">
                            Close Account
                        </button>
                    </div>
                </div>

            </div>

             <!-- COL 2: ADDRESSES & ACTIONS (Width: 8/12) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- ADDRESS MODULE (Wide) -->
                <div class="relative overflow-hidden rounded-3xl p-8 md:p-12 min-h-[500px] flex flex-col"
                     style="background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);">
                    
                     <div class="flex justify-between items-end mb-10">
                        <div>
                             <h2 class="font-gloock text-4xl mb-2">Address Book</h2>
                             <p class="text-xs font-space-mono text-gray-400 uppercase tracking-widest">Manage Shipping</p>
                        </div>
                        <span class="bg-black text-white px-3 py-1 rounded-full text-xs font-bold font-space-mono">{{ $user->addresses->count() }}/3</span>
                    </div>

                    <!-- Address Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-grow">
                        @foreach($user->addresses as $address)
                            <div class="group relative bg-white/70 rounded-2xl p-6 border border-white shadow-sm hover:shadow-lg transition-all hover:scale-[1.02] duration-300">
                                <div class="flex justify-between items-start mb-4">
                                     <span class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center font-space-mono text-xs font-bold text-gray-400">0{{ $loop->iteration }}</span>
                                      <form method="POST" action="{{ route('profile.address.destroy', $address->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-gray-300 hover:text-red-500 transition"><span class="sr-only">Remove</span>&times;</button>
                                    </form>
                                </div>
                                <div class="space-y-1">
                                    <p class="font-instrument font-medium text-lg">{{ $address->address_line1 }}</p>
                                    @if($address->address_line2)<p class="font-instrument text-gray-500">{{ $address->address_line2 }}</p>@endif
                                    <p class="font-instrument text-sm text-gray-600">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                    <p class="font-instrument text-xs text-gray-400 uppercase tracking-widest mt-2 pt-2 border-t border-gray-100">{{ $address->country }}</p>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Add New Button / Form -->
                         @if($user->addresses->count() < 3)
                            <div x-data="{ open: false }" class="h-full min-h-[200px]">
                                <!-- Trigger -->
                                <button @click="open = !open" x-show="!open" class="group w-full h-full rounded-2xl border-2 border-dashed border-gray-300 hover:border-black flex flex-col items-center justify-center gap-2 transition-all hover:bg-white/30">
                                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </div>
                                    <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-gray-400 group-hover:text-black">Add New</span>
                                </button>

                                <!-- Form -->
                                <div x-show="open" x-cloak class="bg-white rounded-2xl p-6 border border-white shadow-lg h-full flex flex-col justify-center relative">
                                    <button @click="open = false" class="absolute top-4 right-4 text-gray-300 hover:text-black"><span class="sr-only">Close</span>&times;</button>
                                    
                                     <form method="POST" action="{{ route('profile.address.store') }}" class="space-y-3 mt-2">
                                        @csrf
                                        <input type="text" name="address_line1" required placeholder="Address Line 1" class="w-full bg-gray-50 border-0 rounded-lg px-3 py-2 text-xs font-instrument outline-none focus:ring-1 focus:ring-black">
                                        <input type="text" name="address_line2" placeholder="Unit (Opt)" class="w-full bg-gray-50 border-0 rounded-lg px-3 py-2 text-xs font-instrument outline-none focus:ring-1 focus:ring-black">
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="text" name="city" required placeholder="City" class="w-full bg-gray-50 border-0 rounded-lg px-3 py-2 text-xs font-instrument outline-none focus:ring-1 focus:ring-black">
                                            <input type="text" name="state" required placeholder="State" class="w-full bg-gray-50 border-0 rounded-lg px-3 py-2 text-xs font-instrument outline-none focus:ring-1 focus:ring-black">
                                        </div>
                                         <div class="grid grid-cols-2 gap-2">
                                            <input type="text" name="postal_code" required placeholder="Zip" class="w-full bg-gray-50 border-0 rounded-lg px-3 py-2 text-xs font-instrument outline-none focus:ring-1 focus:ring-black">
                                            <input type="text" name="country" value="United States" required class="w-full bg-gray-50 border-0 rounded-lg px-3 py-2 text-xs font-instrument outline-none focus:ring-1 focus:ring-black">
                                        </div>
                                        <button type="submit" class="w-full bg-black text-white rounded-lg py-2 font-space-mono text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800">Save</button>
                                     </form>
                                       @if($errors->has('address_limit'))
                                        <p class="text-[10px] text-red-500 mt-1">{{ $errors->first('address_limit') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- MY ORDERS MODULE (Recent Activity) -->
                <div class="relative overflow-hidden rounded-3xl p-8 transition-all duration-500 hover:shadow-[0_20px_40px_rgba(0,0,0,0.05)]"
                     style="background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);">
                    
                     <div class="flex justify-between items-center mb-8">
                        <div>
                             <h2 class="font-gloock text-3xl mb-1">Recent Activity</h2>
                             <p class="text-xs font-space-mono text-gray-500 uppercase tracking-widest">Latest Acquisitions</p>
                        </div>
                        <a href="{{ route('orders.index') }}" class="w-12 h-12 rounded-full border border-black/10 flex items-center justify-center hover:bg-black hover:text-white transition-all duration-300 group bg-white/50">
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>

                    @if($user->orders()->count() > 0)
                        <div class="space-y-3">
                             @foreach($user->orders()->latest()->take(3)->get() as $order)
                                  <a href="{{ route('orders.show', $order) }}" class="block bg-white/40 p-5 rounded-2xl border border-white hover:bg-white hover:border-white hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex justify-between items-center group">
                                       <div class="flex items-center gap-4">
                                           <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-400 group-hover:bg-black group-hover:text-white transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                           </div>
                                           <div>
                                               <span class="block font-space-mono text-[10px] text-gray-400 font-bold uppercase tracking-wider">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                               <span class="font-instrument text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                                           </div>
                                       </div>
                                       <div class="text-right">
                                            <span class="inline-block px-3 py-1 rounded-full bg-white/50 border border-white text-[10px] font-space-mono font-bold uppercase tracking-widest text-gray-600 mb-1 group-hover:border-black group-hover:text-black transition-colors">{{ $order->status }}</span>
                                            <span class="block font-gloock text-lg">{{ \App\Helpers\CurrencyHelper::format($order->total_price) }}</span>
                                       </div>
                                  </a>
                             @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-white/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="font-gloock text-2xl text-gray-300">0</span>
                            </div>
                            <p class="font-instrument italic text-gray-400">No acquisitions in your archive yet.</p>
                            <a href="{{ route('products.index') }}" class="inline-block mt-4 text-[10px] font-space-mono font-bold uppercase tracking-widest border-b border-black pb-0.5 hover:text-gray-600 transition">Browse Collection</a>
                        </div>
                    @endif
                </div>

            </div>

        </div>
        
        <!-- Confirmation Modal -->
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-8 text-center">
                @csrf
                @method('delete')
                <h2 class="text-3xl font-gloock mb-4">{{ __('Departing?') }}</h2>
                <p class="mb-6 text-sm text-gray-500 font-instrument">{{ __('Action is permanent. Confirm password.') }}</p>
                
                <input id="password" name="password" type="password" class="w-full max-w-xs mx-auto border border-gray-200 rounded-lg text-center py-2 focus:ring-1 focus:ring-red-500 mb-6 focus:border-red-500 outline-none" placeholder="Password" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mb-4" />
                
                <div class="flex justify-center gap-4">
                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-space-mono text-gray-500 hover:text-black uppercase tracking-widest hover:bg-gray-50">{{ __('Cancel') }}</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white text-xs font-space-mono hover:bg-red-700 uppercase tracking-widest font-bold shadow-md">{{ __('Delete') }}</button>
                </div>
            </form>
        </x-modal>

    </main>

    <x-footer />
    
</body>
</html>
