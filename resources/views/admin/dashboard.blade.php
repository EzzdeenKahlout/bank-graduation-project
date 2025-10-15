@extends('layouts.app')

@section('title', __('messages.admin_dashboard'))

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-md me-3">
                                <i class="material-icons opacity-10">dashboard</i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ __('messages.admin_dashboard') }}</h5>
                                <p class="text-sm text-muted mb-0">{{ __('messages.welcome_admin', ['name' => auth()->user()->name]) }}</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                                <i class="material-icons text-sm">refresh</i>
                                {{ __('messages.refresh') }}
                            </button>
@permission('view_admin_health')
                            <a href="{{ route('admin.health') }}" class="btn btn-outline-info btn-sm">
                                <i class="material-icons text-sm">monitor_heart</i>
                                {{ __('messages.system_health') }}
                            </a>
@endpermission
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Users -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('messages.total_users') }}</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($stats['total_users']) }}
                                    <span class="text-success text-sm font-weight-bolder">
                                        +{{ $stats['active_users'] }} {{ __('messages.active') }}
                                    </span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="material-icons opacity-10">people</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-2">
                    <div class="d-flex">
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-primary">{{ __('messages.view_all') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Cards -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('messages.total_cards') }}</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($stats['total_cards']) }}
                                    <span class="text-success text-sm font-weight-bolder">
                                        +{{ $stats['active_cards'] }} {{ __('messages.active') }}
                                    </span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="material-icons opacity-10">credit_card</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-2">
                    <div class="d-flex">
                        <a href="{{ route('admin.cards.index') }}" class="text-sm text-success">{{ __('messages.view_all') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('messages.transactions_today') }}</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($stats['today_transactions']) }}
                                    <span class="text-warning text-sm font-weight-bolder">
                                        / {{ number_format($stats['total_transactions']) }}
                                    </span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="material-icons opacity-10">receipt_long</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-2">
                    <div class="d-flex">
                        <a href="{{ route('admin.transactions.index') }}" class="text-sm text-warning">{{ __('messages.view_all') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('messages.amount_today') }}</p>
                                <h5 class="font-weight-bolder mb-0">
                                    ${{ number_format($stats['today_amount'], 2) }}
                                    <span class="text-info text-sm font-weight-bolder">
                                        / ${{ number_format($stats['total_transaction_amount'], 2) }}
                                    </span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                <i class="material-icons opacity-10">attach_money</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-2">
                    <div class="d-flex">
@permission('view_admin_analytics')
                        <a href="{{ route('admin.transactions.index') }}" class="text-sm text-info">{{ __('messages.view_analytics') }}</a>
@endpermission
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Transaction Trends -->
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('messages.transaction_trends') }}</h6>
                        <select class="form-select form-select-sm w-auto" onchange="updateCharts(this.value)">
                            <option value="7days">{{ __('messages.last_7_days') }}</option>
                            <option value="30days">{{ __('messages.last_30_days') }}</option>
                            <option value="year">{{ __('messages.this_year') }}</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-3">
                    <canvas id="transactionTrendsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Cards & Transactions Distribution -->
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">{{ __('messages.distribution') }}</h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-4">
                        <h6 class="text-sm">{{ __('messages.cards_by_type') }}</h6>
                        <canvas id="cardsByTypeChart" height="150"></canvas>
                    </div>
                    <div>
                        <h6 class="text-sm">{{ __('messages.transactions_by_type') }}</h6>
                        <canvas id="transactionsByTypeChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0">{{ __('messages.recent_transactions') }}</h6>
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-primary">
                            {{ __('messages.view_all') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('messages.transaction') }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('messages.user') }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('messages.type') }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('messages.amount') }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('messages.status') }}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('messages.date') }}
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $transaction->transaction_id }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ Str::limit($transaction->description, 30) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $transaction->user->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $transaction->user->email }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $transaction->type == 'credit' ? 'success' : 'warning' }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">${{ number_format($transaction->amount, 2) }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $transaction->status == 'completed' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $transaction->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.transactions.show', $transaction) }}"
                                           class="text-secondary font-weight-bold text-xs"
                                           data-toggle="tooltip"
                                           title="{{ __('messages.view_details') }}">
                                            <i class="material-icons text-sm">visibility</i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="material-icons text-muted" style="font-size: 48px;">inbox</i>
                                        <p class="text-muted">{{ __('messages.no_transactions_found') }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Transaction Trends Chart
const trendsData = @json($transactionTrends);
const trendsCtx = document.getElementById('transactionTrendsChart').getContext('2d');
new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: trendsData.map(item => item.date),
        datasets: [{
            label: '{{ __('messages.transactions') }}',
            data: trendsData.map(item => item.count),
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }, {
            label: '{{ __('messages.amount') }} ($)',
            data: trendsData.map(item => item.total),
            borderColor: 'rgb(255, 99, 132)',
            tension: 0.1,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                type: 'linear',
                position: 'left',
            },
            y1: {
                type: 'linear',
                position: 'right',
                grid: {
                    drawOnChartArea: false,
                },
            },
        }
    }
});

// Cards by Type Chart
const cardsData = @json($cardsByType);
const cardsCtx = document.getElementById('cardsByTypeChart').getContext('2d');
new Chart(cardsCtx, {
    type: 'doughnut',
    data: {
        labels: cardsData.map(item => item.card_type),
        datasets: [{
            data: cardsData.map(item => item.count),
            backgroundColor: ['#36a2eb', '#ff6384']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Transactions by Type Chart
const transData = @json($transactionsByType);
const transCtx = document.getElementById('transactionsByTypeChart').getContext('2d');
new Chart(transCtx, {
    type: 'pie',
    data: {
        labels: transData.map(item => item.type),
        datasets: [{
            data: transData.map(item => item.count),
            backgroundColor: ['#4bc0c0', '#ffcd56', '#ff6384']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

function refreshDashboard() {
    location.reload();
}

function updateCharts(period) {
    // Implement AJAX call to update charts
    console.log('Updating charts for period:', period);
}
</script>
@endpush
@endsection