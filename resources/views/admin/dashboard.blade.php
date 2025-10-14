@extends('layouts.admin')

@section('page-title', __('messages.admin_panel'))

@section('content')

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <h4>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            {{ __('messages.users') }}
        </h4>
        <div class="value">{{ $stats['total_users'] }}</div>
        <small style="color: #28a745;">+{{ $stats['active_users'] }} {{ __('messages.this_week') }}</small>
    </div>

    <div class="stat-card">
        <h4>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            {{ __('messages.transactions') }}
        </h4>
        <div class="value">{{ $stats['total_transactions'] }}</div>
        <small style="color: #28a745;">{{ $stats['today_transactions'] }} {{ __('messages.today') }}</small>
    </div>

    <div class="stat-card">
        <h4>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            {{ __('messages.my_cards') }}
        </h4>
        <div class="value">{{ $stats['total_cards'] }}</div>
        <small style="color: #ffc107;">{{ $stats['pending_cards'] }} معلقة</small>
    </div>

    <div class="stat-card">
        <h4>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            إجمالي الأرصدة
        </h4>
        <div class="value">{{ __('messages.currency_symbol') }}{{ number_format($stats['total_balance'], 0) }}</div>
        <small style="color: #17a2b8;">{{ __('messages.currency_symbol') }}{{ number_format($stats['today_amount'], 0) }} {{ __('messages.today') }}</small>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            عمليات سريعة
        </h3>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="{{ route('admin.users') }}" class="btn btn-primary">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            {{ __('messages.manage_users') }}
        </a>

        <a href="{{ route('admin.transactions') }}" class="btn btn-primary">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            {{ __('messages.transactions') }}
        </a>

        <a href="{{ route('admin.cards') }}" class="btn btn-primary">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            إدارة البطاقات
        </a>

        <a href="{{ route('admin.reports') }}" class="btn btn-primary">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            {{ __('messages.reports') }}
        </a>
    </div>
</div>

<!-- Recent Users -->
<div class="card">
    <div class="card-header">
        <h3>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            أحدث المستخدمين
        </h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الرصيد</th>
                    <th>تاريخ التسجيل</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['recent_users'] as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ __('messages.currency_symbol') }}{{ number_format($user->balance, 2) }}</td>
                    <td>{{ $user->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-primary btn-sm">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            عرض
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header">
        <h3>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            أحدث المعاملات
        </h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>من</th>
                    <th>إلى</th>
                    <th>المبلغ</th>
                    <th>الوصف</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['recent_transactions'] as $transaction)
                <tr>
                    <td>{{ $transaction->sender->name }}</td>
                    <td>{{ $transaction->receiver ? $transaction->receiver->name : $transaction->merchant_name }}</td>
                    <td style="color: #28a745; font-weight: bold;">{{ __('messages.currency_symbol') }}{{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection


@section('title', __('messages.admin_panel'))

@section('content')
<style>
    .admin-header {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .admin-header h1 {
        color: #667eea;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-card h3 {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .stat-card .value {
        font-size: 2rem;
        font-weight: bold;
        color: #667eea;
    }
    .stat-card.primary { border-left: 4px solid #667eea; }
    .stat-card.success { border-left: 4px solid #28a745; }
    .stat-card.warning { border-left: 4px solid #ffc107; }
    .stat-card.danger { border-left: 4px solid #dc3545; }

    .admin-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .admin-section h2 {
        color: #667eea;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .admin-menu {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    .admin-menu-item {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    .admin-menu-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    .recent-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    .icon-lg {
        width: 32px;
        height: 32px;
    }
</style>

<div class="admin-header">
    <h1>
        <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        {{ __('messages.admin_panel') }}
    </h1>
    <p style="color: #666;">مرحباً {{ auth()->user()->name }}، هنا يمكنك إدارة النظام بالكامل</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card primary">
        <h3>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            {{ __('messages.users') }}
        </h3>
        <div class="value">{{ $stats['total_users'] }}</div>
    </div>

    <div class="stat-card success">
        <h3>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            {{ __('messages.transactions') }}
        </h3>
        <div class="value">{{ $stats['total_transactions'] }}</div>
    </div>

    <div class="stat-card warning">
        <h3>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            {{ __('messages.my_cards') }}
        </h3>
        <div class="value">{{ $stats['total_cards'] }}</div>
    </div>

    <div class="stat-card danger">
        <h3>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            إجمالي الأرصدة
        </h3>
        <div class="value">{{ __('messages.currency_symbol') }}{{ number_format($stats['total_balance'], 0) }}</div>
    </div>
</div>

<!-- Today's Stats -->
<div class="admin-section">
    <h2>
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        إحصائيات اليوم
    </h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div style="background: #f0f4ff; padding: 1.5rem; border-radius: 10px;">
            <h4 style="color: #666; margin-bottom: 0.5rem;">معاملات اليوم</h4>
            <p style="font-size: 1.8rem; font-weight: bold; color: #667eea;">{{ $stats['today_transactions'] }}</p>
        </div>
        <div style="background: #f0f4ff; padding: 1.5rem; border-radius: 10px;">
            <h4 style="color: #666; margin-bottom: 0.5rem;">قيمة معاملات اليوم</h4>
            <p style="font-size: 1.8rem; font-weight: bold; color: #667eea;">{{ __('messages.currency_symbol') }}{{ number_format($stats['today_amount'], 0) }}</p>
        </div>
    </div>
</div>

<!-- Admin Quick Actions -->
<div class="admin-section">
    <h2>
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        عمليات سريعة
    </h2>
    <div class="admin-menu">
        <a href="{{ route('admin.roles.index') }}" class="admin-menu-item">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            {{ __('messages.manage_roles') }}
        </a>

        <a href="{{ route('admin.users.index') }}" class="admin-menu-item">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            {{ __('messages.manage_users') }}
        </a>

        <a href="{{ route('transactions.history') }}" class="admin-menu-item">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            {{ __('messages.transactions') }}
        </a>

        <a href="{{ route('cards.index') }}" class="admin-menu-item">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            إدارة البطاقات
        </a>
    </div>
</div>

<!-- Recent Users -->
<div class="admin-section">
    <h2>
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        أحدث المستخدمين
    </h2>
    @foreach($stats['recent_users'] as $user)
        <div class="recent-item">
            <div>
                <strong>{{ $user->name }}</strong>
                <div style="font-size: 0.9rem; color: #666;">{{ $user->email }}</div>
            </div>
            <div style="text-align: left;">
                <div style="font-weight: bold; color: #667eea;">{{ __('messages.currency_symbol') }}{{ number_format($user->balance, 2) }}</div>
                <div style="font-size: 0.9rem; color: #666;">{{ $user->created_at->diffForHumans() }}</div>
            </div>
        </div>
    @endforeach
</div>

<!-- Recent Transactions -->
<div class="admin-section">
    <h2>
        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        أحدث المعاملات
    </h2>
    @foreach($stats['recent_transactions'] as $transaction)
        <div class="recent-item">
            <div>
                <strong>
                    {{ $transaction->sender->name }} → {{ $transaction->receiver ? $transaction->receiver->name : $transaction->merchant_name }}
                </strong>
                <div style="font-size: 0.9rem; color: #666;">
                    {{ $transaction->description }} • {{ $transaction->created_at->diffForHumans() }}
                </div>
            </div>
            <div style="font-size: 1.2rem; font-weight: bold; color: #28a745;">
                {{ __('messages.currency_symbol') }}{{ number_format($transaction->amount, 2) }}
            </div>
        </div>
    @endforeach
</div>

@endsection
