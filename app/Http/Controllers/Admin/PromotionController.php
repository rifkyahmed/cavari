<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        \App\Services\PromotionService::sync();
        $promotions = Promotion::latest()->paginate(15);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::where('is_hidden', false)->get(['id', 'name']);
        
        return view('admin.promotions.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $targetIds = [];
        if ($request->type === 'category') {
            $targetIds = $request->category_ids ?? [];
        } elseif ($request->type === 'product') {
            $targetIds = $request->product_ids ?? [];
        }
        $request->merge(['target_ids' => $targetIds]);

        $request->validate([
            'name' => 'required',
            'type' => 'required|in:category,product,global',
            'target_ids' => 'nullable|array',
            'discount_percentage' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $promotion = Promotion::create($request->all());
        $this->syncPromotions();

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion created successfully. Offers applied where applicable.');
    }

    public function edit(Promotion $promotion)
    {
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::where('is_hidden', false)->get(['id', 'name']);
        
        return view('admin.promotions.edit', compact('promotion', 'categories', 'products'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $targetIds = [];
        if ($request->type === 'category') {
            $targetIds = $request->category_ids ?? [];
        } elseif ($request->type === 'product') {
            $targetIds = $request->product_ids ?? [];
        }
        $request->merge(['target_ids' => $targetIds]);

        $request->validate([
            'name' => 'required',
            'type' => 'required|in:category,product,global',
            'target_ids' => 'nullable|array',
            'discount_percentage' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $promotion->update($request->all());
        $this->syncPromotions();

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion updated successfully. Offers synced.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        $this->syncPromotions();
        return redirect()->route('admin.promotions.index')->with('success', 'Promotion deleted successfully.');
    }

    public function toggleActive(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);
        $this->syncPromotions();
        return redirect()->route('admin.promotions.index')->with('success', 'Promotion status updated successfully.');
    }

    private function syncPromotions()
    {
        \App\Services\PromotionService::sync();
    }
}
