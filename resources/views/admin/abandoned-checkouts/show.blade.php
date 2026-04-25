@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.abandoned-checkouts.index') }}"
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Abandoned Checkouts
        </a>
        <span class="text-gray-300">/</span>
        <h1 class="text-2xl font-bold font-space-mono text-gray-800">{{ $abandonedCheckout->user_name }}</h1>
    </div>
    <form action="{{ route('admin.abandoned-checkouts.destroy', $abandonedCheckout) }}" method="POST" onsubmit="return confirm('Remove this record?')">
        @csrf @method('DELETE')
        <button type="submit" class="px-4 py-2 bg-white text-red-600 border border-red-100 text-xs font-bold font-space-mono uppercase tracking-widest hover:bg-red-50 transition rounded-sm shadow-sm">
            Remove Record
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Customer + Cart Info --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Customer Info --}}
        <div class="glass-panel p-6">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-5 font-space-mono">Customer Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1 font-space-mono">Name</label>
                    <p class="text-lg font-bold text-gray-900">{{ $abandonedCheckout->user_name }}</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1 font-space-mono">Email Address</label>
                    <a href="mailto:{{ $abandonedCheckout->user_email }}" class="text-lg text-indigo-600 hover:text-indigo-800">
                        {{ $abandonedCheckout->user_email }}
                    </a>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1 font-space-mono">Checkout Visited</label>
                    <p class="text-gray-700">{{ $abandonedCheckout->created_at->format('d M Y, h:i A') }}</p>
                    <p class="text-xs text-gray-400">{{ $abandonedCheckout->created_at->diffForHumans() }}</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1 font-space-mono">Cart Total</label>
                    <p class="text-2xl font-bold text-gray-900 font-space-mono">${{ number_format($abandonedCheckout->cart_total, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Cart Items --}}
        <div class="glass-panel p-6">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-5 font-space-mono">Items Left in Cart</h2>
            <div class="space-y-3">
            @foreach($abandonedCheckout->cart_data as $productId => $item)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-4">
                        @php
                            $imgSrc = null;
                            if (!empty($item['image'])) {
                                $imgPath = $item['image'];
                                if (\Illuminate\Support\Str::startsWith($imgPath, ['http', 'https'])) {
                                    $imgSrc = $imgPath;
                                } elseif (\Illuminate\Support\Str::startsWith($imgPath, 'images/')) {
                                    $imgSrc = asset($imgPath);
                                } else {
                                    $imgSrc = asset('storage/' . $imgPath);
                                }
                            }
                        @endphp
                        @if($imgSrc)
                            <img src="{{ $imgSrc }}"
                                 alt="{{ $item['name'] ?? 'Product' }}"
                                 class="w-16 h-16 object-cover rounded-lg bg-gray-100 border border-gray-200 flex-shrink-0">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 border border-gray-200">
                                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">{{ $item['name'] ?? 'Product #' . $productId }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Qty: {{ $item['quantity'] ?? 1 }} &nbsp;·&nbsp; ${{ number_format($item['price'] ?? 0, 2) }} each</p>
                        </div>
                    </div>
                    <p class="font-bold text-gray-900 font-space-mono ml-4">
                        ${{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}
                    </p>
                </div>
            @endforeach
            </div>
        </div>

    </div>

    {{-- Right: Send Reminder --}}
    <div class="space-y-6">

        {{-- Reminder Status --}}
        <div class="glass-panel p-6">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 font-space-mono">Reminder Status</h2>
            @if($abandonedCheckout->reminder_sent_at)
                <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-100">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-green-700">Reminder Sent</p>
                        <p class="text-xs text-green-600">{{ $abandonedCheckout->reminder_sent_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3 text-center">You can send another reminder below.</p>
            @else
                <div class="flex items-center gap-3 p-3 bg-orange-50 rounded-lg border border-orange-100">
                    <div class="w-8 h-8 bg-orange-400 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-orange-700">No Reminder Sent Yet</p>
                        <p class="text-xs text-orange-600">Send a personalized email below.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Send Reminder Email Form --}}
        <div class="glass-panel p-6">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 font-space-mono">Send Reminder Email</h2>

            @if($errors->any())
                <div class="p-3 bg-red-50 border border-red-100 rounded-lg mb-4">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.abandoned-checkouts.send-reminder', $abandonedCheckout) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-[0.15em] mb-2 font-space-mono">
                        To
                    </label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm text-gray-600">
                        {{ $abandonedCheckout->user_name }} &lt;{{ $abandonedCheckout->user_email }}&gt;
                    </div>
                </div>

                <div class="mb-5">
                    <label for="custom_message" class="block text-[10px] font-bold text-gray-500 uppercase tracking-[0.15em] mb-2 font-space-mono">
                        Personal Message <span class="text-red-400">*</span>
                    </label>
                    <textarea
                        id="custom_message"
                        name="custom_message"
                        rows="6"
                        placeholder="Write a personalized message to encourage them to complete their purchase..."
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-transparent resize-none bg-white/70 placeholder-gray-400"
                        required
                    >{{ old('custom_message', "Hi {$abandonedCheckout->user_name},\n\nWe noticed you left some beautiful items in your cart. Your selection is still waiting for you!\n\nWe'd love to help you complete your order. Feel free to reach out if you have any questions.\n\nWarm regards,\nCavari Team") }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">This message will appear in the email body.</p>
                </div>

                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-black text-white text-xs font-bold font-space-mono uppercase tracking-widest hover:bg-gray-800 active:bg-gray-900 transition rounded-sm shadow-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Send Reminder Email
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
