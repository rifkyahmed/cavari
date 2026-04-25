@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold font-space-mono text-gray-800">Products</h1>
    
    <div class="flex space-x-4">
        <form action="{{ route('admin.products.destroyAll') }}" method="POST" onsubmit="return confirm('WARNING: This will delete ALL products! Are you absolutely sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-bold rounded-lg shadow transition-colors font-space-mono text-sm uppercase">
                Delete All
            </button>
        </form>
        
        <a href="{{ route('admin.products.create') }}" class="px-6 py-2 bg-black text-white font-bold rounded-lg shadow hover:bg-gray-800 transition-colors font-space-mono text-sm uppercase flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add Product
        </a>
    </div>
</div>

<div class="glass-panel overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono max-w-[200px]">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Selling Price</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Cost Price</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Video</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Sales</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr class="hover:bg-white/40 transition-colors {{ $product->is_hidden ? 'opacity-60 bg-gray-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(isset($product->images) && count($product->images) > 0)
                                <img src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded object-cover shadow-sm">
                            @else
                                <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 max-w-[200px]">
                            <div class="text-sm font-medium text-gray-900 break-words" title="{{ $product->name }}">{{ $product->name }}</div>
                            <div class="text-xs text-gray-500 truncate mt-1">{{ Str::limit($product->description, 40) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold font-space-mono text-green-600">
                            ${{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-space-mono">
                            ${{ number_format(max($product->cost_price ?? 0, ($product->gold_cost_price ?? 0) + ($product->gem_cost_price ?? 0)), 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->stock }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->video)
                                @php
                                    $isCompressed = str_contains(strtolower($product->video), 'compressed') || str_contains(strtolower($product->video), 'cloudinary');
                                @endphp
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-green-600 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                                        Active
                                    </span>
                                    <span class="text-[10px] text-gray-400 uppercase tracking-tighter">
                                        {{ $isCompressed ? 'Compressed' : 'Original' }}
                                    </span>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">None</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-space-mono">
                            <span class="font-bold text-gray-800">{{ $product->total_sold ?? 0 }}</span>
                            <span class="text-[10px] text-gray-400 block uppercase tracking-tight">Total Sold</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $isDead = $product->stock > 0 && 
                                          $product->created_at < now()->subDays(90) && 
                                          ($product->last_90_sold ?? 0) <= 0;
                                          
                                $isWellMoving = ($product->recent_sold ?? 0) >= 2;
                            @endphp
                            
                            @if($isWellMoving)
                                <span class="px-2 py-1 inline-flex text-[10px] uppercase tracking-widest leading-4 font-bold rounded-full bg-emerald-100 text-emerald-800 shadow-sm border border-emerald-200">
                                    Well Moving
                                </span>
                            @elseif($isDead)
                                <span class="px-2 py-1 inline-flex text-[10px] uppercase tracking-widest leading-4 font-bold rounded-full bg-rose-100 text-rose-800 shadow-sm border border-rose-200">
                                    Dead Stock
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-[10px] uppercase tracking-widest leading-4 font-bold rounded-full bg-gray-100 text-gray-600">
                                    Standard
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('admin.products.toggle-visibility', $product->id) }}" method="POST" class="inline-block mr-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $product->is_hidden ? 'text-green-600' : 'text-gray-500' }} hover:underline transition-colors font-bold">
                                    {{ $product->is_hidden ? 'Show' : 'Hide' }}
                                </button>
                            </form>

                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 transition-colors font-bold">Edit</a>
                            
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors font-bold">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-10 text-center text-gray-500 italic">No products found. Start adding some!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $products->links() }}
    </div>
</div>
@endsection
