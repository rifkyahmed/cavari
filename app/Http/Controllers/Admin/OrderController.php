<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Setting;

use App\Models\User;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Create Custom Order
     */
    public function createCustom()
    {
        $categories = \App\Models\Category::all();
        return view('admin.orders.create-custom', compact('categories'));
    }

    public function storeCustom(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string',
            'products.*.category_id' => 'required|exists:categories,id',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.product_type' => 'required|in:jewelry,loose_gem',
            'products.*.gold_weight' => 'required_if:products.*.product_type,jewelry|nullable|numeric|min:0',
            'products.*.gem_weight' => 'required_if:products.*.product_type,loose_gem|nullable|numeric|min:0',
            'products.*.images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'products.*.video' => 'nullable|mimes:mp4,webm,mov,avi|max:51200',
            'products.*.cost_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $order = Order::create([
            'user_id' => null, // Left null until claimed
            'status' => 'pending',
            'payment_status' => 'pending',
            'total_price' => 0, // Will update after adding items
            'is_custom' => true,
            'payment_link_uuid' => Str::uuid(),
            'discount' => $request->discount ?? 0,
        ]);

        $totalOrderPrice = 0;

        foreach ($request->products as $index => $productData) {
            $imagePaths = [];
            
            // Check for images for this specific product index
            // In the form, they should be named e.g. products[0][image_files][]
            if ($request->hasFile("products.{$index}.image_files")) {
                foreach ($request->file("products.{$index}.image_files") as $file) {
                    $path = $file->store('products', 'public');
                    $imagePaths[] = '/storage/' . $path;
                }
            }

            $videoPath = null;
            if ($request->hasFile("products.{$index}.video_file")) {
                $videoPath = '/storage/' . $request->file("products.{$index}.video_file")->store('products/videos', 'public');
            }

            // Create Order Item with all custom details instead of creating a ghost Product
            \App\Models\OrderItem::create([
                'order_id'       => $order->id,
                'product_id'     => null, // No real product entry
                'quantity'       => 1,
                'price'          => $productData['price'],
                'custom_name'    => $productData['name'],
                'custom_details' => [
                    'category_id'      => $productData['category_id'],
                    'product_type'     => $productData['product_type'],
                    'description'      => $productData['description'] ?? '',
                    'cost_price'       => $productData['cost_price'] ?? 0,
                    'gemstone_type'    => $productData['gemstone_type'] ?? null,
                    'origin'           => $productData['origin'] ?? null,
                    'clarity'          => $productData['clarity'] ?? null,
                    'special_comments' => $productData['special_comments'] ?? null,
                    'gold_weight'      => $productData['gold_weight'] ?? 0,
                    'gem_weight'       => $productData['gem_weight'] ?? 0,
                    'size'             => $productData['size'] ?? null,
                    'images'           => $imagePaths,
                    'video'            => $videoPath,
                ],
            ]);

            $totalOrderPrice += $productData['price'];
        }

        $finalPrice = max(0, $totalOrderPrice - ($request->discount ?? 0));
        $order->update(['total_price' => $finalPrice]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Custom order generated successfully.')
            ->with('show_payment_modal', true)
            ->with('new_order_id', $order->id);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $newOrder = null;
        if (session('new_order_id')) {
            $newOrder = Order::find(session('new_order_id'));
        }
        
        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->paginate(15);
            
        return view('admin.orders.index', compact('orders', 'status', 'newOrder'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'sometimes|in:pending,paid,failed,refunded',
        ]);

        $order = Order::findOrFail($id);
        
        $order->update([
            'status' => $request->status,
             // Update payment status if provided, otherwise keep existing
            'payment_status' => $request->payment_status ?? $order->payment_status
        ]);
        
        return redirect()->route('admin.orders.show', $id)->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
    
    /**
     * Generate Invoice
     */
     public function invoice(string $id)
     {
         $order = Order::with(['user', 'items.product'])->findOrFail($id);
         $template = Setting::get('invoice_template', 'modern');
         $view = view()->exists('admin.orders.invoice-templates.' . $template)
             ? 'admin.orders.invoice-templates.' . $template
             : 'admin.orders.invoice';

         return view($view, compact('order', 'template'));
     }
}
