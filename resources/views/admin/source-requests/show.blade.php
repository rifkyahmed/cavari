@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.source-requests.index') }}" 
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Source Requests
        </a>
        <span class="text-gray-300">/</span>
        <h1 class="text-2xl font-bold font-space-mono text-gray-800">Source Request #{{ $sourceRequest->id }}</h1>
    </div>
    <div class="flex items-center gap-3">
        @if($sourceRequest->user_id)
            <a href="{{ route('admin.customers.show', $sourceRequest->user_id) }}" 
               class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold font-space-mono uppercase tracking-widest hover:bg-indigo-700 transition rounded-sm shadow-lg">
                View Customer Profile
            </a>
        @endif
        <form action="{{ route('admin.source-requests.update', $sourceRequest) }}" method="POST">
            @csrf
            @method('PUT')
            <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-sm px-4 py-2 text-xs font-bold font-space-mono uppercase tracking-widest outline-none focus:border-indigo-500 transition shadow-sm">
                <option value="pending" {{ $sourceRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $sourceRequest->status === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ $sourceRequest->status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $sourceRequest->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </form>
    </div>
</div>

<div class="max-w-4xl">
    <div class="glass-panel p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-100">
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 font-space-mono">Customer Name</label>
                <p class="text-lg font-bold text-gray-900">{{ $sourceRequest->name }}</p>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 font-space-mono">Email Address</label>
                <a href="mailto:{{ $sourceRequest->email }}" class="text-lg text-indigo-600 hover:text-indigo-800 transition-colors">{{ $sourceRequest->email }}</a>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 font-space-mono">Request Status</label>
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold font-space-mono uppercase tracking-widest
                    {{ $sourceRequest->status === 'completed' ? 'bg-green-100 text-green-700' : 
                       ($sourceRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                       ($sourceRequest->status === 'processing' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600')) }}">
                    {{ $sourceRequest->status }}
                </span>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 font-space-mono">Submitted On</label>
                <p class="text-lg text-gray-900">{{ $sourceRequest->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 font-space-mono">Requested Gemstone Details</label>
            <div class="bg-indigo-50/30 p-6 rounded-lg text-gray-800 leading-relaxed text-lg whitespace-pre-line border border-indigo-100 font-instrument">
                {{ $sourceRequest->product_details }}
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-4">
             <a href="mailto:{{ $sourceRequest->email }}?subject=Re: Your Gem Sourcing Request #{{ $sourceRequest->id }}" 
                class="inline-flex items-center gap-2 px-8 py-4 bg-black text-white text-xs font-bold font-space-mono uppercase tracking-widest hover:bg-gray-800 transition rounded-sm shadow-xl">
                 Contact Customer
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                 </svg>
             </a>
        </div>
    </div>
</div>
@endsection
