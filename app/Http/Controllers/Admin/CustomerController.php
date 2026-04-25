<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', '!=', 'admin')->withCount('orders')->latest()->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->load([
            'orders.items.product',
            'addresses',
            'reviews',
            'messages',
            'customizationRequests',
            'sourceRequests',
        ]);

        $totalSpent  = $customer->orders->sum('total_price');
        $totalOrders = $customer->orders->count();

        return view('admin.customers.show', compact('customer', 'totalSpent', 'totalOrders'));
    }

    public function sendBirthdayOffer(Request $request, User $customer)
    {
        $request->validate([
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after_or_equal:today',
        ]);

        // Generate a unique code
        $code = strtoupper('BDAY-' . \Illuminate\Support\Str::random(6));

        $coupon = \App\Models\Coupon::create([
            'code' => $code,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'expiry_date' => $request->expiry_date,
            'usage_limit' => 1,
            'user_email' => $customer->email,
            'is_birthday_offer' => true,
            'is_popup_seen' => false,
        ]);

        \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\BirthdayOfferMail($customer, $coupon));

        return back()->with('success', 'Birthday offer sent successfully via email!');
    }
}
