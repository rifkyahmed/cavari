@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.promotions.index') }}" class="text-gray-500 hover:text-black transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7 M10 19l-7-7 7-7"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-3xl font-bold font-space-mono text-gray-800">Add Promotion</h1>
    </div>
</div>

<div class="glass-panel p-8 max-w-2xl" x-data="{ type: '{{ old('type', 'global') }}' }">
    <form action="{{ route('admin.promotions.store') }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Promotion Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                       class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument"
                       placeholder="e.g. Valentine's Day Sale">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Type & Target --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Discount Type</label>
                    <select name="type" id="type" required x-model="type"
                            class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument appearance-none">
                        <option value="global">Global (All Products)</option>
                        <option value="category">Category Specific</option>
                        <option value="product">Product Specific</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div x-show="type === 'category'" x-cloak>
                    <label for="category_ids" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Select Categories</label>
                    <select name="category_ids[]" id="category_ids" multiple
                            class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument h-32 select-tom">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ is_array(old('target_ids')) && in_array($category->id, old('target_ids')) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1 font-instrument">Hold Ctrl/Cmd to select multiple options.</p>
                     @error('target_ids')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div x-show="type === 'product'" x-cloak>
                    <label for="product_ids" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Select Products</label>
                    <select name="product_ids[]" id="product_ids" multiple
                            class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument h-32 select-tom">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ is_array(old('target_ids')) && in_array($product->id, old('target_ids')) ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1 font-instrument">Hold Ctrl/Cmd to select multiple options.</p>
                     @error('target_ids')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Discount --}}
            <div>
                <label for="discount_percentage" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Discount Percentage (%)</label>
                <input type="number" step="0.01" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage') }}" required 
                       class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument"
                       placeholder="e.g. 15">
                @error('discount_percentage')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Start Date</label>
                    <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                           class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument">
                    @error('start_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">End Date</label>
                    <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                           class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument">
                    @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-4">
                <a href="{{ route('admin.promotions.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-bold rounded-sm hover:bg-gray-50 transition-colors font-space-mono text-xs uppercase tracking-widest">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-black text-white font-bold rounded-sm shadow hover:bg-gray-800 transition-colors font-space-mono text-xs uppercase tracking-widest">
                    Create Promotion
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.select-tom').forEach((el) => {
            new TomSelect(el, {
                plugins: ['remove_button'],
                placeholder: 'Click to select options...',
                maxOptions: null
            });
        });
    });
</script>
@endpush
