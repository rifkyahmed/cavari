<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
        ]);

        $user = $request->user();

        if ($user->addresses()->count() >= 3) {
            return back()->withErrors(['address_limit' => 'You can only save up to 3 addresses.']);
        }

        $user->addresses()->create($request->all());

        return back()->with('status', 'address-created');
    }

    public function destroy(\App\Models\UserAddress $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();

        return back()->with('status', 'address-deleted');
    }
}
