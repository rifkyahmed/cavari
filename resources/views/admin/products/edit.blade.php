@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold font-space-mono text-gray-800">Edit Product: {{ $product->name }}</h1>
        <a href="{{ route('admin.products.index') }}"
            class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors font-space-mono text-sm uppercase">
            Cancel
        </a>
    </div>

    <div class="glass-panel p-8">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="image_order" id="image-order-input">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type -->
                <div>
                    <label for="product_type" class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
                    <select name="product_type" id="product_type"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        required>
                        <option value="jewelry" {{ old('product_type', $product->product_type ?? 'jewelry') == 'jewelry' ? 'selected' : '' }}>Gem & Jewelry</option>
                        <option value="loose_gem" {{ old('product_type', $product->product_type ?? 'jewelry') == 'loose_gem' ? 'selected' : '' }}>Loose Gem</option>
                    </select>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text" name="name" id="name"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        required value="{{ old('name', $product->name) }}">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Level 1 Category (Main) -->
                <div>
                    <label for="main_category_id" class="block text-sm font-medium text-gray-700 mb-1">Main Category</label>
                    <select id="main_category_id"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        required>
                        <option value="">Select Main Category</option>
                        @foreach($categories as $mainCat)
                            <option value="{{ $mainCat->id }}" data-type="{{ $mainCat->type }}" {{ (isset($hierarchy[0]) && $hierarchy[0] == $mainCat->id) ? 'selected' : '' }}>{{ $mainCat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Level 2 Category (Leaf) -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Sub Category</label>
                    <select name="category_id" id="category_id"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        required>
                        <option value="">Select Sub Category</option>
                        @php
                            $activeMain = isset($hierarchy[0]) ? $categories->firstWhere('id', $hierarchy[0]) : null;
                            $children = $activeMain ? $activeMain->children : collect();
                        @endphp
                        @foreach($children as $child)
                            <option value="{{ $child->id }}" {{ (isset($hierarchy[1]) && $hierarchy[1] == $child->id) ? 'selected' : '' }}>{{ $child->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Pricing & Cost Breakdown Section -->
                <div
                    class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-gray-50 rounded-xl border border-gray-200 mb-4">
                    <div class="md:col-span-3">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide font-space-mono">Pricing & Costs
                        </h3>
                    </div>
                    <div id="gold_cost_container">
                        <label for="gold_cost_price" class="block text-sm font-medium text-gray-700 mb-1">Gold Cost Price
                            ($)</label>
                        <input type="number" step="0.01" name="gold_cost_price" id="gold_cost_price"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                            value="{{ old('gold_cost_price', $product->gold_cost_price) }}">
                        @error('gold_cost_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div id="gem_cost_container">
                        <label for="gem_cost_price" class="block text-sm font-medium text-gray-700 mb-1">Gem Cost Price
                            ($)</label>
                        <input type="number" step="0.01" name="gem_cost_price" id="gem_cost_price"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                            value="{{ old('gem_cost_price', $product->gem_cost_price) }}">
                        @error('gem_cost_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Selling Price ($)</label>
                        <input type="number" step="0.01" name="price" id="price"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                            required value="{{ old('price', $product->price) }}">
                        @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div id="profit_display_container" class="md:col-span-3 flex justify-end">
                        <div class="px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center shadow-sm">
                            <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-widest font-space-mono mr-3">Est. Profit Margin:</span>
                            <span id="profit_value" class="text-xl font-bold text-emerald-600 font-space-mono">$0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Detailed Specifications -->
                <div
                    class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-indigo-50/30 rounded-xl border border-indigo-100 mb-4">
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-bold text-indigo-800 uppercase tracking-wide font-space-mono">Physical
                            Specifications</h3>
                    </div>
                    <!-- Gold Weight -->
                    <div id="gold_weight_container">
                        <label for="gold_weight" class="block text-sm font-medium text-gray-700 mb-1">Gold Weight
                            (grams)</label>
                        <input type="number" step="0.01" name="gold_weight" id="gold_weight"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white"
                            value="{{ old('gold_weight', $product->gold_weight) }}">
                        <p class="text-[10px] text-gray-500 mt-1 font-instrument">Important for gold-based jewelry.</p>
                    </div>

                    <!-- Gem Weight -->
                    <div>
                        <label for="gem_weight" class="block text-sm font-medium text-gray-700 mb-1">Gem Weight
                            (carats)</label>
                        <input type="number" step="0.01" name="gem_weight" id="gem_weight"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white"
                            value="{{ old('gem_weight', $product->gem_weight) }}">
                    </div>
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                    <input type="number" name="stock" id="stock"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        required value="{{ old('stock', $product->stock) }}">
                    @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Caret Range (Conditional) -->
                <div id="caret_range_container" style="display: none;">
                    <label for="caret_range" class="block text-sm font-medium text-gray-700 mb-1">Caret Range /
                        Purity</label>
                    <select name="caret_range" id="caret_range"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50">
                        <option value="">Select Caret Range</option>
                        <option value="18k" {{ old('caret_range', $product->caret_range) == '18k' ? 'selected' : '' }}>18K
                        </option>
                        <option value="20k" {{ old('caret_range', $product->caret_range) == '20k' ? 'selected' : '' }}>20K
                        </option>
                        <option value="22k" {{ old('caret_range', $product->caret_range) == '22k' ? 'selected' : '' }}>22K
                        </option>
                        <option value="24k" {{ old('caret_range', $product->caret_range) == '24k' ? 'selected' : '' }}>24K
                        </option>
                    </select>
                    @error('caret_range') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Size (Optional) -->
                <div>
                    <label for="size" class="block text-sm font-medium text-gray-700 mb-1">Size / Dimension</label>
                    <input type="text" name="size" id="size"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        value="{{ old('size', $product->size) }}" placeholder="e.g. Ring Size 7, 18 inch Chain">
                </div>

                <!-- Gemstone Type (Optional) -->
                <div>
                    <label for="gemstone_type" class="block text-sm font-medium text-gray-700 mb-1">Gemstone Type</label>
                    <input type="text" name="gemstone_type" id="gemstone_type"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        value="{{ old('gemstone_type', $product->gemstone_type) }}" placeholder="e.g. Ruby, Sapphire">
                </div>

                <!-- Origin (Optional) -->
                <div>
                    <label for="origin" class="block text-sm font-medium text-gray-700 mb-1">Origin</label>
                    <input type="text" name="origin" id="origin"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        value="{{ old('origin', $product->origin) }}" placeholder="e.g. Ceylon, Colombia">
                </div>

                <!-- Clarity (Optional) -->
                <div>
                    <label for="clarity" class="block text-sm font-medium text-gray-700 mb-1">Clarity</label>
                    <input type="text" name="clarity" id="clarity"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        value="{{ old('clarity', $product->clarity) }}" placeholder="e.g. VVS1, Eye Clean">
                </div>

                <!-- Featured Checkbox -->
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="is_featured" id="is_featured"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                    <label for="is_featured" class="ml-2 block text-sm font-medium text-gray-700">Mark as Featured
                        Product</label>
                </div>

                <!-- Atelier Checkbox -->
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="is_atelier" id="is_atelier"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5" {{ old('is_atelier', $product->is_atelier) ? 'checked' : '' }}>
                    <label for="is_atelier" class="ml-2 block text-sm font-medium text-gray-700">Show in The Atelier
                        Section</label>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                    required>{{ old('description', $product->description) }}</textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Special Comments -->
            <div class="mt-6">
                <label for="special_comments" class="block text-sm font-medium text-gray-700 mb-1">Special Comments</label>
                <textarea name="special_comments" id="special_comments" rows="3"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                    placeholder="e.g. Unique inclusions, specific certification details...">{{ old('special_comments', $product->special_comments) }}</textarea>
                @error('special_comments') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Current Images --}}
            @if(isset($product->images) && count($product->images) > 0)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Images <span
                            class="text-gray-400 font-normal text-xs">(click × to remove on save)</span></label>
                    <div class="flex flex-wrap gap-4" id="current-images-grid">
                        @foreach($product->images as $index => $image)
                            @php
                                // Resolve correct URL regardless of how path was stored
                                if (\Illuminate\Support\Str::startsWith($image, ['http', 'https'])) {
                                    $resolvedUrl = $image;
                                } elseif (\Illuminate\Support\Str::startsWith($image, ['/storage/', 'storage/'])) {
                                    $resolvedUrl = asset($image);
                                } elseif (\Illuminate\Support\Str::startsWith($image, 'images/')) {
                                    $resolvedUrl = asset($image);
                                } else {
                                    $resolvedUrl = asset('storage/' . $image);
                                }
                            @endphp
                            <div class="relative group/img cursor-move" id="current-img-wrapper-{{ $index }}"
                                data-id="{{ $image }}">
                                <img src="{{ $resolvedUrl }}"
                                    class="h-28 w-28 object-cover rounded-xl shadow border border-gray-200 transition-opacity duration-200"
                                    id="current-img-{{ $index }}" alt="Product Image {{ $index + 1 }}">
                                {{-- Hidden checkbox that gets checked on X click --}}
                                <input type="checkbox" name="remove_images[]" value="{{ $index }}" id="remove-img-{{ $index }}"
                                    class="hidden remove-img-checkbox">
                                {{-- X Button --}}
                                <button type="button" onclick="markImageForRemoval({{ $index }})"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-md hover:bg-red-600 transition-all hover:scale-110 focus:outline-none opacity-0 group-hover/img:opacity-100 transition-opacity"
                                    title="Remove this image">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                {{-- "Marked for removal" overlay --}}
                                <div id="remove-overlay-{{ $index }}"
                                    class="hidden absolute inset-0 bg-red-500/40 rounded-xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Images marked with × will be permanently deleted when you save.</p>
                </div>
            @endif

            {{-- Add New Images --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Add New Images</label>
                <div
                    class="mt-1 px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg bg-white/30 hover:bg-white/50 transition-colors">

                    {{-- Default state: large icon + instruction --}}
                    <div id="img-default-text" class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex justify-center text-sm text-gray-600">
                            <label for="image_files"
                                class="cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                <span>Upload files</span>
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB each</p>
                    </div>

                    {{-- Compact "Add more" state — shown after first selection --}}
                    <div id="img-add-more-label" class="hidden flex items-center justify-center gap-2 py-1">
                        <label for="image_files"
                            class="cursor-pointer flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add more images
                        </label>
                    </div>

                    {{-- Single hidden input — shared by both label states above --}}
                    <input id="image_files" name="image_files[]" type="file" class="sr-only" multiple accept="image/*">

                    {{-- JS Preview Grid for newly selected images --}}
                    <div id="new-img-preview-grid" class="hidden mt-4 flex flex-wrap gap-3"></div>
                </div>
                @error('image_files.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>


            {{-- Current Video --}}
            @if($product->video)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Product Video</label>
                    <div class="flex items-start gap-4 p-4 bg-black/5 rounded-xl border border-gray-200">
                        <video src="{{ asset($product->video) }}" controls
                            class="max-h-48 rounded-lg shadow-md flex-shrink-0"></video>
                        <div class="flex flex-col gap-2 pt-1">
                            <p class="text-sm text-gray-600 font-medium">Video is active — plays on hover in shop.</p>

                            @php
                                $isCompressed = str_contains(strtolower($product->video), 'compressed') || str_contains(strtolower($product->video), 'cloudinary');
                            @endphp
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $isCompressed ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-amber-100 text-amber-700 border border-amber-200' }}">
                                    {{ $isCompressed ? 'Compressed' : 'Original' }}
                                </span>
                                <span class="text-[10px] text-gray-500 font-medium italic">
                                    {{ $isCompressed ? 'Optimized for web performance' : 'Raw file — consider compressing for faster loading' }}
                                </span>
                            </div>

                            <label class="flex items-center gap-2 mt-2 cursor-pointer group">
                                <input type="checkbox" name="remove_video" value="1"
                                    class="rounded border-gray-300 text-red-500 focus:ring-red-400 h-4 w-4">
                                <span class="text-sm text-red-600 group-hover:text-red-700">Remove this video</span>
                            </label>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Add / Replace Video --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $product->video ? 'Replace Video' : 'Product Video' }}
                    <span class="text-gray-400 font-normal">(optional — plays on hover in shop)</span>
                </label>
                <div
                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg bg-white/30 hover:bg-white/50 transition-colors cursor-pointer relative">
                    <div class="space-y-1 text-center" id="video-upload-placeholder">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="video_file"
                                class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                <span>{{ $product->video ? 'Upload replacement' : 'Upload a video' }}</span>
                                <input id="video_file" name="video_file" type="file" class="sr-only"
                                    accept="video/mp4,video/webm,video/quicktime,video/avi">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">MP4, WebM, MOV up to 50MB</p>
                        <p class="mt-2 text-[10px] text-indigo-500 font-medium">Tip: Use Handbrake or an online compressor
                            to keep videos under 5MB for faster loading.</p>
                    </div>
                    <div id="video-preview-container" class="hidden w-full text-center">
                        <video id="video-preview" controls class="mx-auto max-h-48 rounded-lg shadow"></video>
                        <p class="text-xs text-gray-500 mt-2" id="video-file-name"></p>
                        <button type="button" onclick="clearVideoPreview()"
                            class="mt-2 text-xs text-red-500 hover:text-red-700 underline">Remove selection</button>
                    </div>
                </div>
                @error('video_file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Current Certificate --}}
            @if($product->certificate)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Certificate of Gem / Product</label>
                    <div class="flex items-start gap-4 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                        <div class="w-16 h-16 bg-white rounded-lg shadow-sm flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex flex-col gap-2 pt-1">
                            <p class="text-sm text-gray-600 font-medium">Certificate is uploaded.</p>
                            <div class="flex gap-4">
                                <a href="{{ asset($product->certificate) }}" target="_blank"
                                    class="text-xs text-indigo-600 hover:text-indigo-800 underline">View Certificate</a>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="remove_certificate" value="1"
                                        class="rounded border-gray-300 text-red-500 focus:ring-red-400 h-4 w-4">
                                    <span class="text-sm text-red-600 group-hover:text-red-700">Remove certificate</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Add / Replace Certificate --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $product->certificate ? 'Replace Certificate of Gem / Product' : 'Certificate of Gem / Product' }}
                    <span class="text-gray-400 font-normal">(optional — PDF or Image)</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg bg-white/30 hover:bg-white/50 transition-colors cursor-pointer relative"
                    id="cert-drop-zone">
                    <div class="space-y-1 text-center" id="cert-upload-placeholder">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="certificate_file"
                                class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                <span>{{ $product->certificate ? 'Upload replacement' : 'Upload certificate' }}</span>
                                <input id="certificate_file" name="certificate_file" type="file" class="sr-only"
                                    accept=".pdf,image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, JPG, PNG up to 5MB</p>
                    </div>
                    <div id="cert-preview-container" class="hidden w-full text-center">
                        <div class="mx-auto w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-900" id="cert-file-name"></p>
                        <button type="button" onclick="clearCertPreview()"
                            class="mt-2 text-xs text-red-500 hover:text-red-700 underline">Remove selection</button>
                    </div>
                </div>
                @error('certificate_file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" id="save-btn"
                    class="px-6 py-3 bg-black text-white font-bold rounded-lg shadow hover:bg-gray-800 active:scale-95 transition-all font-space-mono text-sm uppercase tracking-wide flex items-center gap-2">
                    <svg id="save-spin" class="w-4 h-4 hidden animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <span id="save-text">Update Product</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Show loading state on form submit
        document.querySelector('form').addEventListener('submit', function () {
            const btn = document.getElementById('save-btn');
            const spin = document.getElementById('save-spin');
            const text = document.getElementById('save-text');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                if (spin) spin.classList.remove('hidden');
                if (text) text.textContent = 'Updating...';
            }
        });

        // ... existing scripts ...
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
            // ============================================================
            // 0. Image Reordering (Current Images)
            // ============================================================
            document.addEventListener('DOMContentLoaded', () => {
                const grid = document.getElementById('current-images-grid');
                if (grid) {
                    Sortable.create(grid, {
                        animation: 150,
                        ghostClass: 'bg-indigo-100',
                        onEnd: () => {
                            updateImageOrder();
                        }
                    });
                updateImageOrder();
                }

                const newGrid = document.getElementById('new-img-preview-grid');
                if (newGrid) {
                    Sortable.create(newGrid, {
                        animation: 150,
                        ghostClass: 'bg-indigo-100',
                        onEnd: (evt) => {
                            const oldIndex = evt.oldIndex;
                            const newIndex = evt.newIndex;
                            const movedFile = newImageFiles.splice(oldIndex, 1)[0];
                            newImageFiles.splice(newIndex, 0, movedFile);
                            renderNewImagePreviews();
                        }
                    });
                }
            });


                function updateImageOrder() {
                const orderInput = document.getElementById('image-order-input');
                const grid = document.getElementById('current-images-grid');
                if (orderInput && grid) {
                    const items = grid.querySelectorAll('[data-id]');
                    const order = Array.from(items).map(item => item.getAttribute('data-id'));
                orderInput.value = JSON.stringify(order);
                }
            }

                // ============================================================
                // 1. Mark existing images for removal (toggle X / overlay)
                // ============================================================
                function markImageForRemoval(index) {
                const checkbox  = document.getElementById('remove-img-' + index);
                const overlay   = document.getElementById('remove-overlay-' + index);
                const imgEl     = document.getElementById('current-img-' + index);
                if (!checkbox) return;

                const willRemove = !checkbox.checked;
                checkbox.checked = willRemove;

                if (willRemove) {
                    overlay.classList.remove('hidden');
                imgEl.style.opacity = '0.35';
                } else {
                    overlay.classList.add('hidden');
                imgEl.style.opacity = '1';
                }
            }

                // ============================================================
                // 2. New image upload → inline preview with X remove buttons
                // ============================================================
                let newImageFiles = [];   // mirrors the selected files

                const imgInput    = document.getElementById('image_files');
                const previewGrid = document.getElementById('new-img-preview-grid');

                if (imgInput) {
                    imgInput.addEventListener('change', function () {
                        // Append new files to our tracked list
                        Array.from(this.files).forEach(f => newImageFiles.push(f));
                        renderNewImagePreviews();
                    });
            }

                function renderNewImagePreviews() {
                if (!previewGrid) return;
                previewGrid.innerHTML = '';

                const imgDefaultText = document.getElementById('img-default-text');
                const imgAddMoreLabel = document.getElementById('img-add-more-label');

                if (newImageFiles.length === 0) {
                    previewGrid.classList.add('hidden');
                // Reset to default upload UI
                if (imgDefaultText)  imgDefaultText.classList.remove('hidden');
                if (imgAddMoreLabel) imgAddMoreLabel.classList.add('hidden');
                if (imgInput) imgInput.value = '';
                return;
                }

                // ✅ Keep upload zone ALWAYS visible — never hide it
                previewGrid.classList.remove('hidden');
                // Switch to compact "Add more" label
                if (imgDefaultText)  imgDefaultText.classList.add('hidden');
                if (imgAddMoreLabel) imgAddMoreLabel.classList.remove('hidden');

                newImageFiles.forEach(function (file, idx) {
                    const reader = new FileReader();
                reader.onload = function (e) {
                        const wrapper = document.createElement('div');
                wrapper.className = 'relative group/new flex-shrink-0 cursor-move';
                wrapper.innerHTML = `
                <div class="h-24 w-24 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 shadow relative group">
                    <img src="${e.target.result}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" alt="Preview">
                        <button type="button"
                            onclick="removeNewImage(${idx})"
                            class="absolute top-1 right-1 w-6 h-6 bg-red-500/80 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all scale-0 group-hover:scale-100 backdrop-blur-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                </div>`;
                        previewGrid.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });

                // Sync the actual file input from our newImageFiles array
                syncFileInput();
            }

            function removeNewImage(idx) {
                newImageFiles.splice(idx, 1);
                renderNewImagePreviews();
            }

            function syncFileInput() {
                if (!imgInput) return;
                try {
                    const dt = new DataTransfer();
                    newImageFiles.forEach(f => dt.items.add(f));
                    imgInput.files = dt.files;
                } catch (e) {
                    // DataTransfer not supported — files will still submit via the array
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const categories = @json($categories);
                const hierarchy = @json($hierarchy);
                const mainCatSelect = document.getElementById('main_category_id');
                const leafCatSelect = document.getElementById('category_id');
                const productTypeSelect = document.getElementById('product_type');
                const goldWeightContainer = document.getElementById('gold_weight_container');
                const caretRangeContainer = document.getElementById('caret_range_container');
                const goldCostContainer = document.getElementById('gold_cost_container');
                const gemCostContainer = document.getElementById('gem_cost_container');
                const goldWeightInput = document.getElementById('gold_weight');
                const caretRangeSelect = document.getElementById('caret_range');
                const goldCostInput = document.getElementById('gold_cost_price');
                const gemCostInput = document.getElementById('gem_cost_price');
                const sellingPriceInput = document.getElementById('price');

                const dashboardGoldPrice = {{ $goldPrice ?? 0 }};
                const allMainOptions = Array.from(mainCatSelect.options);
                const profitValueDisplay = document.getElementById('profit_value');

                function roundUpToTen(value) {
                    return Math.ceil(value / 10) * 10;
                }

                function updateProfitDisplay() {
                    const goldCost = parseFloat(goldCostInput.value) || 0;
                    const gemCost = parseFloat(gemCostInput.value) || 0;
                    const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
                    
                    const profit = sellingPrice - (goldCost + gemCost);
                    profitValueDisplay.textContent = '$' + profit.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    
                    if (profit < 0) {
                        profitValueDisplay.classList.remove('text-emerald-600');
                        profitValueDisplay.classList.add('text-red-600');
                    } else {
                        profitValueDisplay.classList.remove('text-red-600');
                        profitValueDisplay.classList.add('text-emerald-600');
                    }
                }

                function calculatePrices(isParameterChange = false) {
                    const mainId = mainCatSelect.value;
                    const mainCat = categories.find(c => c.id == mainId);
                    const mainCatName = mainCat ? mainCat.name.toLowerCase() : '';

                    const isGoldCategory = mainCatName.includes('gold');
                    const isSilverCategory = mainCatName.includes('silver');
                    const isPlatinumCategory = mainCatName.includes('platinum');

                    if (!isGoldCategory && !isSilverCategory && !isPlatinumCategory) {
                        updateProfitDisplay();
                        return; 
                    }

                    const weight = parseFloat(goldWeightInput.value) || 0;
                    const caret = caretRangeSelect.value;
                    
                    let multiplier = 1;
                    if (isGoldCategory) {
                        if (caret === '24k') multiplier = 1;
                        else if (caret === '22k') multiplier = 22/24;
                        else if (caret === '20k') multiplier = 20/24;
                        else if (caret === '18k') multiplier = 18/24;
                    }

                    const currentGoldCost = parseFloat(goldCostInput.value) || 0;
                    const newGoldCost = weight * dashboardGoldPrice * multiplier;

                    if (isParameterChange) {
                        const currentSellingPrice = parseFloat(sellingPriceInput.value) || 0;
                        if (currentSellingPrice > 0 && currentGoldCost > 0) {
                            const delta = newGoldCost - currentGoldCost;
                            if (Math.abs(delta) > 0.01) {
                                sellingPriceInput.value = roundUpToTen(currentSellingPrice + delta).toFixed(2);
                            }
                        }
                        goldCostInput.value = newGoldCost.toFixed(2);
                    }

                    updateProfitDisplay();
                }



                function updateFieldVisibility() {
                    const mainId = mainCatSelect.value;
                    const mainCat = categories.find(c => c.id == mainId);
                    const mainCatName = mainCat ? mainCat.name.toLowerCase() : '';
                    const productType = productTypeSelect.value;

                    // Dynamic Labels based on Metal
                    const goldCostLabel = document.querySelector('label[for="gold_cost_price"]');
                    const goldWeightLabel = document.querySelector('label[for="gold_weight"]');

                    if (mainCatName.includes('platinum')) {
                        if (goldCostLabel) goldCostLabel.textContent = 'Platinum Cost Price ($)';
                        if (goldWeightLabel) goldWeightLabel.textContent = 'Platinum Weight (grams)';
                        caretRangeContainer.style.display = 'none';
                    } else if (mainCatName.includes('silver')) {
                        if (goldCostLabel) goldCostLabel.textContent = 'Silver Cost Price ($)';
                        if (goldWeightLabel) goldWeightLabel.textContent = 'Silver Weight (grams)';
                        caretRangeContainer.style.display = 'none';
                    } else if (mainCatName.includes('gold')) {
                        if (goldCostLabel) goldCostLabel.textContent = 'Gold Cost Price ($)';
                        if (goldWeightLabel) goldWeightLabel.textContent = 'Gold Weight (grams)';
                        caretRangeContainer.style.display = 'block'; // Show Caret for gold
                    } else {
                        if (goldCostLabel) goldCostLabel.textContent = 'Metal Cost Price ($)';
                        if (goldWeightLabel) goldWeightLabel.textContent = 'Metal Weight (grams)';
                        caretRangeContainer.style.display = 'none';
                    }

                    // Loose Gem: Show Gem Cost Only, Hide Metal Cost & Weight
                    if (productType === 'loose_gem') {
                        if (goldWeightContainer) goldWeightContainer.style.display = 'none';
                        if (goldCostContainer) goldCostContainer.style.display = 'none';
                        if (gemCostContainer) gemCostContainer.style.display = 'block';
                        caretRangeContainer.style.display = 'none';
                    } else {
                        if (goldWeightContainer) goldWeightContainer.style.display = 'block';
                        if (goldCostContainer) goldCostContainer.style.display = 'block';
                        if (gemCostContainer) gemCostContainer.style.display = 'block';
                    }
                }




                function filterCategoriesByType(initialLoad = false) {
                    const selectedType = productTypeSelect.value;
                    const currentMainId = mainCatSelect.value;

                    mainCatSelect.innerHTML = '';

                    allMainOptions.forEach(opt => {
                        if (opt.value === "") {
                            mainCatSelect.appendChild(opt.cloneNode(true));
                            return;
                        }
                        const catType = opt.getAttribute('data-type');
                        if (catType === selectedType) {
                            mainCatSelect.appendChild(opt.cloneNode(true));
                        }
                    });

                    if (currentMainId) {
                        const stillExists = Array.from(mainCatSelect.options).some(o => o.value == currentMainId);
                        if (stillExists) {
                            mainCatSelect.value = currentMainId;
                        } else if (!initialLoad) {
                            mainCatSelect.value = "";
                            leafCatSelect.innerHTML = '<option value="">Select Sub Category</option>';
                            leafCatSelect.disabled = true;
                        }
                    }
                }

                function populateSubCategories(selectedId = null) {
                    const mainId = mainCatSelect.value;
                    const mainCat = categories.find(c => c.id == mainId);

                    leafCatSelect.innerHTML = '<option value="">Select Sub Category</option>';

                    if (mainCat && mainCat.children.length > 0) {
                        leafCatSelect.disabled = false;
                        mainCat.children.forEach(child => {
                            const opt = document.createElement('option');
                            opt.value = child.id;
                            opt.textContent = child.name;
                            if (selectedId && child.id == selectedId) opt.selected = true;
                            leafCatSelect.appendChild(opt);
                        });
                    } else {
                        leafCatSelect.disabled = true;
                    }
                    updateFieldVisibility();
                }

                mainCatSelect.addEventListener('change', () => {
                    populateSubCategories();
                });

                productTypeSelect.addEventListener('change', function() {
                    filterCategoriesByType();
                    updateFieldVisibility();
                });

                // Initialize (hierarchy[1] is Sub)
                filterCategoriesByType(true);
                if (hierarchy.length > 0) {
                    if (hierarchy[0]) populateSubCategories(hierarchy[1] || null);
                }

                updateFieldVisibility();
                updateProfitDisplay();

                goldWeightInput.addEventListener('blur', () => calculatePrices(true));
                caretRangeSelect.addEventListener('change', () => calculatePrices(true));
                goldCostInput.addEventListener('blur', () => updateProfitDisplay());
                gemCostInput.addEventListener('blur', () => updateProfitDisplay());
                sellingPriceInput.addEventListener('blur', function() {
                    const value = parseFloat(this.value);
                    if (!isNaN(value) && value > 0) {
                        this.value = roundUpToTen(value).toFixed(2);
                    }
                    updateProfitDisplay();
                });
            });

            // ============================================================
            // 4. Video Preview
            // ============================================================
            const videoInput = document.getElementById('video_file');
            const videoPreview = document.getElementById('video-preview');
            const videoPreviewContainer = document.getElementById('video-preview-container');
            const videoUploadPlaceholder = document.getElementById('video-upload-placeholder');
            const videoFileName = document.getElementById('video-file-name');

            if (videoInput) {
                videoInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) return;
                    videoPreview.src = URL.createObjectURL(file);
                    videoFileName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                    videoPreviewContainer.classList.remove('hidden');
                    if (videoUploadPlaceholder) videoUploadPlaceholder.classList.add('hidden');
                });
            }

            function clearVideoPreview() {
                if (videoInput) videoInput.value = '';
                if (videoPreview) { videoPreview.src = ''; videoPreview.load(); }
                if (videoPreviewContainer) videoPreviewContainer.classList.add('hidden');
                if (videoUploadPlaceholder) videoUploadPlaceholder.classList.remove('hidden');
            }

            // ---- Certificate Preview ----
            const certInput = document.getElementById('certificate_file');
            const certPreviewContainer = document.getElementById('cert-preview-container');
            const certUploadPlaceholder = document.getElementById('cert-upload-placeholder');
            const certFileName = document.getElementById('cert-file-name');

            if (certInput) {
                certInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) return;
                    certFileName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                    certPreviewContainer.classList.remove('hidden');
                    if (certUploadPlaceholder) certUploadPlaceholder.classList.add('hidden');
                });
            }

            function clearCertPreview() {
                if (certInput) certInput.value = '';
                if (certPreviewContainer) certPreviewContainer.classList.add('hidden');
                if (certUploadPlaceholder) certUploadPlaceholder.classList.remove('hidden');
            }
    </script>
@endpush