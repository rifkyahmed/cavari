@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-3xl font-bold font-space-mono text-gray-800">Order #{{ $order->id }}</h1>
        @if($order->is_custom)
            <span class="px-3 py-1 text-sm font-bold bg-purple-100 text-purple-800 rounded-full">Custom</span>
        @endif
        <span class="px-3 py-1 text-sm font-bold rounded-full 
            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
               ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
               ($order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>
    
    <div class="flex space-x-2">
        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="px-4 py-2 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-900 transition-colors font-space-mono text-sm uppercase flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Invoice
        </a>
        
        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-bold rounded-lg transition-colors font-space-mono text-sm uppercase">Delete</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Items -->
    <div class="lg:col-span-2 space-y-6">
        <div class="glass-panel p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-space-mono uppercase">Order Items</h3>
            <div class="overflow-x-auto">
                @if($order->is_custom && $order->payment_status !== 'paid')
                    <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg flex items-center justify-between mb-6">
                        <div>
                            <span class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Payment Link to Send to Customer</span>
                            <code class="text-sm bg-white px-2 py-1 rounded border border-gray-200 text-blue-600 select-all">{{ route('custom_orders.pay', $order->payment_link_uuid) }}</code>
                        </div>
                        <button onclick="navigator.clipboard.writeText('{{ route('custom_orders.pay', $order->payment_link_uuid) }}'); alert('Link copied!');" class="px-4 py-2 bg-black text-white text-xs font-bold rounded hover:bg-gray-800 uppercase tracking-widest">
                            Copy
                        </button>
                    </div>
                @endif
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $images = $item->product ? $item->product->images : ($item->custom_details['images'] ?? []);
                                            $itemName = $item->product ? $item->product->name : ($item->custom_name ?? 'Order Item');
                                            $imageUrl = null; 

                                            if (is_array($images) && count($images) > 0) {
                                                $mainImage = $images[0];
                                                if ($mainImage) {
                                                    if (\Illuminate\Support\Str::startsWith($mainImage, ['http', 'https'])) {
                                                        $imageUrl = $mainImage;
                                                    } elseif (\Illuminate\Support\Str::startsWith($mainImage, ['/storage/', 'storage/'])) {
                                                        $imageUrl = asset($mainImage);
                                                    } elseif (\Illuminate\Support\Str::startsWith($mainImage, 'images/')) {
                                                        $imageUrl = asset($mainImage);
                                                    } else {
                                                        $imageUrl = asset('storage/' . $mainImage);
                                                    }
                                                }
                                            }
                                        @endphp
                                        
                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}" alt="{{ $itemName }}" class="w-12 h-12 object-cover rounded mr-3 bg-gray-50 border border-gray-100">
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded mr-3 flex items-center justify-center text-gray-400 border border-gray-200">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $itemName }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if($item->product)
                                                @if($item->product->metal) <span class="mr-2">Metal: {{ $item->product->metal }}</span> @endif
                                                @if($item->product->gemstone_type) <span class="mr-2">Gem: {{ $item->product->gemstone_type }}</span> @endif
                                                @if($item->product->gold_weight) <span class="mr-2">Gold: {{ $item->product->gold_weight }}g</span> @endif
                                                @if($item->product->gem_weight) <span class="mr-2">Gem: {{ $item->product->gem_weight }}ct</span> @endif
                                            @elseif($item->custom_details)
                                                @php $details = $item->custom_details; @endphp
                                                @if(!empty($details['product_type'])) <span class="mr-2">{{ ucfirst(str_replace('_', ' ', $details['product_type'])) }}</span> @endif
                                                @if(!empty($details['gold_weight'])) <span class="mr-2">Gold: {{ $details['gold_weight'] }}g</span> @endif
                                                @if(!empty($details['gem_weight'])) <span class="mr-2">Gem: {{ $details['gem_weight'] }}ct</span> @endif
                                                @if(!empty($details['size'])) <span class="mr-2">Size/Origin: {{ $details['size'] }}</span> @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                ${{ number_format($item->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                ${{ number_format($item->price * $item->quantity, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50/50">
                        @php
                            $subtotal = $order->items->sum(function($item) {
                                return $item->price * $item->quantity;
                            });
                            $discount = $order->discount ?? 0;
                        @endphp
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-500 uppercase text-xs">Subtotal</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 font-space-mono">${{ number_format($subtotal, 2) }}</td>
                        </tr>
                        @if($discount > 0)
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-bold text-green-500 uppercase text-xs">Discount Applied</td>
                            <td class="px-6 py-3 text-right font-bold text-green-500 font-space-mono">-${{ number_format($discount, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->gift_card_amount > 0)
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right font-bold text-indigo-500 uppercase text-xs">
                                Gift Card: 
                                <a href="{{ route('admin.gift-cards.show', $order->gift_card_id) }}" class="underline">{{ $order->giftCard->code ?? 'View Card' }}</a>
                            </td>
                            <td class="px-6 py-3 text-right font-bold text-indigo-500 font-space-mono">-${{ number_format($order->gift_card_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="bg-gray-100/50">
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-900 uppercase">Grand Total</td>
                            <td class="px-6 py-4 text-right font-bold text-xl text-black font-space-mono">${{ number_format($order->total_price, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <!-- Status Update -->
        <div class="glass-panel p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-space-mono uppercase">Update Status</h3>
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                @csrf
                @method('PUT')
                
                <div>
                     <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                     <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                 <div>
                     <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                     <select name="payment_status" id="payment_status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-colors font-space-mono text-sm uppercase">
                        Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer Details -->
    <div class="space-y-6">
        <div class="glass-panel p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 font-space-mono uppercase">Customer Details</h3>
            
            <div class="space-y-4">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Information</span>
                    @if($order->user)
                        <p class="font-medium text-gray-900 mt-1">{{ $order->user->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $order->user->email }}</p>
                    @else
                        <p class="font-medium text-gray-900 mt-1">Guest User</p>
                    @endif
                </div>

                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Shipping Address</span>
                     <p class="text-gray-700 text-sm mt-1 whitespace-pre-line">{{ $order->shipping_address }}</p>
                </div>
                
                <div>
                     <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Order Date</span>
                     <p class="text-gray-700 text-sm mt-1">{{ $order->created_at->format('F j, Y g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
