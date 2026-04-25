<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class JournalController extends Controller
{
    public function index()
    {
        $journals = Journal::orderBy('created_at', 'desc')->get();
        return view('admin.journals.index', compact('journals'));
    }

    public function create()
    {
        return view('admin.journals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['is_published'] = $request->has('is_published');
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('journals', 'public');
            $validated['cover_image'] = '/storage/' . $path;
        }

        Journal::create($validated);

        return redirect()->route('admin.journals.index')->with('success', 'Journal created successfully.');
    }

    public function edit(Journal $journal)
    {
        return view('admin.journals.edit', compact('journal'));
    }

    public function update(Request $request, Journal $journal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
        if ($validated['is_published'] && !$journal->is_published) {
            $validated['published_at'] = now();
        } elseif (!$validated['is_published']) {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('cover_image')) {
            // Delete old
            if ($journal->cover_image) {
                $oldPath = str_replace('/storage/', '', $journal->cover_image);
                if(Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('cover_image')->store('journals', 'public');
            $validated['cover_image'] = '/storage/' . $path;
        }

        $journal->update($validated);

        return redirect()->route('admin.journals.index')->with('success', 'Journal updated successfully.');
    }

    public function destroy(Journal $journal)
    {
        if ($journal->is_permanent) {
            return redirect()->route('admin.journals.index')->with('error', 'Cannot delete a permanent core journal article.');
        }

        if ($journal->cover_image) {
            $oldPath = str_replace('/storage/', '', $journal->cover_image);
            if(Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
        $journal->delete();

        return redirect()->route('admin.journals.index')->with('success', 'Journal deleted successfully.');
    }
}
