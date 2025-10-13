@extends('layouts.app')

@section('title', __('messages.dashboard') )

@section('content')
<h1 style="color: white; margin-bottom: 2rem; font-size: 2.5rem;">{{ __('messages.welcome') }}، {{ $user->name }} 👋</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
    <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h3 style="margin-bottom: 1rem; opacity: 0.9;">{{ __('messages.current_balance') }}</h3>
        <div style="font-size: 3rem; font-weight: bold; margin-bottom: 0.5rem;">
            {{ __('messages.currency_symbol') }}{{ number_format($user->balance, 2) }}
        </div>
        <p style="opacity: 0.9;">{{ __('messages.account_number') }}: {{ $user->account_number }}</p>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1rem; color: #667eea;">الإحصائيات</h3>
        <div style="display: grid; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e0e0e0;">
                <span>{{ __('messages.total_transactions') }}:</span>
                <strong style="color: #667eea;">{{ $stats['total_transactions'] }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e0e0e0;">
                <span>إجمالي المصروفات:</span>
                <strong style="color: #dc3545;">{{ __('messages.currency_symbol') }}{{ number_format($stats['total_spent'], 2) }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                <span>إجمالي ال{{ __('messages.received') }}:</span>
                <strong style="color: #28a745;">{{ __('messages.currency_symbol') }}{{ number_format($stats['total_received'], 2) }}</strong>
            </div>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1rem; color: #667eea;">{{ __('messages.daily_limit') }}</h3>
        <div style="margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span> {{ __('messages.daily_spent_today') }}:</span>
                <strong>{{ __('messages.currency_symbol') }}{{ number_format($user->daily_spent, 2) }}</strong>
            </div>
            <div style="background: #e0e0e0; height: 10px; border-radius: 5px; overflow: hidden;">
                <div style="background: #667eea; height: 100%; width: {{ ($user->daily_spent / $user->daily_limit) * 100 }}%;"></div>
            </div>
            <div style="text-align: center; margin-top: 0.5rem; color: #666; font-size: 0.9rem;">
                المتبقي: {{ __('messages.currency_symbol') }}{{ number_format($user->daily_limit - $user->daily_spent, 2) }}
            </div>
        </div>
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">🚀 عمليات سريعة</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="{{ route('transfer.friend') }}" class="btn btn-primary" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">👥</div>
            {{ __('messages.transfer_to_friend') }}
        </a>
        <a href="{{ route('pay.merchant') }}" class="btn btn-primary" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🛒</div>
            {{ __('messages.pay_merchant') }}
        </a>
        <a href="{{ route('cards.index') }}" class="btn btn-primary" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">💳</div>
            {{ __('messages.my_cards') }}
        </a>
        <a href="{{ route('settings.index') }}" class="btn btn-primary" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚙️</div>
            {{ __('messages.settings') }}
        </a>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1.5rem;">📝 آخر {{ __('messages.transactions') }}</h3>
    @forelse($recentTransactions as $transaction)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; margin-bottom: 0.5rem;">
            <div>
                <strong>
                    @if($transaction->transaction_type == 'transfer')
                        @if($transaction->sender_id == $user->id)
                            {{ __('messages.transfer') }} إلى {{ $transaction->receiver->name }}
                        @else
                            {{ __('messages.transfer') }} من {{ $transaction->sender->name }}
                        @endif
                    @elseif($transaction->transaction_type == 'payment')
                        {{ __('messages.payment') }} لـ {{ $transaction->merchant_name }}
                    @endif
                </strong>
                <div style="font-size: 0.9rem; color: #666; margin-top: 0.3rem;">
                    {{ $transaction->description }} • {{ $transaction->created_at->diffForHumans() }}
                </div>
            </div>
            <div style="font-size: 1.3rem; font-weight: bold; color: {{ $transaction->sender_id == $user->id ? '#dc3545' : '#28a745' }};">
                {{ $transaction->sender_id == $user->id ? '-' : '+' }}{{ __('messages.currency_symbol') }}{{ number_format($transaction->amount, 2) }}
            </div>
        </div>
    @empty
        <p style="text-align: center; padding: 2rem; color: #666;">{{ __('messages.no_transactions') }} بعد</p>
    @endforelse

    @if($recentTransactions->count() > 0)
        <div style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('transactions.history') }}" class="btn btn-secondary">عرض جميع {{ __('messages.transactions') }}</a>
        </div>
    @endif
</div>
@endsection
