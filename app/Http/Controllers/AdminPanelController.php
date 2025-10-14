<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminPanelController extends Controller
{
    // Dashboard الرئيسية
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_transactions' => Transaction::count(),
            'total_cards' => Card::count(),
            'total_balance' => User::sum('balance'),
            'today_transactions' => Transaction::whereDate('created_at', today())->count(),
            'today_amount' => Transaction::whereDate('created_at', today())->sum('amount'),
            'pending_cards' => Card::where('status', 'pending')->count(),
            'active_users' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_transactions' => Transaction::with(['sender', 'receiver'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // إدارة المستخدمين
    public function users(Request $request)
    {
        $query = User::with('roles');

        // البحث
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('account_number', 'like', '%' . $request->search . '%');
            });
        }

        // الفلترة حسب الدور
        if ($request->role) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // الترتيب
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    // عرض تفاصيل المستخدم
    public function showUser(User $user)
    {
        $user->load('roles', 'cards');

        $stats = [
            'total_sent' => Transaction::where('sender_id', $user->id)->sum('amount'),
            'total_received' => Transaction::where('receiver_id', $user->id)->sum('amount'),
            'total_transactions' => Transaction::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->count(),
            'cards_count' => $user->cards->count(),
        ];

        $recentTransactions = Transaction::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.users.show', compact('user', 'stats', 'recentTransactions'));
    }

    // تعديل رصيد المستخدم
    public function updateBalance(Request $request, User $user)
    {
        $request->validate([
            'action' => 'required|in:add,subtract,set',
            'amount' => 'required|numeric|min:0',
        ]);

        $oldBalance = $user->balance;

        switch ($request->action) {
            case 'add':
                $user->balance += $request->amount;
                break;
            case 'subtract':
                $user->balance -= $request->amount;
                if ($user->balance < 0) $user->balance = 0;
                break;
            case 'set':
                $user->balance = $request->amount;
                break;
        }

        $user->save();

        return redirect()->back()->with('success',
            __('messages.balance_updated') . ': ' .
            __('messages.currency_symbol') . number_format($oldBalance, 2) .
            ' → ' .
            __('messages.currency_symbol') . number_format($user->balance, 2)
        );
    }

    // حذف مستخدم
    public function deleteUser(User $user)
    {
        // لا يمكن حذف نفسك
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('error', 'لا يمكنك حذف حسابك الخاص!');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'تم حذف المستخدم بنجاح');
    }

    // إدارة المعاملات
    public function transactions(Request $request)
    {
        $query = Transaction::with(['sender', 'receiver']);

        // البحث
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('transaction_id', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('sender', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // الفلترة حسب النوع
        if ($request->type) {
            $query->where('transaction_type', $request->type);
        }

        // الفلترة حسب التاريخ
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // الفلترة حسب المبلغ
        if ($request->amount_min) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->amount_max) {
            $query->where('amount', '<=', $request->amount_max);
        }

        $transactions = $query->latest()->paginate(20);

        $stats = [
            'total_amount' => Transaction::sum('amount'),
            'today_amount' => Transaction::whereDate('created_at', today())->sum('amount'),
            'transfer_count' => Transaction::where('transaction_type', 'transfer')->count(),
            'payment_count' => Transaction::where('transaction_type', 'payment')->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    // إدارة البطاقات
    public function cards(Request $request)
    {
        $query = Card::with('user');

        // الفلترة حسب الحالة
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // الفلترة حسب النوع
        if ($request->type) {
            $query->where('card_type', $request->type);
        }

        // البحث
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('card_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $cards = $query->latest()->paginate(15);

        $stats = [
            'total_cards' => Card::count(),
            'active_cards' => Card::where('status', 'active')->count(),
            'pending_cards' => Card::where('status', 'pending')->count(),
            'blocked_cards' => Card::where('is_blocked', true)->count(),
        ];

        return view('admin.cards.index', compact('cards', 'stats'));
    }

    // الموافقة على بطاقة
    public function approveCard(Card $card)
    {
        $card->update(['status' => 'active']);

        return redirect()->back()->with('success', 'تم الموافقة على البطاقة بنجاح');
    }

    // رفض بطاقة
    public function rejectCard(Card $card)
    {
        $card->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'تم رفض البطاقة');
    }

    // التقارير
    public function reports(Request $request)
    {
        $period = $request->get('period', 'month');

        // تحديد الفترة الزمنية
        switch ($period) {
            case 'today':
                $dateFrom = now()->startOfDay();
                break;
            case 'week':
                $dateFrom = now()->startOfWeek();
                break;
            case 'month':
                $dateFrom = now()->startOfMonth();
                break;
            case 'year':
                $dateFrom = now()->startOfYear();
                break;
            default:
                $dateFrom = now()->startOfMonth();
        }

        // إحصائيات الفترة
        $stats = [
            'users_registered' => User::where('created_at', '>=', $dateFrom)->count(),
            'transactions_count' => Transaction::where('created_at', '>=', $dateFrom)->count(),
            'transactions_amount' => Transaction::where('created_at', '>=', $dateFrom)->sum('amount'),
            'cards_issued' => Card::where('created_at', '>=', $dateFrom)->count(),
            'avg_transaction' => Transaction::where('created_at', '>=', $dateFrom)->avg('amount'),
        ];

        // أكثر المستخدمين نشاطاً
        $topUsers = User::withCount([
            'sentTransactions as transactions_count' => function($q) use ($dateFrom) {
                $q->where('created_at', '>=', $dateFrom);
            }
        ])
        ->orderBy('transactions_count', 'desc')
        ->take(10)
        ->get();

        // المعاملات اليومية (آخر 30 يوم)
        $dailyTransactions = Transaction::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.index', compact('stats', 'topUsers', 'dailyTransactions', 'period'));
    }
}
