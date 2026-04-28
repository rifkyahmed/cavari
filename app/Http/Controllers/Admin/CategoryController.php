<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index()
    {
        // Only show the "Leaf" categories (those that don't have children themselves)
        // This keeps the list clean by hiding the parent levels
        $categories = Category::doesntHave('children')->with('parent.parent')->latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        // Only show root categories as parents to limit hierarchy to 2 levels (Root -> Sub)
        $parents = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:jewelry,loose_gem',
            'parent_id' => 'nullable', // Could be an numeric ID or a string name
        ]);

        $parentId = $request->parent_id;

        // If parent_id is sent as a string name instead of a numeric ID
        if ($parentId && !is_numeric($parentId)) {
            // Find the root category for this type
            $root = Category::where('type', $request->type)->whereNull('parent_id')->first();
            
            // Look for existing category by name first to avoid duplicates
            $parent = Category::where('name', $parentId)->where('type', $request->type)->first();
            
            if (!$parent) {
                // Auto-create the parent if it doesn't exist. 
                // It should be a top-level category (parent_id = null), not nested under an existing root.
                $parent = Category::create([
                    'name' => $parentId,
                    'type' => $request->type,
                    'parent_id' => null, 
                    'slug' => Str::slug($parentId . '-' . $request->type . '-' . time())
                ]);
            }
            $parentId = $parent->id;
        }

        Category::create([
            'name' => $request->name,
            'type' => $request->type,
            'slug' => Str::slug($request->name . '-' . ($parentId ?: 'root') . '-' . time()),
            'parent_id' => $parentId,
        ]);

        Cache::forget('admin_dashboard_stats');
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        // Only show other root categories as potential parents
        $parents = Category::where('id', '!=', $category->id)->whereNull('parent_id')->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:jewelry,loose_gem',
            'parent_id' => 'nullable',
        ]);

        $parentId = $request->parent_id;

        if ($parentId && !is_numeric($parentId)) {
            $root = Category::where('type', $request->type)->whereNull('parent_id')->first();
            $parent = Category::where('name', $parentId)->where('type', $request->type)->first();
            
            if (!$parent) {
                // Auto-create as top-level
                $parent = Category::create([
                    'name' => $parentId,
                    'type' => $request->type,
                    'parent_id' => null,
                    'slug' => Str::slug($parentId . '-' . $request->type . '-' . time())
                ]);
            }
            $parentId = $parent->id;
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . ($parentId ?: 'root') . '-' . time()),
            'type' => $request->type,
            'parent_id' => $parentId,
        ]);

        Cache::forget('admin_dashboard_stats');
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated products.');
        }
        $category->delete();
        Cache::forget('admin_dashboard_stats');
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function destroyAll()
    {
        // Security check: only allow if not many products or after confirmation?
        // Let's just do it as requested
        Category::query()->delete();
        Cache::forget('admin_dashboard_stats');
        return redirect()->route('admin.categories.index')->with('success', 'All categories deleted successfully.');
    }
}
