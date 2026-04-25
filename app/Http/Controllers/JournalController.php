<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;

class JournalController extends Controller
{
    public function index()
    {
        $journals = Journal::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('journal.index', compact('journals'));
    }

    public function show($slug)
    {
        $journal = Journal::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Get recent posts for the bottom section
        $recentJournals = Journal::where('is_published', true)
            ->where('id', '!=', $journal->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('journal.show', compact('journal', 'recentJournals'));
    }
}
