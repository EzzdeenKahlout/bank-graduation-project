<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardRequest;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        $cards = auth()->user()->cards;
        return view('cards.index', compact('cards'));
    }

    public function requestNewCard()
    {
        return view('cards.request');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'card_type' => 'required|in:debit,credit',
            'reason' => 'nullable|string'
        ]);

        CardRequest::create([
            'user_id' => auth()->id(),
            'card_type' => $request->card_type,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('cards.index')
            ->with('success', __('messages.card_request_sent'));
    }

    public function toggleBlock(Card $card)
    {
        if ($card->user_id !== auth()->id()) {
            abort(403);
        }

        // Get the new blocked status
        $newBlockedStatus = !$card->is_blocked;

        // Update card with correct logic
        $card->update([
            'is_blocked' => $newBlockedStatus,
            'status' => $newBlockedStatus ? 'blocked' : 'active'
        ]);

        return back()->with('success', __('messages.card_status_updated'));
    }

    public function toggleActive(Card $card)
    {
        if ($card->user_id !== auth()->id()) {
            abort(403);
        }

        $card->update(['is_active' => !$card->is_active]);

        return back()->with('success', __('messages.card_updated'));
    }
}
