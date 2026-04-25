@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.journals.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-space-mono tracking-tight">Create Journal</h1>
            <p class="mt-1 text-sm text-gray-500">Add a new editorial article to the Cavari journal.</p>
        </div>
    </div>

    <form action="{{ route('admin.journals.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Core Information -->
        <div class="glass-panel p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4 font-space-mono border-b border-gray-200/50 pb-2">Core Content</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Headline Title</label>
                    <input type="text" name="title" id="title" class="mt-1 flex-1 block w-full rounded-md sm:text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('title') }}" required placeholder="e.g. The Legacy of Blue Sapphires">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt / Short Description</label>
                    <textarea name="excerpt" id="excerpt" rows="2" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="A brief summary for the journal feed...">{{ old('excerpt') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Keep it under 150 characters for best display on the list page.</p>
                    @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Main Content (HTML Supported)</label>
                    <textarea name="content" id="content" rows="10" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 font-mono text-xs" required>{{ old('content', '<p>Your content here...</p>') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Use HTML tags like &lt;h2&gt;, &lt;p&gt;, &lt;blockquote&gt;, and &lt;img&gt; to format.</p>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Media & SEO -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="glass-panel p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4 font-space-mono border-b border-gray-200/50 pb-2">Media Base</h2>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cover Image</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12..."></path></svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="cover_image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 px-2 py-1">
                                    <span>Upload a file</span>
                                    <input id="cover_image" name="cover_image" type="file" class="sr-only" accept="image/*">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <div class="flex items-center">
                        <input id="is_published" name="is_published" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_published') ? 'checked' : '' }}>
                        <label for="is_published" class="ml-2 block text-sm text-gray-900 font-medium font-space-mono">
                            Publish immediately
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-6">If unchecked, it will be saved as a draft.</p>
                </div>
            </div>

            <div class="glass-panel p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4 font-space-mono border-b border-gray-200/50 pb-2">SEO Optimization</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" class="mt-1 block w-full rounded-md sm:text-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('meta_title') }}" placeholder="Optimized title for search engines">
                        <p class="text-[10px] text-gray-400 mt-1">Leave blank to use the Headline Title. Max 60 characters recommended.</p>
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="A compelling description for search engine results.">{{ old('meta_description') }}</textarea>
                        <p class="text-[10px] text-gray-400 mt-1">Leave blank to use the Excerpt. Max 160 characters recommended.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-black/10">
            <a href="{{ route('admin.journals.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cancel</a>
            <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Save & Initialize
            </button>
        </div>
    </form>
</div>
@endsection
