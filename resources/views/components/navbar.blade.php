<header class="fixed top-0 w-full z-[100] transition-all duration-500" id="main-header">
    @php
        $announcementEnabled = \App\Models\Setting::get('announcement_enabled', '0');
        $announcementText = \App\Models\Setting::get('announcement_text', '');
    @endphp

    @if($announcementEnabled === '1' && !empty($announcementText))
        <div class="bg-black text-white text-center py-2.5 px-4 font-space-mono text-[10px] font-bold uppercase tracking-widest w-full">
            {{ $announcementText }}
        </div>
    @endif

    <div class="mx-3 sm:mx-6 md:mx-8 mt-3 md:mt-4 px-3 sm:px-6 md:px-12 py-4 flex justify-between items-center transition-all duration-500" id="nav-container" style="border: none; outline: none;">
        
        <!-- Logo (Top Left) -->
        <a href="{{ route('home') }}" class="flex-shrink-0 z-50">
            <!-- Logo Image -->
            <img src="{{ asset('images/cavarilogo.png') }}" class="h-10 md:h-12 w-auto object-contain" alt="CAVARI">
        </a>

        <!-- Middle Nav (Desktop) -->
        <nav class="hidden lg:flex absolute left-1/2 transform -translate-x-1/2 space-x-12 h-full items-center">

            
            <!-- Shop Dropdown Group -->
            <div class="relative group h-full flex items-center">
                <a href="{{ route('products.index') }}" class="font-space-mono font-medium text-sm tracking-widest uppercase hover:text-gray-600 transition {{ request()->routeIs('products.*') || request()->routeIs('shop.*') ? 'text-gray-900 border-b border-black' : '' }} py-2 cursor-pointer">
                    Shop
                </a>
                
                <!-- Glass Dropdown -->
                <div class="absolute top-[70%] left-1/2 transform -translate-x-1/2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                    <div class="w-48 overflow-hidden flex flex-col p-2 gap-1 text-center"
                         style="background: rgba(255, 255, 255, 0.35); border-radius: 16px; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.23);">
                        <a href="{{ route('shop.gems') }}" class="font-space-mono text-xs font-bold uppercase tracking-widest text-black hover:bg-white/40 py-3 rounded-lg transition-colors">
                            Loose Gems
                        </a>
                        <a href="{{ route('shop.jewelry') }}" class="font-space-mono text-xs font-bold uppercase tracking-widest text-black hover:bg-white/40 py-3 rounded-lg transition-colors">
                            Jewelry
                        </a>
                        <a href="{{ route('shop.custom-design') }}" class="font-space-mono text-xs font-bold uppercase tracking-widest text-black hover:bg-white/40 py-3 rounded-lg transition-colors">
                            Custom Design
                        </a>
                        <a href="{{ route('gift-cards.index') }}" class="font-space-mono text-xs font-bold uppercase tracking-widest text-black hover:bg-white/40 py-3 rounded-lg transition-colors">
                            Gift Cards
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ route('about') }}" class="font-space-mono font-medium text-sm tracking-widest uppercase hover:text-gray-600 transition {{ request()->routeIs('about') ? 'text-gray-900 border-b border-black' : '' }}">About</a>
            <a href="{{ route('journal.index') }}" class="font-space-mono font-medium text-sm tracking-widest uppercase hover:text-gray-600 transition {{ request()->routeIs('journal.*') ? 'text-gray-900 border-b border-black' : '' }}">Journal</a>
            <a href="{{ route('contact') }}" class="font-space-mono font-medium text-sm tracking-widest uppercase hover:text-gray-600 transition {{ request()->routeIs('contact') ? 'text-gray-900 border-b border-black' : '' }}">Contact</a>
        </nav>

        <!-- Right Icons (Cart & Profile & Mobile Toggle) -->
        <div class="flex items-center space-x-3 md:space-x-4">
            
            <!-- Cart Icon -->
            <a href="{{ route('cart.index') }}" class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition relative shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-600 rounded-full border-2 border-white"></span>
                @endif
            </a>
            
            <!-- User Icon -->
            @auth
                <div class="relative group">
                    <button class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>
                    
                    <!-- Profile Dropdown -->
                    <div class="absolute top-[120%] right-0 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 pt-2">
                         <div class="w-40 overflow-hidden flex flex-col p-2 gap-1 text-center"
                              style="background: rgba(255, 255, 255, 0.35); border-radius: 16px; box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.23);">
                             
                             <a href="{{ route('profile.edit') }}" class="font-space-mono text-xs font-bold uppercase tracking-widest text-black hover:bg-white/40 py-3 rounded-lg transition-colors">
                                 Profile
                             </a>
                             <a href="{{ route('wishlist.index') }}" class="font-space-mono text-xs font-bold uppercase tracking-widest text-black hover:bg-white/40 py-3 rounded-lg transition-colors">
                                 Wishlist
                             </a>
                             
                             <!-- Authentication -->
                             <form method="POST" action="{{ route('logout') }}" class="w-full">
                                 @csrf
                                 <button type="submit" class="w-full font-space-mono text-xs font-bold uppercase tracking-widest text-black hover:bg-white/40 py-3 rounded-lg transition-colors">
                                     Logout
                                 </button>
                             </form>
                         </div>
                    </div>
                </div>
            @else
                <button onclick="openAuthModal()" class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>
            @endauth

            <!-- Mobile Menu Toggle -->
            <button id="mobile-menu-btn" class="lg:hidden w-10 h-10 rounded-full bg-black text-white flex items-center justify-center hover:bg-gray-800 transition shadow-lg z-[60]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
</header>

<!-- Mobile Menu (Moved outside header for z-index/blur fix) -->
<div id="mobile-menu" class="fixed inset-0 z-[1000] invisible pointer-events-none transition-all duration-500">
    <!-- Backdrop -->
    <div id="mobile-menu-backdrop" class="absolute inset-0 bg-black/10 opacity-0 transition-opacity duration-500" 
         style="backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);"></div>
    
    <div id="mobile-menu-content" class="absolute top-0 right-0 w-[85%] max-w-sm h-[100dvh] bg-white shadow-2xl translate-x-full transition-transform duration-500 ease-[cubic-bezier(0.23,1,0.32,1)] p-8 flex flex-col">
        <div class="flex justify-end items-center mb-12">
            <button id="mobile-menu-close" class="text-black hover:opacity-50 transition p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <nav class="flex flex-col space-y-6">
            <a href="{{ route('home') }}" class="font-gloock text-4xl text-black hover:italic transition-all {{ request()->routeIs('home') ? 'italic underline decoration-1 underline-offset-8' : '' }}">Home</a>
            <a href="{{ route('products.index') }}" class="font-gloock text-4xl text-black hover:italic transition-all {{ request()->routeIs('products.*') ? 'italic underline decoration-1 underline-offset-8' : '' }}">Shop</a>
            <a href="{{ route('about') }}" class="font-gloock text-4xl text-black hover:italic transition-all {{ request()->routeIs('about') ? 'italic underline decoration-1 underline-offset-8' : '' }}">About</a>
            <a href="{{ route('journal.index') }}" class="font-gloock text-4xl text-black hover:italic transition-all {{ request()->routeIs('journal.*') ? 'italic underline decoration-1 underline-offset-8' : '' }}">Journal</a>
            <a href="{{ route('contact') }}" class="font-gloock text-4xl text-black hover:italic transition-all {{ request()->routeIs('contact') ? 'italic underline decoration-1 underline-offset-8' : '' }}">Contact</a>
        </nav>
        
        <div class="mt-auto pt-12 border-t border-black/5">
             <div class="flex flex-col gap-1 mb-8">
                 <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4">Socials</span>
                 <div class="flex gap-6">
                    <a href="#" class="text-black hover:text-gray-500 transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069-3.204 0-3.584-.012-4.849-.069-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="text-black hover:text-gray-500 transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.791-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="text-black hover:text-gray-500 transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                 </div>
             </div>
             <p class="font-space-mono text-[9px] uppercase tracking-widest text-gray-400">© 2026 Cavari Gemstones</p>
        </div>
    </div>
</div>

<style>
    /* Full Page Blur and Scroll Lock */
    body.menu-is-open #nav-container,
    body.menu-is-open main,
    body.menu-is-open section,
    body.menu-is-open footer,
    body.menu-is-open [class*="fixed"]:not(#mobile-menu),
    body.menu-is-open [id*="whatsapp-fab"] {
        filter: blur(8px);
        pointer-events: none;
        transition: filter 0.5s ease-out;
        user-select: none;
    }
    
    /* Ensure only the menu is sharp */
    #mobile-menu {
        filter: none !important;
    }
    
    /* Cross-browser position:fixed support for scroll lock */
    body.menu-is-open {
        position: fixed;
        width: 100%;
        height: 100dvh;
        overflow: hidden !important;
    }
</style>


<x-auth-modal />
<x-whatsapp-fab />
<x-birthday-popup />

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const header = document.getElementById('main-header');
        const navContainer = document.getElementById('nav-container');
        let lastScrollTop = 0;
        const delta = 10;

        // Initialize with no border to prevent flash
        navContainer.style.border = 'none';
        navContainer.style.outline = 'none';
        navContainer.style.boxShadow = 'none';

        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Logic for Show/Hide
            if (Math.abs(lastScrollTop - scrollTop) <= delta && scrollTop > 0) return;

            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // Scroll Down - Hide header
                header.style.transform = 'translateY(-120%)';
            } else {
                // Scroll Up or near top - Show header
                header.style.transform = 'translateY(0)';
            }

            // Logic for Glass Effect vs Transparent
            if (scrollTop > 50) {
                // Scrolled state - Apply custom glass effect with rounded corners matches dropdown
                navContainer.style.background = 'rgba(255, 255, 255, 0.35)';
                navContainer.style.borderRadius = '16px';
                navContainer.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.1)';
                navContainer.style.backdropFilter = 'blur(20px)';
                navContainer.style.webkitBackdropFilter = 'blur(20px)';
                navContainer.style.border = '1px solid rgba(255, 255, 255, 0.23)';
            } else {
                // Top state - Completely transparent with no borders
                navContainer.style.background = 'transparent';
                navContainer.style.borderRadius = '0px';
                navContainer.style.boxShadow = 'none';
                navContainer.style.backdropFilter = 'none';
                navContainer.style.webkitBackdropFilter = 'none';
                // Explicitly set to transparent, not black
                navContainer.style.border = '1px solid transparent';
            }

            lastScrollTop = Math.max(scrollTop, 0);
        });

        // Mobile Menu Logic
        const menuBtn = document.getElementById('mobile-menu-btn');
        const menuClose = document.getElementById('mobile-menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuBackdrop = document.getElementById('mobile-menu-backdrop');
        const menuContent = document.getElementById('mobile-menu-content');

        function openMenu() {
            mobileMenu.classList.remove('invisible');
            mobileMenu.classList.add('pointer-events-auto');
            menuBackdrop.classList.add('opacity-100');
            menuContent.classList.remove('translate-x-full');
            document.body.classList.add('menu-is-open');
        }

        function closeMenu() {
            menuBackdrop.classList.remove('opacity-100');
            menuContent.classList.add('translate-x-full');
            document.body.classList.remove('menu-is-open');
            setTimeout(() => {
                mobileMenu.classList.add('invisible');
                mobileMenu.classList.remove('pointer-events-auto');
            }, 500);
        }

        menuBtn?.addEventListener('click', openMenu);
        menuClose?.addEventListener('click', closeMenu);
        menuBackdrop?.addEventListener('click', closeMenu);

        @if(session('open_auth_modal'))

            openAuthModal();
            @if(session('auth_mode'))
                toggleAuthMode('{{ session('auth_mode') }}');
            @endif
        @endif
    });
</script>
