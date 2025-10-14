@extends('layouts.app')

@section('title', __('messages.dashboard') )

@section('content')
<style>
    .welcome-header {
        color: white;
        margin-bottom: 2rem;
        font-size: 2.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .icon-xl {
        width: 48px;
        height: 48px;
    }
</style>

<h1 class="welcome-header">
    <svg class="icon-xl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
    </svg>
    {{ __('messages.welcome') }}، {{ $user->name }}
</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
    <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h3 style="margin-bottom: 1rem; opacity: 0.9; display: flex; align-items: center; gap: 0.5rem;">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
            </svg>
            {{ __('messages.current_balance') }}
        </h3>
        <div style="font-size: 3rem; font-weight: bold; margin-bottom: 0.5rem;">
            {{ __('messages.currency_symbol') }}{{ number_format($user->balance, 2) }}
        </div>
        <p style="opacity: 0.9;">{{ __('messages.account_number') }}: {{ $user->account_number }}</p>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 1rem; color: #667eea; display: flex; align-items: center; gap: 0.5rem;">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            الإحصائيات
        </h3>
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
        <h3 style="margin-bottom: 1rem; color: #667eea; display: flex; align-items: center; gap: 0.5rem;">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ __('messages.daily_limit') }}
        </h3>
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
    <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        عمليات سريعة
    </h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <a href="{{ route('transfer.friend') }}" class="btn btn-primary" style="padding: 1.5rem; text-align: center;">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <div>{{ __('messages.transfer_to_friend') }}</div>
        </a>
        <a href="{{ route('pay.merchant') }}" class="btn btn-primary" style="padding: 1.5rem; text-align: center;">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <div>{{ __('messages.pay_merchant') }}</div>
        </a>
        <a href="{{ route('cards.index') }}" class="btn btn-primary" style="padding: 1.5rem; text-align: center;">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <div>{{ __('messages.my_cards') }}</div>
        </a>
        <a href="{{ route('settings.index') }}" class="btn btn-primary" style="padding: 1.5rem; text-align: center;">
            <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <div>{{ __('messages.settings') }}</div>
        </a>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg class="icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        آخر {{ __('messages.transactions') }}
    </h3>
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
            <a href="{{ route('transactions.history') }}" class="btn btn-primary">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                عرض جميع {{ __('messages.transactions') }}
            </a>
        </div>
    @endif>
</div>
@endsection
