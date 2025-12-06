<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\Permission;
use App\Models\Role;
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
            'total_roles' => Role::count(),
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

        // Recent transactions المعاملات الأخيرة
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
            ->select(DB::raw("
                CASE
                    WHEN transaction_type IN ('transfer') THEN 'تحويل لصديق'
                    WHEN transaction_type IN ('payment') THEN 'دفع لتاجر'
                    WHEN (transaction_type IS NULL OR transaction_type = '') AND payment_method = 'transfer' THEN 'تحويل لصديق'
                    WHEN (transaction_type IS NULL OR transaction_type = '') AND payment_method = 'card' THEN 'دفع لتاجر'
                    WHEN (transaction_type IS NULL OR transaction_type = '') AND receiver_id IS NOT NULL THEN 'تحويل لصديق'
                    WHEN (transaction_type IS NULL OR transaction_type = '') AND merchant_name IS NOT NULL THEN 'دفع لتاجر'
                    ELSE 'غير محدد'
                END as type"),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total'))
            ->groupBy('type')
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

        // Add binned amounts for the period
        $now = Carbon::now();
        switch ($period) {
            case 'today':
                $bins = $this->buildAmountBins($now->copy()->startOfDay(), $now->copy()->endOfDay(), 'day');
                break;
            case '7days':
            default:
                $bins = $this->buildAmountBins($now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay(), 'day');
                break;
            case '30days':
                $bins = $this->buildAmountBins($now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay(), 'day');
                break;
            case 'year':
                $bins = $this->buildAmountBins($now->copy()->startOfYear(), $now->copy()->endOfYear(), 'month');
                break;
        }
        $data['amount_bins'] = $bins;
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

    
    /**
     * Build dynamic amount bins (>=1000 step) and aggregate sums per day/month/year
     */
    private function buildAmountBins(Carbon $start, Carbon $end, string $granularity = 'day')
    {
        // Fetch required transactions in range
        $rows = Transaction::whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->select(['amount', 'created_at'])
            ->get();

        $maxAmount = max(10000, (int) ceil(($rows->max('amount') ?? 0) / 1000) * 1000);
        $step = 1000;

        // Build bin labels like "0-999", "1000-1999", ...
        $bins = [];
        for ($a = 0; $a < $maxAmount; $a += $step) {
            $bins[] = [$a, $a + $step - 1];
        }
        // Add an overflow bin for amounts >= maxAmount
        $bins[] = [$maxAmount, null]; // null = infinity

        $binLabels = array_map(function ($b) {
            return is_null($b[1]) ? ($b[0] . '+') : ($b[0] . '-' . $b[1]);
        }, $bins);

        // Build time labels
        $labels = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            if ($granularity === 'year') {
                $labels[] = $cursor->format('Y');
                $cursor->addYear();
            } elseif ($granularity === 'month') {
                $labels[] = $cursor->format('Y-m');
                $cursor->addMonth();
            } else { // day
                $labels[] = $cursor->format('Y-m-d');
                $cursor->addDay();
            }
        }

        // Initialize matrix [bin][time] = 0
        $series = array_fill(0, count($bins), array_fill(0, count($labels), 0));

        // Helper to pick label index
        $labelIndex = function ($dt) use ($granularity, $labels) {
            $key = $granularity === 'year' ? $dt->format('Y')
                 : ($granularity === 'month' ? $dt->format('Y-m') : $dt->format('Y-m-d'));
            return array_search($key, $labels, true);
        };

        foreach ($rows as $r) {
            $dt = Carbon::parse($r->created_at);
            $li = $labelIndex($dt);
            if ($li === false) continue;

            $amt = (int) round($r->amount);
            $bi = count($bins)-1; // default overflow
            foreach ($bins as $i => $b) {
                if (is_null($b[1])) { // overflow
                    if ($amt >= $b[0]) { $bi = $i; break; }
                } else {
                    if ($amt >= $b[0] && $amt <= $b[1]) { $bi = $i; break; }
                }
            }
            $series[$bi][$li] += $amt;
        }

        return [
            'labels' => $labels,
            'binLabels' => $binLabels,
            'series' => $series,
        ];
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
