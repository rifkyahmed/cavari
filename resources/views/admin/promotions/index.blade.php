@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold font-space-mono text-gray-800">Promotions / Sales</h1>
    
    <a href="{{ route('admin.promotions.create') }}" class="px-6 py-2 bg-black text-white font-bold rounded-lg shadow hover:bg-gray-800 transition-colors font-space-mono text-sm uppercase flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Add Promotion
    </a>
</div>

<div class="glass-panel overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Type & Target</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($promotions as $promotion)
                    <tr class="hover:bg-white/40 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-bold text-gray-900">{{ $promotion->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-700 uppercase tracking-wider">
                                {{ $promotion->type }}
                            </span>
                            @if($promotion->target_ids)
                                <span class="ml-2 text-[10px] text-gray-500 max-w-[150px] truncate inline-block align-middle" title="{{ implode(', ', $promotion->target_ids) }}">
                                    IDs: {{ count($promotion->target_ids) }} item(s)
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-green-600">
                            {{ $promotion->discount_percentage }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                            {{ $promotion->start_date->format('d M') }} - {{ $promotion->end_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(!$promotion->is_active)
                                <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-gray-100 text-gray-800 uppercase tracking-widest">Paused</span>
                            @elseif(now()->between($promotion->start_date, $promotion->end_date))
                                <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-green-100 text-green-800 uppercase tracking-widest">Active</span>
                            @elseif(now()->lt($promotion->start_date))
                                <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-yellow-100 text-yellow-800 uppercase tracking-widest">Upcoming</span>
                            @else
                                <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-red-100 text-red-800 uppercase tracking-widest">Expired</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('admin.promotions.toggle', $promotion->id) }}" method="POST" class="inline-block mr-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $promotion->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }} transition-colors font-bold">
                                    {{ $promotion->is_active ? 'Pause' : 'Resume' }}
                                </button>
                            </form>

                            <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 transition-colors font-bold">Edit</a>
                            
                            <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this promotion?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors font-bold">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">No promotions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $promotions->links() }}
    </div>
</div>
@endsection
