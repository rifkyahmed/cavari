<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourceRequest;
use Illuminate\Http\Request;

class SourceRequestController extends Controller
{
    public function index()
    {
        $requests = SourceRequest::latest()->paginate(15);
        return view('admin.source-requests.index', compact('requests'));
    }

    public function show(SourceRequest $sourceRequest)
    {
        return view('admin.source-requests.show', compact('sourceRequest'));
    }

    public function update(Request $request, SourceRequest $sourceRequest)
    {
         $sourceRequest->update(['status' => $request->status]);
         return back()->with('success', 'Status updated.');
    }
}
