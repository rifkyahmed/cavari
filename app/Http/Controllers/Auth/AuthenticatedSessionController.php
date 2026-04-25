<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        $intended = session()->get('url.intended');
        return redirect()->route('home')->with([
            'open_auth_modal' => true,
            'intended_url' => $intended
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // MERGE CART LOGIC
        $user = Auth::user();
        $sessionCart = session()->get('cart', []);
        
        // Get or Create Persistent Cart
        $dbCart = \App\Models\Cart::firstOrCreate(['user_id' => $user->id]);

        // Merge Session Items into DB
        foreach ($sessionCart as $productId => $details) {
            $cartItem = $dbCart->items()->where('product_id', $productId)->first();
            
            if ($cartItem) {
                // If item exists, update quantity (sum)
                $cartItem->quantity += $details['quantity'];
                $cartItem->save();
            } else {
                // Create new item
                $dbCart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $details['quantity']
                ]);
            }
        }

        // Re-populate Session from DB (Source of Truth)
        $newSessionCart = [];
        $dbCart->load('items.product'); // Load items and products
        
        foreach ($dbCart->items as $item) {
            if ($item->product) { // Ensure product still exists
                $newSessionCart[$item->product_id] = [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'image' => $item->product->images[0] ?? null
                ];
            }
        }
        
        session()->put('cart', $newSessionCart);
        // END MERGE CART LOGIC

        $intended = $request->input('intended_url') ?? session()->get('intended_url') ?? redirect()->intended(route('home'))->getTargetUrl();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => $user->isAdmin() ? route('admin.dashboard') : $intended
            ]);
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->to($intended);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
