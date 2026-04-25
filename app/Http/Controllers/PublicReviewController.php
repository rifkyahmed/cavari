<?php

namespace App\Http\Controllers;

use App\Models\WebsiteReview;
use Illuminate\Http\Request;

class PublicReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = WebsiteReview::where('is_approved', true)
            ->latest()
            ->get();

        return view('reviews.index', compact('reviews'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:250',
        ]);

        WebsiteReview::create([
            'author_name' => $validated['author_name'],
            'location' => $validated['location'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true, // Auto-approve for immediate appearance
        ]);

        return back()->with('success', 'Thank you! Your experience has been published.');
    }
}
