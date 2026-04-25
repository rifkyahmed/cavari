@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.messages.index') }}" 
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Messages
        </a>
        <span class="text-gray-300">/</span>
        <h1 class="text-2xl font-bold font-space-mono text-gray-800">Message #{{ $message->id }}</h1>
    </div>
    <div class="flex items-center gap-3">
        @if($message->user_id)
            <a href="{{ route('admin.customers.show', $message->user_id) }}" 
               class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold font-space-mono uppercase tracking-widest hover:bg-indigo-700 transition rounded-sm shadow-lg">
                View Customer Profile
            </a>
        @endif
        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this message?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-white text-red-600 border border-red-100 text-xs font-bold font-space-mono uppercase tracking-widest hover:bg-red-50 transition rounded-sm shadow-sm">
                Delete
            </button>
        </form>
    </div>
</div>

<div class="max-w-4xl">
    <div class="glass-panel p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-100">
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 font-space-mono">Sender Name</label>
                <p class="text-lg font-bold text-gray-900">{{ $message->name }}</p>
                @if($message->user_id)
                    <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wider">
                        Registered User
                    </span>
                @endif
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 font-space-mono">Email Address</label>
                <a href="mailto:{{ $message->email }}" class="text-lg text-indigo-600 hover:text-indigo-800 transition-colors">{{ $message->email }}</a>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 font-space-mono">Subject / Topic</label>
                <p class="text-lg text-gray-900 font-medium">{{ $message->subject ?? 'General Inquiry' }}</p>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 font-space-mono">Received On</label>
                <p class="text-lg text-gray-900">{{ $message->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 font-space-mono">Message Content</label>
            <div class="bg-gray-50/50 p-6 rounded-lg text-gray-800 leading-relaxed text-lg whitespace-pre-line border border-gray-100 italic font-instrument">
                "{{ $message->message }}"
            </div>
        </div>

        <div class="mt-8 flex justify-end">
             <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" 
                class="inline-flex items-center gap-2 px-8 py-4 bg-black text-white text-xs font-bold font-space-mono uppercase tracking-widest hover:bg-gray-800 transition rounded-sm shadow-xl">
                 Reply via Email
                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                 </svg>
             </a>
        </div>
    </div>
</div>
@endsection
