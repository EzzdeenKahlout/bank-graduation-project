@extends('layouts.app')

@section('title',  __('messages.transaction_history') )

@section('content')
<div style="max-width: 1000px; margin: 2rem auto;">
    <h1 style="color: white; margin-bottom: 2rem;">📊 سجل جميع {{ __('messages.transactions') }}</h1>

    <div class="card" style="margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;">🔍 {{ __('messages.filter') }} النتائج</h3>
        <form method="GET" action="{{ route('transactions.history') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label>{{ __('messages.from') }} تاريخ</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.to') }} تاريخ</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">{{ __('messages.search') }}</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        @forelse($transactions as $transaction)
            <div style="display: flex; justify-content: space-between; align-items: start; padding: 1.5rem; background: #f8f9fa; border-radius: 10px; margin-bottom: 1rem;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                        <span style="font-size: 2rem;">
                            @if($transaction->transaction_type == 'transfer')
                                {{ $transaction->sender_id == auth()->id() ? '📤' : '📥' }}
                            @elseif($transaction->transaction_type == 'payment')
                                🛒
                            @else
                                💳
                            @endif
                        </span>
                        <div>
                            <h4 style="margin: 0;">
                                @if($transaction->transaction_type == 'transfer')
                                    @if($transaction->sender_id == auth()->id())
                                        {{ __('messages.transfer') }} إلى {{ $transaction->receiver->name }}
                                    @else
                                        {{ __('messages.transfer') }} من {{ $transaction->sender->name }}
                                    @endif
                                @elseif($transaction->transaction_type == 'payment')
                                    {{ __('messages.payment') }} لـ {{ $transaction->merchant_name }}
                                @endif
                            </h4>
                            <div style="color: #666; font-size: 0.9rem; margin-top: 0.3rem;">
                                <strong>السبب:</strong> {{ $transaction->description }}
                            </div>
                            <div style="color: #999; font-size: 0.85rem; margin-top: 0.3rem;">
                                {{ $transaction->created_at->format('d/m/Y H:i') }} •
                                رقم المرجع: {{ $transaction->reference_number }}
                            </div>
                        </div>
                    </div>
                </div>
                <div style="text-align: left; margin-right: 1rem;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: {{ $transaction->sender_id == auth()->id() ? '#dc3545' : '#28a745' }};">
                        {{ $transaction->sender_id == auth()->id() ? '-' : '+' }}{{ __('messages.currency_symbol') }}{{ number_format($transaction->amount, 2) }}
                    </div>
                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.3rem;">
                        {{ $transaction->payment_method }}
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 3rem; color: #666;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
                <p>{{ __('messages.no_transactions') }} لعرضها</p>
            </div>
        @endforelse

        @if($transactions->hasPages())
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">العودة ل{{ __('messages.dashboard') }}</a>
    </div>
</div>
@endsection
