<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Round selling price up so it always ends with 0 (e.g. 1281 -> 1290).
     */
    private function roundUpPriceToTen(float $price): float
    {
        return ceil($price / 10) * 10;
    }

    /**
     * Generate a unique slug for a product.
     * If the base slug already exists, appends -2, -3, etc.
     */
    private function uniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $n = 2;
        while (
            Product::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base . '-' . $n++;
        }
        return $slug;
    }

    public function index(Request $request)
    {
        $query = Product::with('category')
            ->withSum('orderItems as total_sold', 'quantity')
            ->withSum([
                'orderItems as recent_sold' => function ($q) {
                    $q->where('order_items.created_at', '>', now()->subDays(30));
                }
            ], 'quantity')
            ->withSum([
                'orderItems as last_90_sold' => function ($q) {
                    $q->where('order_items.created_at', '>', now()->subDays(90));
                }
            ], 'quantity');

        if ($request->has('dead_stock')) {
            $query->deadStock();
        }

        $products = $query->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::with('children.children')->whereNull('parent_id')->get();
        $goldPrice = \App\Models\Setting::get('gold_price', 0);
        return view('admin.products.create', compact('categories', 'goldPrice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'product_type' => 'required|in:jewelry,loose_gem',
            'price' => 'required|numeric',
            'gold_cost_price' => 'nullable|numeric',
            'gem_cost_price' => 'nullable|numeric',
            'stock' => 'required|integer',
            'description' => 'required',
            'image_files.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_file' => 'nullable|mimes:mp4,webm,mov,avi|max:51200',
            'certificate_file' => 'nullable|mimes:pdf,jpeg,png,jpg,webp|max:5120',
        ]);

        // 1. Handle Remote GemLightbox Assets (Parallel Download)
        $urls = [];
        if ($request->filled('image_order')) {
            $urls = json_decode($request->input('image_order'), true) ?? [];
        } elseif ($request->filled('gl_images')) {
            $urls = (array) $request->input('gl_images');
        }

        $glVideo = $request->input('gl_video');
        // We handle the video separately from images to avoid it ending up in the images array

        $savedFiles = $this->downloadMultipleFiles($urls, 'products/images');

        $imagePaths = [];
        $videoPath = null;

        // If we have a GemLightbox video, prioritize Cloudinary for instant optimization
        if ($glVideo) {
            try {
                $result = Cloudinary::uploadApi()->upload($glVideo, [
                    'resource_type' => 'video',
                    'folder' => 'products/videos',
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto',
                    ]
                ]);
                $videoPath = $result['secure_url'];
                // Ensure extension for browser playback
                if (!\Illuminate\Support\Str::endsWith($videoPath, ['.mp4', '.webm', '.ogg'])) {
                    $videoPath .= '.mp4';
                }
            } catch (\Exception $e) {
                Log::error('Cloudinary GL Video Upload Failed: ' . $e->getMessage());
                // Fallback: Download locally if Cloudinary fails or is not configured
                $videoDownloadResult = $this->downloadMultipleFiles([$glVideo], 'products/videos');
                if (isset($videoDownloadResult[$glVideo])) {
                    $videoPath = '/storage/' . $videoDownloadResult[$glVideo];
                } else {
                    $videoPath = $glVideo; // Remote URL as final fallback
                }
            }
        }

        // Only process images in the main download loop
        foreach ($urls as $url) {
            if (isset($savedFiles[$url])) {
                $imagePaths[] = '/storage/' . $savedFiles[$url];
            }
        }

        // 2. Locally uploaded images (append)
        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $image) {
                $imagePaths[] = '/storage/' . $image->store('products', 'public');
            }
        }

        if ($request->hasFile('video_file')) {
            try {
                $result = Cloudinary::uploadApi()->upload($request->file('video_file')->getRealPath(), [
                    'resource_type' => 'video',
                    'folder' => 'products/videos',
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto',
                    ]
                ]);
                $videoPath = $result['secure_url'];
                if (!\Illuminate\Support\Str::endsWith($videoPath, ['.mp4', '.webm', '.ogg'])) {
                    $videoPath .= '.mp4';
                }
            } catch (\Exception $e) {
                Log::error('Cloudinary Manual Video Upload Failed: ' . $e->getMessage());
                // Fallback to local storage if Cloudinary fails
                $videoPath = '/storage/' . $request->file('video_file')->store('products/videos', 'public');
            }
        }

        $certificatePath = null;
        if ($request->hasFile('certificate_file')) {
            $certificatePath = '/storage/' . $request->file('certificate_file')->store('products/certificates', 'public');
        }

        Product::create([
            'category_id' => $request->category_id,
            'product_type' => $request->product_type,
            'name' => $request->name,
            'slug' => $this->uniqueSlug($request->name),
            'description' => $request->description,
            'price' => $this->roundUpPriceToTen((float) $request->price),
            'cost_price' => ($request->gold_cost_price ?? 0) + ($request->gem_cost_price ?? 0),
            'stock' => $request->stock,
            'gemstone_type' => $request->gemstone_type,
            'origin' => $request->origin,
            'clarity' => $request->clarity,
            'special_comments' => $request->special_comments,
            'gold_weight' => $request->gold_weight ?? 0,
            'gem_weight' => $request->gem_weight ?? 0,
            'size' => $request->size,
            'images' => $imagePaths,
            'video' => $videoPath,
            'is_featured' => $request->has('is_featured'),
            'is_atelier' => $request->has('is_atelier'),
            'caret_range' => $request->caret_range,
            'gold_cost_price' => $request->gold_cost_price,
            'gem_cost_price' => $request->gem_cost_price,
            'certificate' => $certificatePath,

        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Parallel download using curl_multi for high-speed GemLightbox imports.
     */
    private function downloadMultipleFiles(array $urls, string $folder = 'products'): array
    {
        if (empty($urls))
            return [];

        $mh = curl_multi_init();
        $requests = [];
        $results = [];

        foreach ($urls as $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_multi_add_handle($mh, $ch);
            $requests[$url] = $ch;
        }

        $active = null;
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        foreach ($requests as $url => $ch) {
            $content = curl_multi_getcontent($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);

            if ($httpCode == 200 && $content) {
                $urlPath = parse_url($url, PHP_URL_PATH);
                $ext = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));
                $ext = preg_replace('/[^a-z0-9].*/', '', $ext);

                // Determine folder based on content type; override with provided folder for videos if needed
                if (empty($folder)) {
                    $folder = (str_contains($url, 'mp4') || str_contains($url, 'mov') || str_contains($url, 'webm'))
                        ? 'products/videos'
                        : 'products';
                }

                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'mp4', 'webm', 'mov'])) {
                    $ext = str_contains($url, 'mp4') ? 'mp4' : 'jpg';
                }

                $filename = $folder . '/' . uniqid('gl_') . '.' . $ext;
                Storage::disk('public')->put($filename, $content);
                $results[$url] = $filename;
            }
        }

        curl_multi_close($mh);
        return $results;
    }


    public function edit(Product $product)
    {
        $categories = Category::with('children.children')->whereNull('parent_id')->get();

        // Find the hierarchy for the current product category
        $currentLeaf = Category::find($product->category_id);
        $currentParent = $currentLeaf ? Category::find($currentLeaf->parent_id) : null;
        $currentMain = $currentParent ? Category::find($currentParent->parent_id) : ($currentLeaf && $currentLeaf->parent_id === null ? $currentLeaf : null);

        // If it's a 3-level hierarchy:
        // Level 3 category: $currentLeaf
        // Level 2 category: $currentParent
        // Level 1 category: $currentMain

        // Let's refine this logic to be robust for 1, 2, or 3 levels
        $hierarchy = [];
        $curr = $currentLeaf;
        while ($curr) {
            array_unshift($hierarchy, $curr->id);
            $curr = $curr->parent;
        }

        // hierarchy[0] = Level 1, hierarchy[1] = Level 2, hierarchy[2] = Level 3

        $goldPrice = \App\Models\Setting::get('gold_price', 0);
        return view('admin.products.edit', compact('product', 'categories', 'hierarchy', 'goldPrice'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'product_type' => 'required|in:jewelry,loose_gem',
            'price' => 'required|numeric',
            'gold_cost_price' => 'nullable|numeric',
            'gem_cost_price' => 'nullable|numeric',
            'stock' => 'required|integer',
            'description' => 'required',
            'image_files.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_file' => 'nullable|mimes:mp4,webm,mov,avi|max:51200',
            'certificate_file' => 'nullable|mimes:pdf,jpeg,png,jpg,webp|max:5120',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'product_type' => $request->product_type,
            'name' => $request->name,
            'slug' => $this->uniqueSlug($request->name, $product->id),
            'description' => $request->description,
            'price' => $this->roundUpPriceToTen((float) $request->price),
            'cost_price' => ($request->gold_cost_price ?? 0) + ($request->gem_cost_price ?? 0),
            'stock' => $request->stock,
            'gemstone_type' => $request->gemstone_type,
            'origin' => $request->origin,
            'clarity' => $request->clarity,
            'special_comments' => $request->special_comments,
            'gold_weight' => $request->gold_weight ?? 0,
            'gem_weight' => $request->gem_weight ?? 0,
            'size' => $request->size,
            'is_featured' => $request->has('is_featured'),
            'is_atelier' => $request->has('is_atelier'),
            'caret_range' => $request->caret_range,
            'gold_cost_price' => $request->gold_cost_price,
            'gem_cost_price' => $request->gem_cost_price,
            'certificate' => $product->certificate,

        ];

        // 1. Handle Existing Images & Order
        $imagePaths = [];
        if ($request->filled('image_order')) {
            $orderedPaths = json_decode($request->input('image_order'), true) ?? [];
            $removeIdx = $request->input('remove_images', []);

            foreach ($orderedPaths as $path) {
                // Find original index to check if marked for removal
                $originalIdx = array_search($path, $product->images);
                if ($originalIdx !== false && in_array($originalIdx, $removeIdx)) {
                    Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $path), '/'));
                    continue;
                }
                $imagePaths[] = $path;
            }
        } else {
            $imagePaths = $product->images ?? [];
        }


        // Append new uploads
        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = '/storage/' . $path;
            }
        }

        $data['images'] = $imagePaths;

        if ($request->hasFile('video_file')) {
            // Delete old LOCAL video if exists
            if ($product->video && !Str::startsWith($product->video, 'http')) {
                Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $product->video), '/'));
            }
            try {
                $result = Cloudinary::uploadApi()->upload($request->file('video_file')->getRealPath(), [
                    'resource_type' => 'video',
                    'folder' => 'products/videos',
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto',
                    ]
                ]);
                $data['video'] = $result['secure_url'];
                if (!\Illuminate\Support\Str::endsWith($data['video'], ['.mp4', '.webm', '.ogg'])) {
                    $data['video'] .= '.mp4';
                }
            } catch (\Exception $e) {
                Log::error('Cloudinary Update Video Upload Failed: ' . $e->getMessage());
                // Fallback to local storage if Cloudinary fails
                $data['video'] = '/storage/' . $request->file('video_file')->store('products/videos', 'public');
            }
        }

        // Allow clearing video
        if ($request->has('remove_video') && $request->remove_video == '1') {
            if ($product->video) {
                Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $product->video), '/'));
            }
            $data['video'] = null;
        }

        if ($request->hasFile('certificate_file')) {
            // Delete old certificate if exists
            if ($product->certificate) {
                Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $product->certificate), '/'));
            }
            $data['certificate'] = '/storage/' . $request->file('certificate_file')->store('products/certificates', 'public');
        }

        // Allow clearing certificate
        if ($request->has('remove_certificate') && $request->remove_certificate == '1') {
            if ($product->certificate) {
                Storage::disk('public')->delete(ltrim(str_replace('/storage/', '', $product->certificate), '/'));
            }
            $data['certificate'] = null;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function toggleVisibility($id)
    {
        $product = Product::findOrFail($id);
        $product->is_hidden = !$product->is_hidden;
        $product->save();

        return back()->with('success', 'Product visibility updated.');
    }

    public function destroy(Product $product)
    {
        // Delete images from storage (optional, depending on requirements)
        // if ($product->images && is_array($product->images)) {
        //     foreach ($product->images as $image) {
        //         Storage::disk('public')->delete(str_replace('/storage/', '', $image));
        //     }
        // }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }



    /**
     * Bulk delete selected products.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'integer|exists:products,id',
        ]);

        $ids = $request->input('selected');
        // Disable foreign key checks for mass deletion
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::whereIn('id', $ids)->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('admin.products.index')
            ->with('success', count($ids) . ' product(s) deleted successfully.');
    }

    /**
     * Delete all products (truncate).
     */
    public function destroyAll()
    {
        // Disable foreign key checks to allow truncation despite constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate(); // Truncate resets IDs and removes all rows
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('admin.products.index')
            ->with('success', 'All products have been deleted.');
    }
}
