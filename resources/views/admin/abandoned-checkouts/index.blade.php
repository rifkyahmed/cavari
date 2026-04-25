@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold font-space-mono text-gray-800">Abandoned Checkouts</h1>
        <p class="text-sm text-gray-500 mt-1">Users who reached checkout but didn't complete their purchase.</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-bold rounded-full font-space-mono">
            {{ $checkouts->total() }} records
        </span>
    </div>
</div>

<div class="glass-panel overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Cart Value</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Visited</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Reminder</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($checkouts as $checkout)
                    <tr class="hover:bg-white/40 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.abandoned-checkouts.show', $checkout->id) }}'">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ $checkout->user_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $checkout->user_email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 font-space-mono">
                            ${{ number_format($checkout->cart_total, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ count($checkout->cart_data) }} item(s)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $checkout->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($checkout->reminder_sent_at)
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Sent {{ $checkout->reminder_sent_at->diffForHumans() }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-700">Not Sent</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" onclick="event.stopPropagation()">
                            <a href="{{ route('admin.abandoned-checkouts.show', $checkout->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3">View & Send Mail</a>
                            <form action="{{ route('admin.abandoned-checkouts.destroy', $checkout->id) }}" method="POST" class="inline" onsubmit="return confirm('Remove this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <svg class="w-12 h-12 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span class="text-lg font-medium font-space-mono">No abandoned checkouts</span>
                                <span class="text-sm">All customers who visited checkout have completed their purchase.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200">
        {{ $checkouts->links() }}
    </div>
</div>
@endsection
