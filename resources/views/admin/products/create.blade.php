@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold font-space-mono text-gray-800">Add Product</h1>
        <a href="{{ route('admin.products.index') }}"
            class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors font-space-mono text-sm uppercase">
            Cancel
        </a>
    </div>

    <div class="glass-panel p-8">

        {{-- ═══════════════════════════════════════════════════════════════
        GemLightbox Quick Import
        ═══════════════════════════════════════════════════════════════ --}}
        <div class="mb-8 p-5 rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-space-mono text-sm font-bold text-indigo-800 uppercase tracking-wide">Import from
                        GemLightbox</h3>
                    <p class="text-xs text-indigo-600 mt-0.5">Paste your product link to auto-fill name, description, images
                        & video</p>
                </div>
            </div>
            <div class="flex gap-3">
                <input type="url" id="gl-url-input" placeholder="https://gembox.app/s/your-product-id"
                    class="flex-1 rounded-xl border border-indigo-200 bg-white px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 font-instrument">
                <button type="button" id="gl-import-btn" onclick="importFromGemLightbox()"
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 active:scale-95 transition-all font-space-mono uppercase tracking-wide flex items-center gap-2">
                    <svg id="gl-spin" class="w-4 h-4 hidden animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    Import
                </button>
            </div>
            <div id="gl-error" class="hidden mt-2 text-xs text-red-600 font-instrument"></div>
            <div id="gl-success" class="hidden mt-2 text-xs text-green-700 font-instrument font-bold"></div>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Hidden inputs to carry imported remote URLs through form submit --}}
            <div id="gl-hidden-images"></div>
            <input type="hidden" name="image_order" id="image-order-input">
            <input type="hidden" name="gl_video" id="gl-hidden-video">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type -->
                <div>
                    <label for="product_type" class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
                    <select name="product_type" id="product_type"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        required>
                        <option value="jewelry" {{ old('product_type') == 'jewelry' ? 'selected' : '' }}>Gem & Jewelry
                        </option>
                        <option value="loose_gem" {{ old('product_type') == 'loose_gem' ? 'selected' : '' }}>Loose Gem
                        </option>
                    </select>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text" name="name" id="name"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        required value="{{ old('name') }}">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Main Category (Level 1) -->
                <!-- Level 1 Category (Main) -->
                <div>
                    <label for="main_category_id" class="block text-sm font-medium text-gray-700 mb-1">Main Category</label>
                    <select id="main_category_id"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        required>
                        <option value="">Select Main Category</option>
                        @foreach($categories as $mainCat)
                            <option value="{{ $mainCat->id }}" data-type="{{ $mainCat->type }}">{{ $mainCat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Level 2 Category (Leaf) -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Sub Category</label>
                    <select name="category_id" id="category_id"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        required disabled>
                        <option value="">Select Sub Category</option>
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
                            value="{{ old('gold_cost_price') }}">
                        @error('gold_cost_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div id="gem_cost_container">
                        <label for="gem_cost_price" class="block text-sm font-medium text-gray-700 mb-1">Gem Cost Price
                            ($)</label>
                        <input type="number" step="0.01" name="gem_cost_price" id="gem_cost_price"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                            value="{{ old('gem_cost_price') }}">
                        @error('gem_cost_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Selling Price ($)</label>
                        <input type="number" step="0.01" name="price" id="price"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                            required value="{{ old('price') }}">
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
                            value="{{ old('gold_weight', 0) }}">
                        <p class="text-[10px] text-gray-500 mt-1 font-instrument">Important for gold-based jewelry.</p>
                    </div>

                    <!-- Gem Weight -->
                    <div>
                        <label for="gem_weight" class="block text-sm font-medium text-gray-700 mb-1">Gem Weight
                            (carats)</label>
                        <input type="number" step="0.01" name="gem_weight" id="gem_weight"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white"
                            value="{{ old('gem_weight', 0) }}">
                    </div>
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                    <input type="number" name="stock" id="stock"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        required value="{{ old('stock', 0) }}">
                    @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Caret Range (Conditional) -->
                <div id="caret_range_container" style="display: none;">
                    <label for="caret_range" class="block text-sm font-medium text-gray-700 mb-1">Caret Range /
                        Purity</label>
                    <select name="caret_range" id="caret_range"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50">
                        <option value="">Select Caret Range</option>
                        <option value="18k" {{ old('caret_range') == '18k' ? 'selected' : '' }}>18K</option>
                        <option value="20k" {{ old('caret_range') == '20k' ? 'selected' : '' }}>20K</option>
                        <option value="22k" {{ old('caret_range') == '22k' ? 'selected' : '' }}>22K</option>
                        <option value="24k" {{ old('caret_range') == '24k' ? 'selected' : '' }}>24K</option>
                    </select>
                    @error('caret_range') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Size (Optional) -->
                <div>
                    <label for="size" class="block text-sm font-medium text-gray-700 mb-1">Size / Dimension</label>
                    <input type="text" name="size" id="size"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        value="{{ old('size') }}" placeholder="e.g. Ring Size 7, 18 inch Chain">
                </div>

                <!-- Gemstone Type (Optional) -->
                <div>
                    <label for="gemstone_type" class="block text-sm font-medium text-gray-700 mb-1">Gemstone Type</label>
                    <input type="text" name="gemstone_type" id="gemstone_type"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        value="{{ old('gemstone_type') }}" placeholder="e.g. Ruby, Sapphire">
                </div>

                <!-- Origin (Optional) -->
                <div>
                    <label for="origin" class="block text-sm font-medium text-gray-700 mb-1">Origin</label>
                    <input type="text" name="origin" id="origin"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        value="{{ old('origin') }}" placeholder="e.g. Ceylon, Colombia">
                </div>

                <!-- Clarity (Optional) -->
                <div>
                    <label for="clarity" class="block text-sm font-medium text-gray-700 mb-1">Clarity</label>
                    <input type="text" name="clarity" id="clarity"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                        value="{{ old('clarity') }}" placeholder="e.g. VVS1, Eye Clean">
                </div>

                <!-- Featured Checkbox -->
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="is_featured" id="is_featured"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5" {{ old('is_featured') ? 'checked' : '' }}>
                    <label for="is_featured" class="ml-2 block text-sm font-medium text-gray-700">Mark as Featured
                        Product</label>
                </div>

                <!-- Atelier Checkbox -->
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="is_atelier" id="is_atelier"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5" {{ old('is_atelier') ? 'checked' : '' }}>
                    <label for="is_atelier" class="ml-2 block text-sm font-medium text-gray-700">Show in The Atelier
                        Section</label>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                    required>{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Special Comments -->
            <div class="mt-6">
                <label for="special_comments" class="block text-sm font-medium text-gray-700 mb-1">Special Comments</label>
                <textarea name="special_comments" id="special_comments" rows="3"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50"
                    placeholder="e.g. Unique inclusions, specific certification details...">{{ old('special_comments') }}</textarea>
                @error('special_comments') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Product Images --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Images</label>
                <div
                    class="mt-1 px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg bg-white/30 hover:bg-white/50 transition-colors">

                    {{-- Default state: large icon + instruction --}}
                    <div id="img-default-text" class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"
                            aria-hidden="true">
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

                    {{-- JS Preview Grid --}}
                    <div id="new-img-preview-grid" class="hidden mt-4 flex flex-wrap gap-3"></div>
                </div>
                @error('image_files.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                {{-- Imported images from GemLightbox (shown as previews, sent as hidden URL inputs) --}}
                <div id="gl-img-preview-grid" class="mt-3 flex flex-wrap gap-3 empty:mt-0"></div>

            </div>


            {{-- Product Video --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Product Video <span class="text-gray-400 font-normal">(optional — plays on hover in shop)</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg bg-white/30 hover:bg-white/50 transition-colors cursor-pointer relative"
                    id="video-drop-zone">
                    <div class="space-y-1 text-center" id="video-upload-placeholder">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="video_file"
                                class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                <span>Upload a video</span>
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
                            class="mt-2 text-xs text-red-500 hover:text-red-700 underline">Remove</button>
                    </div>
                </div>
                @error('video_file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                {{-- GemLightbox imported video preview --}}
                <div id="gl-video-preview-wrap" class="hidden mt-3 p-3 bg-indigo-50 rounded-xl flex items-center gap-4">
                    <video id="gl-video-preview" src="" controls muted class="h-24 rounded-lg shadow"></video>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-indigo-700 font-space-mono">Imported from GemLightbox</p>
                        <p class="text-xs text-gray-500 mt-1">This video will be downloaded and saved when you submit the
                            form.</p>
                    </div>
                    <button type="button" onclick="clearGlVideo()"
                        class="text-red-400 hover:text-red-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Product Certificate --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Certificate of Gem / Product <span class="text-gray-400 font-normal">(optional — PDF or Image)</span>
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
                                <span>Upload certificate</span>
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
                            class="mt-2 text-xs text-red-500 hover:text-red-700 underline">Remove</button>
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
                    <span id="save-text">Save Product</span>
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
                if (text) text.textContent = 'Saving...';
            }
        });

        // ... existing sortable scripts ...
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
            // ============================================================
            // 0. Image Reordering Initialization
            // ============================================================
            document.addEventListener('DOMContentLoaded', () => {
                const glGrid = document.getElementById('gl-img-preview-grid');
                if (glGrid) {
                    Sortable.create(glGrid, {
                        animation: 150,
                        ghostClass: 'bg-indigo-100',
                        onEnd: () => { updateGlImageOrder(); }
                    });
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


                function updateGlImageOrder() {
                const orderInput = document.getElementById('image-order-input');
                const grid = document.getElementById('gl-img-preview-grid');
                if (orderInput && grid) {
                    const items = grid.querySelectorAll('[data-id]');
                    const order = Array.from(items).map(item => item.getAttribute('data-id'));
                orderInput.value = JSON.stringify(order);
                }
            }

                // ============================================================
                // GemLightbox Import
                // ============================================================
                let glImportedImages = []; // [{url, index}]
                let glImportedVideo  = null;

                async function importFromGemLightbox() {
                const url    = document.getElementById('gl-url-input').value.trim();
                const btn    = document.getElementById('gl-import-btn');
                const spinner= document.getElementById('gl-spin');
                const errBox = document.getElementById('gl-error');
                const okBox  = document.getElementById('gl-success');

                errBox.classList.add('hidden'); errBox.textContent = '';
                okBox.classList.add('hidden');  okBox.textContent  = '';

                if (!url) {errBox.textContent = 'Please enter a GemLightbox URL.'; errBox.classList.remove('hidden'); return; }

                btn.disabled = true;
                spinner.classList.remove('hidden');

                try {
                    const res = await fetch('{{ route("admin.gemlightbox.import") }}', {
                    method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                'X-CSRF-TOKEN' : '{{ csrf_token() }}',
                'Accept'       : 'application/json',
                        },
                body: JSON.stringify({url}),
                    });

                const data = await res.json();

                if (!res.ok || data.error) {
                    errBox.textContent = data.error || 'Import failed.';
                errBox.classList.remove('hidden');
                return;
                    }

                // ── Fill text fields ─────────────────────────────────────
                if (data.title) {
                        const nameInput = document.getElementById('name');
                if (nameInput && !nameInput.value) nameInput.value = data.title;
                    }
                if (data.description) {
                        const descInput = document.getElementById('description');
                if (descInput && !descInput.value) descInput.value = data.description;
                    }

                // ── Show imported images ──────────────────────────────────
                glImportedImages = [];
                document.getElementById('gl-hidden-images').innerHTML = '';
                const glGrid = document.getElementById('gl-img-preview-grid');
                glGrid.innerHTML = '';

                    (data.images || []).forEach((imgUrl, i) => {
                    glImportedImages.push({ url: imgUrl, idx: i });

                // Hidden input so the server knows which URLs to download
                const hidden = document.createElement('input');
                hidden.type  = 'hidden';
                hidden.name  = 'gl_images[]';
                hidden.id    = 'gl-img-hidden-' + i;
                hidden.value = imgUrl;
                document.getElementById('gl-hidden-images').appendChild(hidden);

                // Thumbnail preview
                const wrap = document.createElement('div');
                wrap.className = 'relative flex-shrink-0 cursor-move';
                wrap.id = 'gl-img-wrap-' + i;
                wrap.setAttribute('data-id', imgUrl);
                wrap.innerHTML = `
                <img src="${imgUrl}" class="h-24 w-24 object-cover rounded-xl border-2 border-indigo-300 shadow" onerror="this.src='https://placehold.co/96x96/e0e7ff/4f46e5?text=IMG'">
                    <div class="absolute top-1 left-1 bg-indigo-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded font-space-mono">GL</div>
                    <button type="button" onclick="removeGlImage(${i})"
                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow hover:bg-red-600 hover:scale-110 transition-all z-10">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>`;
                    glGrid.appendChild(wrap);
                    });
                    updateGlImageOrder();


                    // ── Show imported video ───────────────────────────────────
                    if (data.video) {
                        glImportedVideo = data.video;
                    document.getElementById('gl-hidden-video').value = data.video;
                    const vidEl = document.getElementById('gl-video-preview');
                    vidEl.src = data.video;
                    document.getElementById('gl-video-preview-wrap').classList.remove('hidden');
                    }

                    const count = (data.images || []).length + (data.video ? 1 : 0);
                    okBox.textContent = `✓ Imported ${count} media file${count !== 1 ? 's' : ''} from GemLightbox!`;
                    okBox.classList.remove('hidden');

                } catch (e) {
                        errBox.textContent = 'Network error. Please try again.';
                    errBox.classList.remove('hidden');
                } finally {
                        btn.disabled = false;
                    spinner.classList.add('hidden');
                }
            }

                    function removeGlImage(idx) {
                const hidden = document.getElementById('gl-img-hidden-' + idx);
                    if (hidden) hidden.remove();
                    const wrap   = document.getElementById('gl-img-wrap-' + idx);
                    if (wrap) wrap.remove();
            }

                    function clearGlVideo() {
                        glImportedVideo = null;
                    document.getElementById('gl-hidden-video').value = '';
                    document.getElementById('gl-video-preview').src = '';
                    document.getElementById('gl-video-preview-wrap').classList.add('hidden');
            }

            // Also allow pressing Enter in the URL input to trigger import
            document.addEventListener('DOMContentLoaded', () => {
                const glInput = document.getElementById('gl-url-input');
                    if (glInput) {
                        glInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); importFromGemLightbox(); } });
                }
            });

                    // ============================================================
                    // Image Upload → Inline Preview with X Remove Buttons
                    // ============================================================
                    let newImageFiles = [];

                    const imgInput          = document.getElementById('image_files');
                    const previewGrid       = document.getElementById('new-img-preview-grid');

                    if (imgInput) {
                        imgInput.addEventListener('change', function () {
                            Array.from(this.files).forEach(f => newImageFiles.push(f));
                            renderNewImagePreviews();
                        });
            }

                    function renderNewImagePreviews() {
                if (!previewGrid) return;
                    previewGrid.innerHTML = '';

                    if (newImageFiles.length === 0) {
                        previewGrid.classList.add('hidden');
                    const addMoreLabel = document.getElementById('img-add-more-label');
                    const imgDefaultText = document.getElementById('img-default-text');
                    if (addMoreLabel) addMoreLabel.classList.add('hidden');
                    if (imgDefaultText) imgDefaultText.classList.remove('hidden');
                    if (imgInput) imgInput.value = '';
                    return;
                }

                    previewGrid.classList.remove('hidden');

                    const addMoreLabel = document.getElementById('img-add-more-label');
                    const imgDefaultText = document.getElementById('img-default-text');
                    if (imgDefaultText) imgDefaultText.classList.add('hidden');
                    if (addMoreLabel) addMoreLabel.classList.remove('hidden');

                    newImageFiles.forEach(function (file, idx) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const wrapper = document.createElement('div');
                    wrapper.className = 'relative flex-shrink-0 cursor-move';
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
                } catch (e) { /* DataTransfer not supported */ }
            }

            // ---- Video Preview ----
            const videoInput = document.getElementById('video_file');
            const videoPreview = document.getElementById('video-preview');
            const videoPreviewContainer = document.getElementById('video-preview-container');
            const videoUploadPlaceholder = document.getElementById('video-upload-placeholder');
            const videoFileName = document.getElementById('video-file-name');

            if (videoInput) {
                videoInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) return;
                    const url = URL.createObjectURL(file);
                    videoPreview.src = url;
                    videoFileName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                    videoPreviewContainer.classList.remove('hidden');
                    videoUploadPlaceholder.classList.add('hidden');
                });
            }

            function clearVideoPreview() {
                if (videoInput) videoInput.value = '';
                if (videoPreview) { videoPreview.src = ''; videoPreview.load(); }
                videoPreviewContainer.classList.add('hidden');
                videoUploadPlaceholder.classList.remove('hidden');
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
                    certUploadPlaceholder.classList.add('hidden');
                });
            }

            function clearCertPreview() {
                if (certInput) certInput.value = '';
                certPreviewContainer.classList.add('hidden');
                certUploadPlaceholder.classList.remove('hidden');
            }

            document.addEventListener('DOMContentLoaded', function() {
                const categories = @json($categories);
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
                        // If weight or caret changed, update Gold Cost
                        // AND update Selling Price by the same delta to preserve profit
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
                        goldWeightContainer.style.display = 'none';
                        goldCostContainer.style.display = 'none';
                        gemCostContainer.style.display = 'block';
                        caretRangeContainer.style.display = 'none'; // Never show caret for loose gems
                    } else {
                        // Jewelry: Show both
                        goldWeightContainer.style.display = 'block';
                        goldCostContainer.style.display = 'block';
                        gemCostContainer.style.display = 'block';
                    }
                    // calculatePrices(); // Removed auto-triggering on every visibility update to avoid unwanted resets
                }




                // Filter main categories based on product type
                function filterCategoriesByType() {
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

                    // Re-select if still valid
                    if (currentMainId) {
                        const stillExists = Array.from(mainCatSelect.options).some(o => o.value == currentMainId);
                        if (stillExists) {
                            mainCatSelect.value = currentMainId;
                        } else {
                            mainCatSelect.value = "";
                            leafCatSelect.innerHTML = '<option value="">Select Sub Category</option>';
                            leafCatSelect.disabled = true;
                        }
                    }
                }

                mainCatSelect.addEventListener('change', function() {
                    const mainId = this.value;
                    const mainCat = categories.find(c => c.id == mainId);

                    // Populate Level 2 (Directly under Main)
                    leafCatSelect.innerHTML = '<option value="">Select Sub Category</option>';

                    if (mainCat && mainCat.children.length > 0) {
                        leafCatSelect.disabled = false;
                        mainCat.children.forEach(child => {
                            const opt = document.createElement('option');
                            opt.value = child.id;
                            opt.textContent = child.name;
                            leafCatSelect.appendChild(opt);
                        });
                    } else {
                        leafCatSelect.disabled = true;
                    }

                    updateFieldVisibility();
                });

                productTypeSelect.addEventListener('change', function() {
                    filterCategoriesByType();
                    updateFieldVisibility();
                });

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

                // Initialize
                filterCategoriesByType();
                updateFieldVisibility();
                updateProfitDisplay();
            });
    </script>
@endpush