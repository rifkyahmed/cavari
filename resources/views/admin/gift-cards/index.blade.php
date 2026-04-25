@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold font-space-mono text-gray-800">Gift Card Management</h1>
    <p class="text-gray-500">Track and manage gift card balances and usage.</p>
</div>

<div class="glass-panel overflow-hidden">
    <table class="min-w-full">
        <thead>
            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200 bg-white/50">
                <th class="py-4 px-6 text-center">Batch</th>
                <th class="py-4 px-6">Code</th>
                <th class="py-4 px-6">Recipient</th>
                <th class="py-4 px-6">Balance</th>
                <th class="py-4 px-6 text-center">Status</th>
                <th class="py-4 px-6 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm divide-y divide-gray-100 bg-transparent">
            @forelse($giftCards as $card)
            <tr class="hover:bg-white/40 transition-colors">
                <td class="py-4 px-6 text-center">
                    <span class="text-gray-400 font-mono text-xs">{{ $card->id }}</span>
                </td>
                <td class="py-4 px-6">
                    <span class="font-space-mono font-bold text-indigo-600">{{ $card->code }}</span>
                    <div class="text-[10px] text-gray-400">Purchased: {{ $card->created_at->format('M d, Y') }}</div>
                </td>
                <td class="py-4 px-6">
                    <div class="font-medium text-gray-900">{{ $card->recipient_name }}</div>
                    <div class="text-xs text-gray-500">{{ $card->recipient_email }}</div>
                </td>
                <td class="py-4 px-6">
                    <div class="font-bold text-gray-900">${{ number_format($card->balance, 2) }}</div>
                    <div class="text-[10px] text-gray-400">Total: ${{ number_format($card->initial_balance, 2) }}</div>
                </td>
                <td class="py-4 px-6 text-center">
                    @if($card->is_active && $card->balance > 0)
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-green-100 text-green-700">Active</span>
                    @elseif($card->balance <= 0)
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-gray-100 text-gray-600">Redeemed</span>
                    @else
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-red-100 text-red-700">Inactive</span>
                    @endif
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.gift-cards.show', $card->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View History">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </a>
                        <form action="{{ route('admin.gift-cards.destroy', $card->id) }}" method="POST" onsubmit="return confirm('Archive this gift card?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-12 text-center text-gray-500 font-instrument">
                    No gift cards found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($giftCards->hasPages())
    <div class="p-6 border-t border-gray-100">
        {{ $giftCards->links() }}
    </div>
    @endif
</div>
@endsection
