<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)->with('category')->take(3)->get();
        $atelierProducts  = Product::where('is_atelier', true)->with('category')->take(8)->get();
        $categories = Category::all();
        $websiteReviews = \App\Models\WebsiteReview::where('is_approved', true)->latest()->take(6)->get();
        
        return view('home', compact('featuredProducts', 'atelierProducts', 'categories', 'websiteReviews'));
    }
}
