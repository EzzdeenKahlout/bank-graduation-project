<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardRequest;
use App\Models\User;
use Illuminate\Http\Request;

class CardManagementController extends Controller
{
    /**
     * Display cards list
     */
    public function index(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('view_cards')) {
            abort(403, 'Unauthorized action.');
        }

        $query = Card::with('user');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('card_number', 'like', "%{$search}%")
                  ->orWhere('card_holder_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('card_type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $cards = $query->latest()->paginate(20);

        $stats = [
            'total_cards'   => \App\Models\Card::count(),
            'active_cards'  => \App\Models\Card::where('status','active')->count(),
            'blocked_cards' => \App\Models\Card::where('is_blocked', true)->count(),
        ];

         $pending = CardRequest::with('user')->where('status','pending')->latest()->get();
        return view('admin.cards.index', compact('cards', 'stats', 'pending'));
    }

    /**
     * Show card details
     */
    public function show(Card $card)
    {
        // Check permission
        if (!auth()->user()->hasPermission('view_cards')) {
            abort(403, 'Unauthorized action.');
        }

        $card->load(['user']);

        $stats = [
            'total_transactions' => $card->transactions()->count(),
            'total_spent' => $card->transactions()->where('transaction_type', 'debit')->sum('amount'),
            'last_transaction' => $card->transactions()->latest()->first(),
        ];

        return view('admin.cards.show', compact('card', 'stats'));
    }

    /**
     * Show create card form
     */
    public function create()
    {
        // Check permission
        if (!auth()->user()->hasPermission('create_cards')) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::where('is_active', true)->get();
        return view('admin.cards.create', compact('users'));
    }

    /**
     * Store new card
     */
    public function store(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('create_cards')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'card_type' => 'required|in:debit,credit',
            'card_holder_name' => 'required|string|max:255',
            'expiry_date' => 'required|date|after:today',
        ]);

        // Generate card number
        $cardNumber = $this->generateCardNumber();

        $card = Card::create([
            'user_id' => $validated['user_id'],
            'card_type' => $validated['card_type'],
            'card_number' => $cardNumber,
            'card_holder_name' => $validated['card_holder_name'],
            'expiry_date' => $validated['expiry_date'],
            'status' => 'active',
        ]);

        return redirect()
            ->route('admin.cards.show', $card)
            ->with('success', __('messages.card_created_successfully'));
    }

    /**
     * Block card
     */
    public function block(Card $card)
    {
        // Check permission
        if (!auth()->user()->hasPermission('block_cards')) {
            abort(403, 'Unauthorized action.');
        }

        $card->update(['status' => 'blocked']);

        return back()->with('success', __('messages.card_blocked_successfully'));
    }

    /**
     * Unblock card
     */
    public function unblock(Card $card)
    {
        // Check permission
        if (!auth()->user()->hasPermission('block_cards')) {
            abort(403, 'Unauthorized action.');
        }

        $card->update(['status' => 'active']);

        return back()->with('success', __('messages.card_unblocked_successfully'));
    }

    /**
     * Delete card
     */
    public function destroy(Card $card)
    {
        // Check permission
        if (!auth()->user()->hasPermission('delete_cards')) {
            abort(403, 'Unauthorized action.');
        }

        $card->delete();

        return redirect()
            ->route('admin.cards.index')
            ->with('success', __('messages.card_deleted_successfully'));
    }

    /**
     * Card requests list
     */
    public function requests()
    {
        // Check permission
        if (!auth()->user()->hasPermission('approve_cards')) {
            abort(403, 'Unauthorized action.');
        }

        $requests = \App\Models\CardRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.cards.requests', compact('requests'));
    }

    /**
     * Approve card request
     */
    public function approveRequest($requestId)
    {
        // Check permission
        if (!auth()->user()->hasPermission('approve_cards')) {
            abort(403, 'Unauthorized action.');
        }

        $cardRequest = \App\Models\CardRequest::findOrFail($requestId);

        // Generate card number
        $cardNumber = $this->generateCardNumber();

        // Create card
        $card = Card::create([
            'user_id' => $cardRequest->user_id,
            'card_type' => $cardRequest->card_type,
            'card_number' => $cardNumber,
            'card_holder_name' => $cardRequest->card_holder_name,
            'expiry_date' => now()->addYears(3),
            'status' => 'active',
        ]);

        // Update request status
        $cardRequest->update([
            'status' => 'approved',
            'card_id' => $card->id,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', __('messages.card_request_approved'));
    }

    /**
     * Reject card request
     */
    public function rejectRequest(Request $request, $requestId)
    {
        // Check permission
        if (!auth()->user()->hasPermission('approve_cards')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $cardRequest = \App\Models\CardRequest::findOrFail($requestId);

        $cardRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', __('messages.card_request_rejected'));
    }

    /**
     * Export cards
     */
    public function export()
    {
        // Check permission
        if (!auth()->user()->hasPermission('export_reports')) {
            abort(403, 'Unauthorized action.');
        }

        $cards = Card::with('user')->get();

        $filename = 'cards_export_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($cards) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Card Number', 'Holder Name', 'Type', 'Status', 'User', 'Email', 'Expiry Date', 'Created At']);

            // Data
            foreach ($cards as $card) {
                fputcsv($file, [
                    $card->card_number,
                    $card->card_holder_name,
                    $card->card_type,
                    $card->status,
                    $card->user->name,
                    $card->user->email,
                    $card->expiry_date,
                    $card->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate unique card number
     */
    private function generateCardNumber()
    {
        do {
            // Generate 16 digit card number
            $cardNumber = '';
            for ($i = 0; $i < 4; $i++) {
                $cardNumber .= str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            }
        } while (Card::where('card_number', $cardNumber)->exists());

        return $cardNumber;
    }
}
