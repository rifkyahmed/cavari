<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AbandonedCheckoutReminder;
use App\Models\AbandonedCheckout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AbandonedCheckoutController extends Controller
{
    public function index()
    {
        $checkouts = AbandonedCheckout::latest()->paginate(20);
        return view('admin.abandoned-checkouts.index', compact('checkouts'));
    }

    public function show(AbandonedCheckout $abandonedCheckout)
    {
        return view('admin.abandoned-checkouts.show', compact('abandonedCheckout'));
    }

    public function sendReminder(Request $request, AbandonedCheckout $abandonedCheckout)
    {
        $request->validate([
            'custom_message' => 'required|string|max:1000',
        ]);

        Mail::to($abandonedCheckout->user_email)
            ->send(new AbandonedCheckoutReminder($abandonedCheckout, $request->custom_message));

        $abandonedCheckout->update(['reminder_sent_at' => now()]);

        return back()->with('success', "Reminder email sent to {$abandonedCheckout->user_email} successfully!");
    }

    public function destroy(AbandonedCheckout $abandonedCheckout)
    {
        $abandonedCheckout->delete();
        return redirect()->route('admin.abandoned-checkouts.index')->with('success', 'Record removed.');
    }
}
