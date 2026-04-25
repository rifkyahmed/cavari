<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/cavarilogo.png') }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Gloock&family=Instrument+Sans:wght@400;500;600&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
        }
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Custom Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent; 
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2); 
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.4); 
        }
    </style>
</head>
<body class="admin-panel font-sans antialiased text-gray-900 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">
    
    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen bg-transparent">
        
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-50 w-64 h-screen transition duration-300 transform glass-sidebar lg:translate-x-0 flex flex-col overflow-hidden">
            <div class="flex items-center justify-center mt-8 cursor-default">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-gray-800 font-space-mono">CAVARI ADMIN</span>
                </div>
            </div>

            <!-- Scrollable Navigation Area -->
            <nav class="mt-10 px-4 space-y-2 flex-1 overflow-y-auto overscroll-contain min-h-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="mx-3 font-medium">Dashboard</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                </div>

                <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="mx-3 font-medium">Products</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span class="mx-3 font-medium">Categories</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span class="mx-3 font-medium">Orders</span>
                </a>

                <a href="{{ route('admin.customers.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.customers.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="mx-3 font-medium">Customers</span>
                </a>

                <a href="{{ route('admin.abandoned-checkouts.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.abandoned-checkouts.*') ? 'bg-white/60 text-orange-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="mx-3 font-medium">Abandoned Checkouts</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Marketing</p>
                </div>

                <a href="{{ route('admin.journals.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.journals.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <span class="mx-3 font-medium">Journals</span>
                </a>

                <a href="{{ route('admin.coupons.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.coupons.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    <span class="mx-3 font-medium">Coupons</span>
                </a>

                <a href="{{ route('admin.gift-cards.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.gift-cards.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="mx-3 font-medium">Gift Cards</span>
                </a>

                <a href="{{ route('admin.promotions.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.promotions.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    <span class="mx-3 font-medium">Offers & Sales</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Communication</p>
                </div>

                <a href="{{ route('admin.messages.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.messages.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="mx-3 font-medium">Messages</span>
                </a>

                <a href="{{ route('admin.customization-requests.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.customization-requests.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    <span class="mx-3 font-medium">Customization</span>
                </a>

                <a href="{{ route('admin.source-requests.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.source-requests.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span class="mx-3 font-medium">Source Requests</span>
                </a>

                 <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Reports</p>
                </div>

                <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.reviews.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    <span class="mx-3 font-medium">Product Reviews</span>
                </a>

                <a href="{{ route('admin.website-reviews.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.website-reviews.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <span class="mx-3 font-medium">Website Reviews</span>
                </a>

                <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.analytics.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span class="mx-3 font-medium">Analytics</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">System</p>
                </div>

                <a href="{{ route('admin.settings.edit') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-white/50 hover:text-gray-900 rounded-xl transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-white/60 text-indigo-600 shadow-sm' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="mx-3 font-medium">Settings</span>
                </a>
            </nav>
            
             <!-- Logout user -->
             <div class="p-4 border-t border-white/30 bg-white/30 backdrop-blur-md">
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-3 text-red-700 hover:bg-red-50 hover:text-red-900 rounded-xl transition-colors w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="mx-3 font-medium">Logout</span>
                    </button>
                 </form>
             </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64">
            <!-- Top bar mobile -->
            <header class="flex items-center justify-between px-6 py-4 glass-panel lg:hidden m-4">
                <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="font-space-mono font-bold text-lg">CAVARI ADMIN</div>
                <div></div>
            </header>

            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="glass-panel p-4 mb-6 border-l-4 border-green-500 text-green-700 relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                     <div class="glass-panel p-4 mb-6 border-l-4 border-red-500 text-red-700 relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    @include('partials.admin-scroll-script')
    @stack('scripts')
</body>
</html>
