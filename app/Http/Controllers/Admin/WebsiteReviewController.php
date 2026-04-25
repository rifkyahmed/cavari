<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteReview;
use Illuminate\Http\Request;

class WebsiteReviewController extends Controller
{
    public function index()
    {
        $reviews = WebsiteReview::latest()->paginate(15);
        return view('admin.website-reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('admin.website-reviews.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_name' => 'required',
            'location' => 'nullable',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:250',
        ]);

        WebsiteReview::create([
            'author_name' => $request->author_name,
            'location' => $request->location,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
            'is_fake' => true, // Creating manual reviews => assumes fake unless marked otherwise, user asked for 'add fake reviews'
        ]);

        return redirect()->route('admin.website-reviews.index')->with('success', 'Review added successfully.');
    }

    public function edit(WebsiteReview $websiteReview)
    {
        return view('admin.website-reviews.edit', compact('websiteReview'));
    }

    public function update(Request $request, WebsiteReview $websiteReview)
    {
        $request->validate([
            'author_name' => 'required',
            'location' => 'nullable',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:250',
        ]);

        $websiteReview->update([
            'author_name' => $request->author_name,
            'location' => $request->location,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => $request->has('is_approved'),
        ]);

        return redirect()->route('admin.website-reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(WebsiteReview $websiteReview)
    {
        $websiteReview->delete();
        return redirect()->route('admin.website-reviews.index')->with('success', 'Review deleted successfully.');
    }
}
