@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between md:items-center">
    <h1 class="text-3xl font-bold font-space-mono text-gray-800 mb-4 md:mb-0">Orders</h1>
    
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.orders.index') }}" class="px-3 py-1 text-sm rounded-full {{ request('status') ? 'bg-white text-gray-700 hover:bg-gray-100' : 'bg-black text-white' }} transition-colors border border-gray-200">
            All
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} transition-colors border border-gray-200">
            Pending
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') === 'shipped' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} transition-colors border border-gray-200">
            Shipped
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') === 'delivered' ? 'bg-green-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} transition-colors border border-gray-200">
            Delivered
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') === 'cancelled' ? 'bg-red-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} transition-colors border border-gray-200">
            Cancelled
        </a>
    </div>
    <div class="mt-4 md:mt-0">
        <a href="{{ route('admin.orders.create-custom') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-colors font-space-mono text-sm uppercase inline-block">
            + Custom Order
        </a>
    </div>
</div>

<div class="glass-panel overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-white/40 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order->id) }}'">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $order->id }}
                            @if($order->is_custom)
                                <span class="ml-2 px-2 py-0.5 text-xs font-bold bg-purple-100 text-purple-800 rounded-full">Custom</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($order->is_custom && !$order->user)
                                <span class="text-purple-600 font-bold uppercase tracking-widest text-[10px]">Customized</span>
                            @else
                                {{ $order->user ? $order->user->name : 'Guest' }}
                                <div class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 font-space-mono">
                            ${{ number_format($order->total_price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-bold rounded-full 
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                   ($order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             <span class="px-2 py-1 text-xs font-bold rounded-full 
                                {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($order->payment_status === 'refunded' ? 'bg-purple-100 text-purple-800' : 
                                   'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" onclick="event.stopPropagation()">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
    </div>
</div>
</div>

@if($newOrder)
<!-- Payment Link Success Modal -->
<div x-data="{ open: true }" 
     x-show="open" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm transition-opacity duration-300"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     x-cloak>
    
    <div @click.away="open = false" 
         class="relative glass-panel bg-white p-8 max-w-lg w-full shadow-2xl border-t-4 border-indigo-600 transform transition-all"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
        
        <div class="mb-6 text-center">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-indigo-600 block mb-1">Custom Order Generated</span>
            <h2 class="text-3xl font-bold font-space-mono text-gray-800 uppercase">Payment Link Ready</h2>
            <p class="text-xs text-gray-400 font-space-mono mt-1">Order #{{ $newOrder->id }} Details</p>
        </div>

        <div class="space-y-6 pt-4 border-t border-gray-100">
            <div class="bg-gray-50 border border-gray-100 p-4 rounded-xl">
                 <div class="flex justify-between items-center mb-3">
                     <span class="text-[10px] items-center font-bold text-gray-400 uppercase tracking-widest">Grand Total</span>
                     <span class="text-xl font-bold text-black font-space-mono">${{ number_format($newOrder->total_price, 2) }}</span>
                 </div>
                 
                 <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Payment Link</div>
                 <div class="flex items-center gap-2">
                     <input type="text" readonly value="{{ route('custom_orders.pay', $newOrder->payment_link_uuid) }}" id="modal-pay-link" class="w-full bg-white border border-gray-200 rounded-lg py-2 px-3 text-sm text-blue-600 font-medium font-mono">
                     <button onclick="document.getElementById('modal-pay-link').select(); document.execCommand('copy'); alert('Link Copied!');" class="p-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                     </button>
                 </div>
            </div>

            <div class="flex flex-col gap-3">
                <button @click="open = false" class="w-full py-4 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors uppercase text-xs tracking-widest font-space-mono shadow-sm">
                    Close to View All Orders
                </button>
                <a href="{{ route('admin.orders.show', $newOrder->id) }}" class="w-full py-4 bg-indigo-50 text-indigo-600 font-bold rounded-xl hover:bg-indigo-100 transition-colors uppercase text-xs tracking-widest font-space-mono text-center">
                    Check Detailed Specs
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
