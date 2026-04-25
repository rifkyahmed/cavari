<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\Order;

class UserOrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->paginate(5);
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Auth::user()->orders()->with('items.product')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function invoice($id)
    {
        $order = Auth::user()->orders()->with(['user', 'items.product'])->findOrFail($id);
        
        if ($order->payment_link_uuid && $order->payment_status !== 'paid') {
            return redirect()->route('custom_orders.pay', $order->payment_link_uuid)
                ->with('error', 'Please complete your payment before downloading the invoice.');
        }

        $template = Setting::get('invoice_template', 'modern');
        $view = view()->exists('admin.orders.invoice-templates.' . $template)
            ? 'admin.orders.invoice-templates.' . $template
            : 'admin.orders.invoice';

        return view($view, compact('order', 'template'));
    }

    public function publicShow(string $uuid)
    {
        $order = Order::with(['user', 'items.product'])
            ->where('payment_link_uuid', $uuid)
            ->firstOrFail();

        return view('orders.show', [
            'order' => $order,
            'publicAccess' => true,
            'publicInvoiceUrl' => route('orders.public.invoice', $uuid),
            'publicOrderUrl' => route('orders.public.show', $uuid),
        ]);
    }

    public function publicInvoice(string $uuid)
    {
        $order = Order::with(['user', 'items.product'])
            ->where('payment_link_uuid', $uuid)
            ->firstOrFail();

        $template = Setting::get('invoice_template', 'modern');
        $view = view()->exists('admin.orders.invoice-templates.' . $template)
            ? 'admin.orders.invoice-templates.' . $template
            : 'admin.orders.invoice';

        return view($view, compact('order', 'template'));
    }
}
