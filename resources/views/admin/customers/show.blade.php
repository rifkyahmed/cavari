@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.customers.index') }}"
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Customers
        </a>
        <span class="text-gray-300">/</span>
        <h1 class="text-2xl font-bold font-space-mono text-gray-800">{{ $customer->name }}</h1>
    </div>
    <span class="text-xs font-space-mono text-gray-400">ID #{{ $customer->id }}</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── LEFT COLUMN ───────────────────────────────────────── --}}
    <div class="lg:col-span-1 flex flex-col gap-6">

        {{-- Profile Card --}}
        <div class="glass-panel p-6">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-3xl font-bold font-space-mono mb-3 shadow-lg">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <h2 class="text-lg font-bold text-gray-900">{{ $customer->name }}</h2>
                <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                <span class="mt-2 px-3 py-0.5 rounded-full text-xs font-semibold
                    {{ $customer->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                    {{ ucfirst($customer->role ?? 'customer') }}
                </span>
            </div>

            <div class="divide-y divide-gray-100 text-sm">
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Birthday</span>
                    <span class="font-medium text-gray-800">{{ $customer->birthday ? \Carbon\Carbon::parse($customer->birthday)->format('d M Y') : '—' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Joined</span>
                    <span class="font-medium text-gray-800">{{ $customer->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Last Order</span>
                    <span class="font-medium text-gray-800">
                        {{ $customer->orders->sortByDesc('created_at')->first()?->created_at->format('d M Y') ?? '—' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="glass-panel p-4 text-center">
                <p class="text-2xl font-bold font-space-mono text-indigo-600">{{ $totalOrders }}</p>
                <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Total Orders</p>
            </div>
            <div class="glass-panel p-4 text-center">
                <p class="text-2xl font-bold font-space-mono text-emerald-600">${{ number_format($totalSpent, 2) }}</p>
                <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Total Spent</p>
            </div>
        </div>

        {{-- Birthday Offer --}}
        @if($customer->birthday)
        <div class="glass-panel p-6 border-l-4 border-pink-400 relative overflow-hidden group">
            <div class="absolute right-[-20px] top-[-20px] w-24 h-24 bg-pink-100 rounded-full mix-blend-multiply filter blur-xl opacity-50 z-0"></div>
            <div class="relative z-10">
                <h3 class="text-sm font-bold font-space-mono text-pink-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"></path></svg>
                    Send Birthday Offer
                </h3>
                <p class="text-xs text-gray-600 mb-4 mb-4">Send a unique, exclusive birthday coupon directly to {{ $customer->name }}'s email.</p>
                
                <form action="{{ route('admin.customers.send-birthday-offer', $customer->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex gap-2">
                        <div class="w-2/5">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Type</label>
                            <select name="discount_type" class="w-full text-xs font-mono border-gray-200 rounded-md shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50 h-8 px-2" required>
                                <option value="percentage">% Off</option>
                                <option value="fixed">$ Off</option>
                            </select>
                        </div>
                        <div class="w-3/5">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Value</label>
                            <input type="number" name="discount_value" step="0.01" min="1" class="w-full text-xs font-mono border-gray-200 rounded-md shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50 h-8 px-2" placeholder="e.g. 10" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Expires On</label>
                        @php
                            $nextBday = \Carbon\Carbon::parse($customer->birthday)->year(now()->year);
                            if ($nextBday->isPast() && !$nextBday->isToday()) {
                                $nextBday->addYear();
                            }
                        @endphp
                        <input type="date" name="expiry_date" value="{{ $nextBday->copy()->addDays(7)->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}" class="w-full text-xs font-mono border-gray-200 rounded-md shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50 h-8 px-2" required>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-rose-500 text-white font-bold text-[10px] uppercase tracking-widest py-2 rounded shadow-md hover:from-pink-600 hover:to-rose-600 transition-all">
                        Generate & Email Code
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Saved Addresses --}}
        <div class="glass-panel p-6">
            <h3 class="text-sm font-bold font-space-mono text-gray-700 uppercase tracking-wider mb-4">
                Saved Addresses
            </h3>
            @forelse($customer->addresses as $address)
                <div class="mb-3 last:mb-0 p-3 bg-white/60 rounded-lg border border-gray-100 text-sm text-gray-700 leading-relaxed">
                    <p class="font-medium text-gray-900">{{ $address->address_line1 }}</p>
                    @if($address->address_line2)
                        <p>{{ $address->address_line2 }}</p>
                    @endif
                    <p>{{ $address->city }}@if($address->state), {{ $address->state }}@endif {{ $address->postal_code }}</p>
                    <p class="text-gray-500">{{ $address->country }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-400 italic">No saved addresses.</p>
            @endforelse
        </div>

    </div>

    {{-- ── RIGHT COLUMN ──────────────────────────────────────── --}}
    <div class="lg:col-span-2 flex flex-col gap-6">

        {{-- Order History --}}
        <div class="glass-panel overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold font-space-mono text-gray-700 uppercase tracking-wider">Order History</h3>
                <span class="text-xs text-gray-400">{{ $totalOrders }} order{{ $totalOrders !== 1 ? 's' : '' }}</span>
            </div>

            @forelse($customer->orders->sortByDesc('created_at') as $order)
                <div class="border-b border-gray-100 last:border-0">
                    {{-- Order Header --}}
                    <div class="px-6 py-4 bg-white/30 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="font-space-mono text-sm font-bold text-gray-800">#{{ $order->id }}</span>
                            <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            {{-- Payment Status --}}
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                @if($order->payment_status === 'paid') bg-green-100 text-green-700
                                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                            {{-- Order Status --}}
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                @if($order->status === 'delivered') bg-emerald-100 text-emerald-700
                                @elseif($order->status === 'shipped') bg-blue-100 text-blue-700
                                @elseif($order->status === 'processing') bg-indigo-100 text-indigo-700
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($order->status ?? 'pending') }}
                            </span>
                            <span class="font-bold font-space-mono text-sm text-gray-900">
                                ${{ number_format($order->total_price, 2) }}
                            </span>
                        </div>
                    </div>

                    {{-- Order Items --}}
                    @if($order->items->count())
                        <div class="px-6 py-3 space-y-2">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-3 text-sm">
                                    @if($item->product && isset($item->product->images[0]))
                                        <img src="{{ asset($item->product->images[0]) }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-10 h-10 rounded object-cover border border-gray-100 shadow-sm flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-800 truncate">
                                            {{ $item->product?->name ?? 'Product Deleted' }}
                                        </p>
                                        <p class="text-xs text-gray-400">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <span class="font-space-mono text-gray-700 font-semibold ml-auto">
                                        ${{ number_format($item->price ?? ($item->product?->price ?? 0), 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Shipping Address --}}
                    @if($order->shipping_address)
                        <div class="px-6 pb-3">
                            <p class="text-xs text-gray-400">
                                <span class="font-semibold text-gray-500">Ship to:</span>
                                {{ is_array($order->shipping_address)
                                    ? implode(', ', array_filter($order->shipping_address))
                                    : $order->shipping_address }}
                            </p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-6 py-10 text-center text-gray-400 italic">No orders yet.</div>
            @endforelse
        </div>

        {{-- Reviews --}}
        @if($customer->reviews->count())
        <div class="glass-panel p-6">
            <h3 class="text-sm font-bold font-space-mono text-gray-700 uppercase tracking-wider mb-4">
                Reviews ({{ $customer->reviews->count() }})
            </h3>
            <div class="space-y-3">
                @foreach($customer->reviews->sortByDesc('created_at') as $review)
                    <div class="p-3 bg-white/60 rounded-lg border border-gray-100">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex text-yellow-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                            <span class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</span>
                        </div>
                        @if($review->comment)
                            <p class="text-sm text-gray-700">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Form Submissions & Requests --}}
        <div class="glass-panel p-6">
            <h3 class="text-sm font-bold font-space-mono text-gray-700 uppercase tracking-wider mb-4">
                Form Submissions & Requests
            </h3>
            
            <div class="space-y-6">
                {{-- Messages / Contact Inquiries --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Messages ({{ $customer->messages->count() }})
                    </h4>
                    @forelse($customer->messages->sortByDesc('created_at') as $message)
                        <div class="mb-3 p-3 bg-white/60 rounded-lg border border-gray-100 last:mb-0">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-medium text-gray-900 text-sm">{{ $message->subject ?? 'General Inquiry' }}</span>
                                <span class="text-[10px] text-gray-400 font-space-mono">{{ $message->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-xs text-gray-600 line-clamp-2">{{ $message->message }}</p>
                            <a href="{{ route('admin.messages.show', $message) }}" class="text-[10px] text-indigo-600 hover:text-indigo-800 mt-2 inline-block font-bold uppercase tracking-wider">View Full Message</a>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic px-2">No messages from this customer.</p>
                    @endforelse
                </div>

                {{-- Customization Requests --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        Customization Requests ({{ $customer->customizationRequests->count() }})
                    </h4>
                    @forelse($customer->customizationRequests->sortByDesc('created_at') as $request)
                        <div class="mb-3 p-3 bg-white/60 rounded-lg border border-gray-100 last:mb-0">
                            <div class="flex justify-between items-start mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900 text-sm">Custom Design</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold 
                                        @if($request->status === 'completed') bg-green-100 text-green-700
                                        @elseif($request->status === 'pending') bg-yellow-100 text-yellow-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-gray-400 font-space-mono">{{ $request->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-xs text-gray-600 line-clamp-2">{{ $request->details }}</p>
                            <a href="{{ route('admin.customization-requests.show', $request) }}" class="text-[10px] text-indigo-600 hover:text-indigo-800 mt-2 inline-block font-bold uppercase tracking-wider">Manage Request</a>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic px-2">No customization requests.</p>
                    @endforelse
                </div>

                {{-- Source Requests --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Source Requests ({{ $customer->sourceRequests->count() }})
                    </h4>
                    @forelse($customer->sourceRequests->sortByDesc('created_at') as $request)
                        <div class="mb-3 p-3 bg-white/60 rounded-lg border border-gray-100 last:mb-0">
                            <div class="flex justify-between items-start mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900 text-sm">Gem Sourcing</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold 
                                        @if($request->status === 'completed') bg-green-100 text-green-700
                                        @elseif($request->status === 'pending') bg-yellow-100 text-yellow-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-gray-400 font-space-mono">{{ $request->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-xs text-gray-600 line-clamp-2">{{ $request->product_details }}</p>
                            <a href="{{ route('admin.source-requests.show', $request) }}" class="text-[10px] text-indigo-600 hover:text-indigo-800 mt-2 inline-block font-bold uppercase tracking-wider">Manage Request</a>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic px-2">No sourcing requests.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
