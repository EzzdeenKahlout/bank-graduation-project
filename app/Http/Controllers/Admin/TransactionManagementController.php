<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionManagementController extends Controller
{
    /**
     * Display transactions list
     */
    public function index(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('view_all_transactions')) {
            abort(403, 'Unauthorized action.');
        }

        $query = Transaction::with(['user', 'card']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->has('amount_from')) {
            $query->where('amount', '>=', $request->amount_from);
        }
        if ($request->has('amount_to')) {
            $query->where('amount', '<=', $request->amount_to);
        }

        $transactions = $query->latest()->paginate(20);

        $stats = [
            'total' => Transaction::count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'failed' => Transaction::where('status', 'failed')->count(),
            'total_amount' => Transaction::where('status', 'completed')->sum('amount'),
            'today_amount' => Transaction::whereDate('created_at', Carbon::today())
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Show transaction details
     */
    public function show(Transaction $transaction)
    {
        // Check permission
        if (!auth()->user()->hasPermission('view_all_transactions')) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->load(['user', 'card', 'recipient']);

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Cancel transaction
     */
    public function cancel(Transaction $transaction)
    {
        // Check permission
        if (!auth()->user()->hasPermission('cancel_transactions')) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->status !== 'pending') {
            return back()->with('error', __('messages.only_pending_transactions_can_be_cancelled'));
        }

        $transaction->update([
            'status' => 'cancelled',
            'cancelled_by' => auth()->id(),
            'cancelled_at' => now(),
        ]);

        // Refund amount if already deducted
        if ($transaction->type === 'debit') {
            $transaction->user->increment('balance', $transaction->amount);
        }

        return back()->with('success', __('messages.transaction_cancelled_successfully'));
    }

    /**
     * Refund transaction
     */
    public function refund(Request $request, Transaction $transaction)
    {
        // Check permission
        if (!auth()->user()->hasPermission('refund_transactions')) {
            abort(403, 'Unauthorized action.');
        }

        if ($transaction->status !== 'completed') {
            return back()->with('error', __('messages.only_completed_transactions_can_be_refunded'));
        }

        $validated = $request->validate([
            'refund_reason' => 'required|string|max:500',
        ]);

        // Create refund transaction
        $refund = Transaction::create([
            'user_id' => $transaction->user_id,
            'card_id' => $transaction->card_id,
            'transaction_id' => 'REF-' . strtoupper(uniqid()),
            'type' => $transaction->type === 'debit' ? 'credit' : 'debit',
            'amount' => $transaction->amount,
            'description' => 'Refund: ' . $transaction->description,
            'status' => 'completed',
            'metadata' => json_encode([
                'original_transaction_id' => $transaction->id,
                'refund_reason' => $validated['refund_reason'],
                'refunded_by' => auth()->id(),
            ]),
        ]);

        // Update balances
        if ($transaction->type === 'debit') {
            $transaction->user->increment('balance', $transaction->amount);
        } else {
            $transaction->user->decrement('balance', $transaction->amount);
        }

        // Mark original transaction as refunded
        $transaction->update([
            'status' => 'refunded',
            'refund_transaction_id' => $refund->id,
        ]);

        return back()->with('success', __('messages.transaction_refunded_successfully'));
    }

    /**
     * Export transactions
     */
    public function export(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('export_reports')) {
            abort(403, 'Unauthorized action.');
        }

        $query = Transaction::with(['user', 'card']);

        // Apply same filters as index
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->get();

        $filename = 'transactions_export_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'Transaction ID',
                'User',
                'Email',
                'Card Number',
                'Type',
                'Amount',
                'Status',
                'Description',
                'Date'
            ]);

            // Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_id,
                    $transaction->user->name,
                    $transaction->user->email,
                    $transaction->card ? $transaction->card->card_number : 'N/A',
                    $transaction->type,
                    $transaction->amount,
                    $transaction->status,
                    $transaction->description,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Transaction analytics
     */
    public function analytics(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('view_reports')) {
            abort(403, 'Unauthorized action.');
        }

        $period = $request->input('period', '30days');

        $startDate = match($period) {
            'today' => Carbon::today(),
            '7days' => Carbon::now()->subDays(7),
            '30days' => Carbon::now()->subDays(30),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subDays(30),
        };

        $stats = [
            'total_transactions' => Transaction::where('created_at', '>=', $startDate)->count(),
            'total_amount' => Transaction::where('created_at', '>=', $startDate)
                ->where('status', 'completed')
                ->sum('amount'),
            'by_type' => Transaction::where('created_at', '>=', $startDate)
                ->where('status', 'completed')
                ->selectRaw('type, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('type')
                ->get(),
            'by_status' => Transaction::where('created_at', '>=', $startDate)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'daily_trend' => Transaction::where('created_at', '>=', $startDate)
                ->where('status', 'completed')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'top_users' => Transaction::where('created_at', '>=', $startDate)
                ->where('status', 'completed')
                ->selectRaw('user_id, COUNT(*) as transaction_count, SUM(amount) as total_amount')
                ->groupBy('user_id')
                ->with('user')
                ->orderByDesc('total_amount')
                ->take(10)
                ->get(),
        ];

        return view('admin.transactions.analytics', compact('stats', 'period'));
    }
}
