<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->with('product')->paginate(10);
        return view('wishlist.index', compact('wishlists'));
    }

    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $user = Auth::user();
        $wishlist = Wishlist::where('user_id', $user->id)
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $action = 'removed';
            $message = 'Product removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id
            ]);
            $action = 'added';
            $message = 'Product added to wishlist.';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => $message,
                'action' => $action
            ]);
        }

        return back()->with('success', $message);
    }

    public function destroy(Request $request, $id)
    {
        Auth::user()->wishlists()->where('id', $id)->delete();
        $message = 'Product removed from wishlist.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }
}
