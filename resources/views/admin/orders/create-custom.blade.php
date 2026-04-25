@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold font-space-mono text-gray-800">Create Multi-Item Custom Order</h1>
    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors font-space-mono text-sm uppercase">
        Cancel
    </a>
</div>

<div x-data="{ 
    products: [
        { id: Date.now(), type: 'jewelry', price: 0, cost: 0 }
    ],
    discount: 0,
    get subtotal() {
        return this.products.reduce((acc, p) => acc + (parseFloat(p.price) || 0), 0);
    },
    addProduct() {
        this.products.push({ id: Date.now(), type: 'jewelry', price: 0, cost: 0 });
    },
    removeProduct(index) {
        if (this.products.length > 1) {
            this.products.splice(index, 1);
        }
    }
}" class="space-y-6 pb-20">

    <form action="{{ route('admin.orders.store-custom') }}" method="POST" enctype="multipart/form-data" id="custom-order-form">
        @csrf

        <template x-for="(product, index) in products" :key="product.id">
            <div class="glass-panel p-8 mb-6 relative border-t-4 border-indigo-500">
                
                <!-- Remove Button -->
                <button type="button" @click="removeProduct(index)" x-show="products.length > 1" class="absolute top-4 right-4 text-red-400 hover:text-red-600 transition-colors bg-red-50 p-2 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>

                <div class="flex items-center gap-3 mb-6">
                    <span class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm" x-text="index + 1"></span>
                    <h2 class="text-xl font-bold font-space-mono text-gray-800 uppercase tracking-tight">Product Specification</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Type Selection -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-widest text-[10px]">Product Nature <span class="text-red-500">*</span></label>
                        <select :name="`products[${index}][product_type]`" x-model="product.type" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50 font-instrument" required>
                            <option value="jewelry">Finished Jewelry</option>
                            <option value="loose_gem">Loose Gemstone</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-widest text-[10px]">Category <span class="text-red-500">*</span></label>
                        <select :name="`products[${index}][category_id]`" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50 font-instrument" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-widest text-[10px]">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" :name="`products[${index}][name]`" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50 font-instrument" placeholder="e.g. 2ct Cushion Cut Sapphire Ring" required>
                    </div>

                    <!-- Pricing Context -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-4 rounded-xl border border-dashed border-gray-200">
                        <!-- Internal Cost -->
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-1 uppercase tracking-widest text-[10px]">Internal Cost (Optional)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300">$</span>
                                <input type="number" step="0.01" :name="`products[${index}][cost_price]`" x-model.number="product.cost" class="w-full pl-8 rounded-xl border-gray-300 border-dashed focus:border-indigo-300 focus:ring-indigo-300 bg-white/30 font-instrument text-gray-500 placeholder-gray-200" placeholder="0.00">
                            </div>
                        </div>

                        <!-- Selling Price -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-widest text-[10px]">Selling Price (USD) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">$</span>
                                <input type="number" step="0.01" :name="`products[${index}][price]`" x-model.number="product.price" class="w-full pl-8 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white font-instrument font-bold" placeholder="0.00" required>
                            </div>
                            <div class="mt-1 flex justify-between px-1">
                                <span class="text-[9px] uppercase font-bold tracking-tighter" :class="(product.price - product.cost) > 0 ? 'text-green-500' : 'text-red-400'">
                                    Profit Preview: <span x-text="'$' + (product.price - product.cost).toFixed(2)"></span>
                                </span>
                                <span class="text-[9px] uppercase font-bold tracking-tighter text-gray-400" x-show="product.price > 0">
                                    Margin: <span x-text="Math.round(((product.price - product.cost) / product.price) * 100) + '%'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Weight Fields - Contextual -->
                    <div x-show="product.type === 'jewelry'">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-widest text-[10px]">Gold Weight (grams) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" :name="`products[${index}][gold_weight]`" :required="product.type === 'jewelry'" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50 font-instrument" placeholder="0.00">
                    </div>

                    <div x-show="product.type === 'loose_gem'">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-widest text-[10px]">Gem Weight (carats) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" :name="`products[${index}][gem_weight]`" :required="product.type === 'loose_gem'" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50 font-instrument" placeholder="0.00">
                    </div>

                    <!-- Shared Specs -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-widest text-[10px]">Size / Origin</label>
                        <input type="text" :name="`products[${index}][size]`" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50 font-instrument" placeholder="e.g. Ring Size 7 or Sri Lanka">
                    </div>
                </div>

                <!-- Collapsible / Optional Details -->
                <div x-data="{ open: false }" class="mt-8 border-t border-gray-100 pt-6">
                    <button type="button" @click="open = !open" class="flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-widest">
                        <span x-text="open ? '- Hide Optional Details' : '+ Show Optional Details (Description, Media, Comments)'"></span>
                    </button>

                    <div x-show="open" x-collapse x-cloak class="mt-6 space-y-6">
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-widest text-[10px]">Customer Facing Description</label>
                            <textarea :name="`products[${index}][description]`" rows="2" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50 font-instrument" placeholder="Narrative for the customer..."></textarea>
                        </div>

                        <!-- Special Comments -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-widest text-[10px]">Special Internal Comments</label>
                            <textarea :name="`products[${index}][special_comments]`" rows="2" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white/50 font-instrument" placeholder="Notes on inclusions, flaws, or specific sources..."></textarea>
                        </div>

                        <!-- Media -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{
                            imagePreviews: [],
                            init() {
                                this.imagePreviews = [];
                            },
                            handleImageChange(e) {
                                const files = Array.from(e.target.files);
                                files.forEach(file => {
                                    this.imagePreviews.push({
                                        id: Date.now() + Math.random(),
                                        url: URL.createObjectURL(file),
                                        file: file
                                    });
                                });
                                // Reset the file input so the same file can be chose again if removed
                                // But wait, we need the files in the input for submission if we don't use hidden inputs.
                                // Standard file inputs don't allow programmatic removal easily.
                                // We'll stick to a simple multiple input with preview for now as a middle ground.
                            }
                        }">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest text-[10px]">Product Images</label>
                                <div class="flex flex-wrap gap-4 mb-3" x-show="imagePreviews.length > 0">
                                    <template x-for="(img, imgIndex) in imagePreviews" :key="img.id">
                                        <div class="relative w-24 h-24 group">
                                            <img :src="img.url" class="w-full h-full object-cover rounded-xl border border-indigo-100 shadow-sm">
                                            <button type="button" @click="imagePreviews.splice(imgIndex, 1)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <input type="file" :name="`products[${index}][image_files][]`" @change="handleImageChange" multiple accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                <p class="text-[9px] text-gray-400 mt-2 font-space-mono">Tip: Upload 2-3 high quality angles for the best customer experience.</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-widest text-[10px]">Product Video (Optional)</label>
                                <input type="file" :name="`products[${index}][video_file]`" accept="video/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Add Product Button -->
        <div class="mb-12">
            <button type="button" @click="addProduct()" class="w-full py-4 border-2 border-dashed border-indigo-200 rounded-2xl text-indigo-400 font-bold font-space-mono text-sm uppercase tracking-widest hover:bg-indigo-50 hover:border-indigo-400 hover:text-indigo-600 transition-all flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Another Product to this Order
            </button>
        </div>

        <!-- Summary & Discount Area -->
        <div class="glass-panel p-8 bg-black/5 border-t-4 border-black">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                <!-- Discount Input -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-widest text-[10px]">Adjust Global Discount (USD)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-red-500 font-bold">-$</span>
                        <input type="number" step="0.01" name="discount" x-model.number="discount" class="w-full pl-12 rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 bg-white font-instrument text-red-600 font-bold" placeholder="0.00">
                    </div>
                </div>

                <!-- Live Price Summary -->
                <div class="space-y-3 pt-4 border-t border-black/5 md:border-t-0">
                    <div class="flex justify-between items-center text-gray-500">
                        <span class="font-space-mono text-[10px] uppercase tracking-widest">Subtotal</span>
                        <span class="font-instrument font-medium" x-text="'$' + subtotal.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between items-center text-red-500">
                        <span class="font-space-mono text-[10px] uppercase tracking-widest">Discount</span>
                        <span class="font-instrument font-bold" x-text="'-$' + (discount || 0).toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-black/10">
                        <span class="font-space-mono text-xs font-bold uppercase tracking-widest text-black">Final Order Total</span>
                        <span class="text-2xl font-bold font-gloock text-black" x-text="'$' + Math.max(0, subtotal - (discount || 0)).toFixed(2)"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 flex justify-end gap-4">
            <button type="submit" class="px-10 py-5 bg-black text-white font-bold rounded-xl shadow-2xl hover:bg-zinc-900 transition-all font-space-mono text-sm uppercase tracking-[0.2em] transform active:scale-95 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                Generate Payment Link
            </button>
        </div>
    </form>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
