<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'total_transactions' => Transaction::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->count(),
            'total_spent' => Transaction::where('sender_id', $user->id)
                ->sum('amount'),
            'total_received' => Transaction::where('receiver_id', $user->id)
                ->sum('amount'),
        ];

        $recentTransactions = Transaction::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact('user', 'recentTransactions', 'stats'));
    }
}