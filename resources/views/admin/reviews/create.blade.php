@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.reviews.index') }}" class="text-gray-500 hover:text-black transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7 M10 19l-7-7 7-7"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h1 class="text-3xl font-bold font-space-mono text-gray-800">Add Fake Review</h1>
    </div>
</div>

<div class="glass-panel p-8 max-w-2xl">
    <form action="{{ route('admin.reviews.store') }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            {{-- Product --}}
            <div>
                <label for="product_id" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Select Product</label>
                <select name="product_id" id="product_id" required 
                        class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument select-tom">
                    <option value="">Choose a product...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Reviewer Name --}}
            <div>
                <label for="reviewer_name" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Fake Reviewer Name</label>
                <input type="text" name="reviewer_name" id="reviewer_name" required value="{{ old('reviewer_name') }}"
                       class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument"
                       placeholder="e.g. John Doe">
                @error('reviewer_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Rating --}}
            <div>
                <label for="rating" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Rating (1-5)</label>
                <select name="rating" id="rating" required 
                        class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating', 5) == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                    @endfor
                </select>
                @error('rating')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Comment --}}
            <div>
                <label for="comment" class="block text-sm font-bold font-space-mono text-gray-700 uppercase tracking-widest mb-2">Review Comment</label>
                <textarea name="comment" id="comment" rows="5" required
                          class="w-full bg-white/50 border border-gray-200 rounded-sm px-4 py-3 focus:outline-none focus:border-black transition-colors font-instrument"
                          placeholder="Write the fake review here...">{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-4">
                <a href="{{ route('admin.reviews.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-bold rounded-sm hover:bg-gray-50 transition-colors font-space-mono text-xs uppercase tracking-widest">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-black text-white font-bold rounded-sm shadow hover:bg-gray-800 transition-colors font-space-mono text-xs uppercase tracking-widest">
                    Post Fake Review
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
                plugins: ['clear_button'],
                placeholder: 'Type to search...',
                maxOptions: null
            });
        });
    });
</script>
@endpush
