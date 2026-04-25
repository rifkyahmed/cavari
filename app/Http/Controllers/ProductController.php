<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        \App\Services\PromotionService::ensureSynced();
        $query = Product::where('is_hidden', false);

        $this->applyFilters($query, $request);

        $products = $query->with('category')->paginate(12);
        $categories = Category::all();
        $filterOptions = cache()->remember('product_filter_options', 3600, function() {
            return $this->getFilterOptions();
        });

        if ($request->ajax()) {
            return view('products.partials.product-grid', compact('products', 'categories', 'filterOptions'));
        }

        return view('products.index', compact('products', 'categories', 'filterOptions'));
    }

    public function quickView($id)
    {
        $product = Product::where('is_hidden', false)->with('category')->findOrFail($id);
        return view('products.partials.quick-view', compact('product'));
    }

    public function show($slug)
    {
        \App\Services\PromotionService::ensureSynced();
        $product = Product::where('slug', $slug)->where('is_hidden', false)->with(['reviews.user', 'category'])->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $goldPrice = \App\Models\Setting::get('gold_price', 0);
        $inCart = isset(session('cart')[$product->id]);

        return view('products.show', compact('product', 'relatedProducts', 'goldPrice', 'inCart'));
    }

    public function gems(Request $request)
    {
        \App\Services\PromotionService::ensureSynced();
        $categories = Category::all();
        
        $query = Product::where('is_hidden', false)
                        ->where(function($q) {
                            $q->where('product_type', 'gem')
                              ->orWhereHas('category', function($c){
                                  $c->where('slug', 'loose-gems');
                              });
                        });

        $this->applyFilters($query, $request);

        $products = $query->with('category')->paginate(12);
        $filterOptions = cache()->remember('product_filter_options', 3600, function() {
            return $this->getFilterOptions();
        });
        
        if ($request->ajax()) {
            return view('products.partials.product-grid', compact('products', 'categories', 'filterOptions'));
        }
        
        return view('products.index', compact('products', 'categories', 'filterOptions'));
    }

    public function jewelry(Request $request)
    {
        \App\Services\PromotionService::ensureSynced();
        $categories = Category::all();
        
        $query = Product::where('is_hidden', false)
                        ->where(function($q) {
                            $q->where('product_type', 'jewelry')
                              ->orWhere(function($sub) {
                                   $sub->whereHas('category', function($c){
                                      $c->where('slug', '!=', 'loose-gems');
                                   });
                                   $sub->whereNull('product_type');
                              });
                        });

        $this->applyFilters($query, $request);

        $products = $query->with('category')->paginate(12);
        $filterOptions = cache()->remember('product_filter_options', 3600, function() {
            return $this->getFilterOptions();
        });
        
        if ($request->ajax()) {
            return view('products.partials.product-grid', compact('products', 'categories', 'filterOptions'));
        }
        
        return view('products.index', compact('products', 'categories', 'filterOptions'));
    }

    private function applyFilters($query, Request $request)
    {
        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by Category
        if ($request->filled('category')) {
            $category = $request->category;
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category)->orWhere('name', $category);
            });
        }

        // Product Type - Enforce strict separation
        if ($request->filled('type')) {
            $type = $request->type;
            $query->where(function($q) use ($type) {
                if ($type === 'gem') {
                    // Show only loose gems
                    $q->where('product_type', 'gem')
                      ->orWhereHas('category', function($c) {
                          $c->where('slug', 'loose-gems');
                      });
                } elseif ($type === 'jewelry') {
                    // Show only jewelry (exclude loose gems)
                    $q->where('product_type', 'jewelry')
                      ->orWhere(function($sub) {
                          $sub->whereNull('product_type')
                              ->whereHas('category', function($c) {
                                  $c->where('slug', '!=', 'loose-gems');
                              });
                      });
                }
            });
        } else {
            // When no type filter is applied, show all products (used on main shop)
            // This ensures default behavior shows everything
        }

        // Price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Gem Specifics
        if ($request->filled('gemstone')) {
            $query->where('gemstone_type', $request->gemstone);
        }
        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }
        if ($request->filled('shape')) {
            $query->where('shape', $request->shape);
        }
        if ($request->filled('treatment')) {
            $query->where('treatment', $request->treatment);
        }
        if ($request->filled('min_weight')) {
             $query->where('weight', '>=', $request->min_weight);
        }
        if ($request->filled('max_weight')) {
             $query->where('weight', '<=', $request->max_weight);
        }

        // Jewelry Specifics
        if ($request->filled('metal')) {
            $metal = $request->metal;
            $query->where(function($q) use ($metal) {
                // Check if it's a category slug (Gem & Gold, etc)
                $q->where('metal', $metal)
                  ->orWhereHas('category.parent', function($p) use ($metal) {
                      $p->where('slug', $metal);
                  })
                  ->orWhereHas('category', function($c) use ($metal) {
                      $c->where('slug', $metal);
                  });
            });
        }

        // General Status
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }
        if ($request->boolean('new_arrivals')) {
            $query->latest();
        }
        if ($request->boolean('best_sellers')) {
             $query->withCount('orderItems')->orderByDesc('order_items_count');
        }
        if ($request->boolean('discount')) {
            $query->whereNotNull('original_price')->whereColumn('price', '<', 'original_price');
        }
        // Availability
        if ($request->filled('availability')) {
            if ($request->availability === 'in_stock') {
                $query->where('stock', '>', 0);
            }
        }
    }

    private function getFilterOptions()
    {
        return [
            'gemstones' => Product::where('is_hidden', false)->distinct()->whereNotNull('gemstone_type')->pluck('gemstone_type'),
            'colors' => Product::where('is_hidden', false)->distinct()->whereNotNull('color')->pluck('color'),
            'shapes' => Product::where('is_hidden', false)->distinct()->whereNotNull('shape')->pluck('shape'),
            'treatments' => Product::where('is_hidden', false)->distinct()->whereNotNull('treatment')->pluck('treatment'),
            'metals' => Product::where('is_hidden', false)->distinct()->whereNotNull('metal')->pluck('metal'),
        ];
    }
}
