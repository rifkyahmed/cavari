<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminGiftCardController extends Controller
{
    public function index()
    {
        $giftCards = \App\Models\GiftCard::with('user')->latest()->paginate(20);
        return view('admin.gift-cards.index', compact('giftCards'));
    }

    public function show($id)
    {
        $giftCard = \App\Models\GiftCard::with(['user', 'transactions.order'])->findOrFail($id);
        return view('admin.gift-cards.show', compact('giftCard'));
    }

    public function destroy($id)
    {
        $giftCard = \App\Models\GiftCard::findOrFail($id);
        $giftCard->delete();

        return redirect()->route('admin.gift-cards.index')->with('success', 'Gift card deleted.');
    }
}
