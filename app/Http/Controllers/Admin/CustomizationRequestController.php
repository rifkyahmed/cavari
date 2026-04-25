<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomizationRequest;
use Illuminate\Http\Request;

class CustomizationRequestController extends Controller
{
    public function index()
    {
        $requests = CustomizationRequest::latest()->paginate(15);
        return view('admin.customization-requests.index', compact('requests'));
    }

    public function show(CustomizationRequest $customizationRequest)
    {
        return view('admin.customization-requests.show', compact('customizationRequest'));
    }

    public function update(Request $request, CustomizationRequest $customizationRequest)
    {
        $customizationRequest->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }
}
