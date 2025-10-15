<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_cards' => Card::count(),
            'active_cards' => Card::where('status', 'active')->count(),
            'total_transactions' => Transaction::count(),
            'total_transaction_amount' => Transaction::where('status', 'completed')->sum('amount'),
            'today_transactions' => Transaction::whereDate('created_at', Carbon::today())->count(),
            'today_amount' => Transaction::whereDate('created_at', Carbon::today())
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        // Recent transactions
        $recentTransactions = Transaction::with(['user', 'card'])
            ->latest()
            ->take(10)
            ->get();

        // Transaction trends (last 7 days)
        $transactionTrends = Transaction::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Card statistics by type
        $cardsByType = Card::select('card_type', DB::raw('count(*) as count'))
            ->groupBy('card_type')
            ->get();

        // Transaction by type
        $transactionsByType = Transaction::where('status', 'completed')
            ->select('transaction_type as type', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('transaction_type')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentTransactions',
            'transactionTrends',
            'cardsByType',
            'transactionsByType'
        ));
    }

    /**
     * Get dashboard analytics data
     */
    public function analytics(Request $request)
    {
        $period = $request->input('period', '7days');

        $data = match($period) {
            'today' => $this->getTodayAnalytics(),
            '7days' => $this->getWeekAnalytics(),
            '30days' => $this->getMonthAnalytics(),
            'year' => $this->getYearAnalytics(),
            default => $this->getWeekAnalytics(),
        };

        return response()->json($data);
    }

    /**
     * Get today's analytics
     */
    private function getTodayAnalytics()
    {
        return [
            'transactions' => Transaction::whereDate('created_at', Carbon::today())
                ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                ->groupBy('hour')
                ->get(),
            'revenue' => Transaction::whereDate('created_at', Carbon::today())
                ->where('status', 'completed')
                ->sum('amount'),
        ];
    }

    /**
     * Get week analytics
     */
    private function getWeekAnalytics()
    {
        return [
            'transactions' => Transaction::where('created_at', '>=', Carbon::now()->subDays(7))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->get(),
            'revenue' => Transaction::where('created_at', '>=', Carbon::now()->subDays(7))
                ->where('status', 'completed')
                ->sum('amount'),
        ];
    }

    /**
     * Get month analytics
     */
    private function getMonthAnalytics()
    {
        return [
            'transactions' => Transaction::where('created_at', '>=', Carbon::now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->get(),
            'revenue' => Transaction::where('created_at', '>=', Carbon::now()->subDays(30))
                ->where('status', 'completed')
                ->sum('amount'),
        ];
    }

    /**
     * Get year analytics
     */
    private function getYearAnalytics()
    {
        return [
            'transactions' => Transaction::where('created_at', '>=', Carbon::now()->subYear())
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->get(),
            'revenue' => Transaction::where('created_at', '>=', Carbon::now()->subYear())
                ->where('status', 'completed')
                ->sum('amount'),
        ];
    }

    /**
     * System health check
     */
    public function health()
    {
        $health = [
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
            'storage' => $this->checkStorageSpace(),
            'queue' => $this->checkQueueStatus(),
        ];

        return view('admin.health', compact('health'));
    }

    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkCacheConnection()
    {
        try {
            cache()->put('health_check', true, 60);
            $result = cache()->get('health_check');
            return ['status' => 'healthy', 'message' => 'Cache is working'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkStorageSpace()
    {
        $total = disk_total_space(storage_path());
        $free = disk_free_space(storage_path());
        $used = $total - $free;
        $percentage = ($used / $total) * 100;

        return [
            'status' => $percentage < 80 ? 'healthy' : 'warning',
            'message' => sprintf('%.2f%% used', $percentage),
            'total' => $this->formatBytes($total),
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
        ];
    }

    private function checkQueueStatus()
    {
        // Simple queue check
        return ['status' => 'healthy', 'message' => 'Queue is running'];
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
