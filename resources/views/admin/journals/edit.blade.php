@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.journals.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-space-mono tracking-tight">Edit Journal</h1>
                <p class="mt-1 text-sm text-gray-500">Updating editorial #{{ $journal->id }}: {{ $journal->title }}</p>
            </div>
        </div>
        <a href="{{ route('journal.show', $journal->slug) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Preview Frontend
        </a>
    </div>

    <form action="{{ route('admin.journals.update', $journal) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Core Information -->
        <div class="glass-panel p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4 font-space-mono border-b border-gray-200/50 pb-2">Core Content</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Headline Title</label>
                    <input type="text" name="title" id="title" class="mt-1 flex-1 block w-full rounded-md sm:text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('title', $journal->title) }}" required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt / Short Description</label>
                    <textarea name="excerpt" id="excerpt" rows="2" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">{{ old('excerpt', $journal->excerpt) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Keep it under 150 characters for best display on the list page.</p>
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Main Content (HTML Supported)</label>
                    <textarea name="content" id="content" rows="10" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 font-mono text-xs" required>{{ old('content', $journal->content) }}</textarea>
                    <p class="text-[10px] text-gray-500 mt-1">For blockquotes inside the content use <code>&lt;blockquote&gt;Text&lt;/blockquote&gt;</code>.</p>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Media & SEO -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="glass-panel p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4 font-space-mono border-b border-gray-200/50 pb-2">Media & Status</h2>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Current Cover Image</label>
                    @if($journal->cover_image)
                        <img src="{{ asset($journal->cover_image) }}" alt="Preview" class="mt-2 w-full max-h-48 object-cover rounded shadow ring-1 ring-black/10">
                    @else
                        <div class="mt-2 text-sm text-gray-500 italic">No image uploaded.</div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Replace Cover Image</label>
                    <input type="file" name="cover_image" accept="image/*" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-200 rounded p-1">
                </div>
                
                <div class="mt-8 pt-4 border-t border-gray-200/50">
                    <div class="flex items-center">
                        <input id="is_published" name="is_published" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_published', $journal->is_published) ? 'checked' : '' }}>
                        <label for="is_published" class="ml-2 block text-sm text-gray-900 font-medium font-space-mono">
                            Published
                        </label>
                    </div>
                </div>
            </div>

            <div class="glass-panel p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4 font-space-mono border-b border-gray-200/50 pb-2">SEO Optimization</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" class="mt-1 block w-full rounded-md sm:text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('meta_title', $journal->meta_title) }}">
                        <p class="text-[10px] text-gray-400 mt-1">Overrides the headline title for search engines.</p>
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">{{ old('meta_description', $journal->meta_description) }}</textarea>
                        <p class="text-[10px] text-gray-400 mt-1">If blank, Google will try to pull from the excerpt.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-black/10">
            <a href="{{ route('admin.journals.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Discard Changes</a>
            <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Update & Publish
            </button>
        </div>
    </form>
</div>
@endsection
