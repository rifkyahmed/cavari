@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.coupons.index') }}" class="text-gray-500 hover:text-black transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7 M10 19l-7-7 7-7"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-3xl font-bold font-space-mono text-gray-800">Edit Coupon</h1>
    </div>
</div>

<div class="glass-panel p-8 max-w-2xl">
    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            {{-- Code --}}
            <div>
                <label for="code" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Coupon Code</label>
                <input type="text" name="code" id="code" value="{{ old('code', $coupon->code) }}" required 
                       class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument"
                       placeholder="e.g. SUMMER2026">
                @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Discount Type & Value --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="discount_type" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Discount Type</label>
                    <select name="discount_type" id="discount_type" required 
                            class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument appearance-none">
                        <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount ($)</option>
                    </select>
                    @error('discount_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="discount_value" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Discount Value</label>
                    <input type="number" step="0.01" name="discount_value" id="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" required 
                           class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument">
                    @error('discount_value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Expiry Date & Usage Limit --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="expiry_date" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Expiry Date (Optional)</label>
                    <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $coupon->expiry_date ? $coupon->expiry_date->format('Y-m-d') : '') }}" 
                           class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument">
                    @error('expiry_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="usage_limit" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Usage Limit (Optional)</label>
                    <input type="number" min="1" step="1" name="usage_limit" id="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" 
                           class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument">
                    @error('usage_limit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-4">
                <a href="{{ route('admin.coupons.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-bold rounded-sm hover:bg-gray-50 transition-colors font-space-mono text-xs uppercase tracking-widest">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-sm shadow hover:bg-indigo-700 transition-colors font-space-mono text-xs uppercase tracking-widest">
                    Update Coupon
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
