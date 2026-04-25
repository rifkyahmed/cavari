<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\CustomizationRequest;
use App\Models\SourceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicFormController extends Controller
{
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'topic' => 'required|string',
            'message' => 'required|string',
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => ucfirst($validated['topic']),
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Your message has been sent successfully.');
    }

    public function submitCustomization(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($validated['type'] === 'sourcing') {
            SourceRequest::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'product_details' => $validated['message'],
                'status' => 'pending',
            ]);
        } else {
            CustomizationRequest::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'details' => "Type: " . ucfirst($validated['type']) . "\n" . $validated['message'],
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Your request has been submitted successfully.');
    }
}
