@extends('layouts.app')

@section('title',  __('messages.transfer_to_friend') )

@section('content')
<div style="max-width: 600px; margin: 2rem auto;">
    <div class="card">
        <div style="text-align: center; font-size: 3rem; margin-bottom: 1rem;">👥</div>
        <h2 style="text-align: center; color: #667eea; margin-bottom: 2rem;">{{ __('messages.transfer') }} أموال لصديق</h2>

        <form method="POST" action="{{ route('transfer.friend') }}">
            @csrf

            <div class="form-group">
                <label>رقم حساب الصديق</label>
                <input type="text" name="account_number" class="form-control" placeholder="ACC1234567890" required>
                <small style="color: #666;">أدخل رقم حساب الصديق المكون {{ __('messages.from') }} 13 رقم</small>
            </div>

            <div class="form-group">
                <label>{{ __('messages.amount') }} (₪)</label>
                <input type="number" name="amount" class="form-control" step="0.01" min="1" placeholder="100.00" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.transfer_reason') }} (اختياري)</label>
                <input type="text" name="description" class="form-control" placeholder="مثال: قرض، هدية، مساعدة">
            </div>

            <div class="form-group">
                <label>أدخل رقم PIN (4 أرقام)</label>
                <input type="password" name="pin" class="form-control" maxlength="4" placeholder="****" required>
                <small style="color: #666;">الرقم السري للتحقق {{ __('messages.from') }} هويتك</small>
            </div>

            <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>{{ __('messages.current_balance') }}:</span>
                    <strong style="color: #667eea;">{{ __('messages.currency_symbol') }}{{ number_format(auth()->user()->balance, 2) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>المتبقي من {{ __('messages.daily_limit') }}:</span>
                    <strong style="color: #28a745;">{{ __('messages.currency_symbol') }}{{ number_format(auth()->user()->daily_limit - auth()->user()->daily_spent, 2) }}</strong>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                {{ __('messages.transfer_confirmation') }} 💸
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('dashboard') }}" style="color: #667eea;">العودة ل{{ __('messages.dashboard') }}</a>
        </div>
    </div>
</div>
@endsection
