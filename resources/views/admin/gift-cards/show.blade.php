@extends('layouts.admin')

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.gift-cards.index') }}" class="p-2 text-gray-500 hover:text-gray-800 transition-colors bg-white/50 rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="text-3xl font-bold font-space-mono text-gray-800">Gift Card Details</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
    <!-- Card Summary Card -->
    <div class="glass-panel p-6 shadow-xl border border-indigo-100 flex flex-col justify-center items-center text-center relative overflow-hidden group">
        <div class="z-10 relative">
            <h3 class="text-indigo-400 text-[10px] font-bold uppercase tracking-widest mb-2">Gift Card Code</h3>
            <p class="text-4xl font-space-mono font-bold text-indigo-700 tracking-wider mb-4">{{ $giftCard->code }}</p>
            <div class="flex items-center justify-center gap-2">
                <span class="text-xs text-gray-500 font-bold uppercase tracking-widest">Balance:</span>
                <span class="text-2xl font-space-mono font-bold text-gray-900">${{ number_format($giftCard->balance, 2) }}</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1">Initial Balance: ${{ number_format($giftCard->initial_balance, 2) }}</p>
        </div>
        <div class="absolute right-[-30px] top-[-30px] w-32 h-32 bg-indigo-500 rounded-full mix-blend-multiply filter blur-2xl opacity-10"></div>
    </div>

    <!-- Recipient Info Card -->
    <div class="glass-panel p-6 border-l-4 border-emerald-400">
        <h3 class="text-emerald-500 text-[10px] font-bold uppercase tracking-widest mb-4">Recipient Details</h3>
        <div class="space-y-4">
            <div>
                <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold block mb-1">To:</span>
                <p class="font-bold text-gray-900 text-lg">{{ $giftCard->recipient_name }}</p>
                <p class="text-sm text-gray-500">{{ $giftCard->recipient_email }}</p>
            </div>
            <div>
                <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold block mb-1">Personal Message:</span>
                <p class="text-sm italic text-gray-700 font-instrument">{{ $giftCard->message ?: 'No message provided.' }}</p>
            </div>
        </div>
    </div>

    <!-- Sender Info Card -->
    <div class="glass-panel p-6 border-l-4 border-amber-400">
        <h3 class="text-amber-500 text-[10px] font-bold uppercase tracking-widest mb-4">Sender Details</h3>
        <div class="space-y-4">
            <div>
                <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold block mb-1">From:</span>
                <p class="font-bold text-gray-900 text-lg">{{ $giftCard->sender_name }}</p>
                <p class="text-sm text-gray-500">{{ $giftCard->sender_email }}</p>
            </div>
            <div>
                <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold block mb-1">Account Holder:</span>
                <p class="text-xs text-gray-500">
                    @if($giftCard->user)
                        {{ $giftCard->user->name }} (UID: #{{ $giftCard->user->id }})
                    @else
                        Guest Customer
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<div class="mb-6">
    <h2 class="text-xl font-bold font-space-mono text-gray-800 uppercase tracking-wider">Transaction Ledger</h2>
    <p class="text-xs text-gray-500">Audit trail of all usage on this gift card.</p>
</div>

<div class="glass-panel overflow-hidden border border-gray-100">
    <table class="min-w-full">
        <thead>
            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200 bg-white/50">
                <th class="py-4 px-6">Date</th>
                <th class="py-4 px-6 text-center">Type</th>
                <th class="py-4 px-6">Amount</th>
                <th class="py-4 px-6">OrderRef</th>
                <th class="py-4 px-6">Notes</th>
            </tr>
        </thead>
        <tbody class="text-sm divide-y divide-gray-100 bg-transparent">
            @forelse($giftCard->transactions as $tx)
            <tr class="hover:bg-white/40 transition-colors">
                <td class="py-4 px-6 text-gray-500 font-mono text-xs">
                    {{ $tx->created_at->format('M d, Y - H:i') }}
                </td>
                <td class="py-4 px-6 text-center">
                    <span class="px-2 py-1 text-[9px] font-bold uppercase tracking-widest rounded-md 
                        {{ $tx->type === 'debit' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                        {{ $tx->type }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    <span class="font-bold {{ $tx->type === 'debit' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $tx->type === 'debit' ? '-' : '+' }}${{ number_format($tx->amount, 2) }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    @if($tx->order)
                        <a href="{{ route('admin.orders.show', $tx->order_id) }}" class="text-indigo-600 font-bold hover:underline">#{{ $tx->order_id }}</a>
                    @else
                        -
                    @endif
                </td>
                <td class="py-4 px-6 text-gray-500 italic text-xs">
                    {{ $tx->notes }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-12 text-center text-gray-500 font-instrument">
                    No transactions yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
