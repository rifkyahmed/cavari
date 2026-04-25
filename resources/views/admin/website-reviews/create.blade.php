@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold font-space-mono text-gray-800">Add Fake Review</h1>
    <a href="{{ route('admin.website-reviews.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors font-space-mono text-sm uppercase">Cancel</a>
</div>

<div class="glass-panel p-8 max-w-2xl mx-auto">
    <form action="{{ route('admin.website-reviews.store') }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1">Author Name (Fake)</label>
                <input type="text" name="author_name" id="author_name" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50" required value="{{ old('author_name') }}">
                @error('author_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location/Country (Optional)</label>
                <input type="text" name="location" id="location" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50" value="{{ old('location') }}">
                @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating (1-5)</label>
                <select name="rating" id="rating" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50" required>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
                @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Review Content</label>
                <textarea name="comment" id="comment" rows="4" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50" required>{{ old('comment') }}</textarea>
                @error('comment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_approved" value="1" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5">
                    <span class="ml-2 text-sm text-gray-700">Approve immediately (Visible on website)</span>
                </label>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-black text-white font-bold rounded-lg shadow hover:bg-gray-800 transition-colors font-space-mono text-sm uppercase">
                    Save Review
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
