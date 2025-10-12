@extends('layouts.app')

@section('title',  __('messages.pay_merchant') )

@section('content')
<div style="max-width: 700px; margin: 2rem auto;">
    <div class="card">
        <h2 style="color: #667eea; margin-bottom: 2rem;">🛒 ال{{ __('messages.pay_merchant') }}</h2>

        <form method="POST" action="{{ route('pay.merchant') }}">
            @csrf

            <div class="form-group">
                <label style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 10px; cursor: pointer; border: 2px solid transparent; transition: 0.3s;"
                               onmouseover="this.style.borderColor='#667eea'; this.style.background='white';">اختر التاجر</label>
                <div style="display: grid; gap: 1rem; margin-top: 1rem;">
                    @foreach($merchants as $merchant)
                        <label style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 10px; cursor: pointer; border: 2px solid transparent; transition: 0.3s;"
                               onmouseover="this.style.borderColor='#667eea'; this.style.background='white';"
                               onmouseout="if(!this.querySelector('input').checked) { this.style.borderColor='transparent'; this.style.background='#f8f9fa'; }">
                            <input type="radio" name="merchant_id" value="{{ $merchant->id }}" required
                                   style="margin-left: 1rem; width: 20px; height: 20px;">
                            <div style="flex: 1;">
                                <div style="font-size: 1.1rem; font-weight: bold; margin-bottom: 0.3rem;">
                                    {{ $merchant->name }}
                                </div>
                                <div style="color: #666; font-size: 0.9rem;">
                                    {{ $merchant->business_type }}
                                </div>
                            </div>
                            <div style="font-size: 2rem;">🏪</div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('messages.amount') }} (₪)</label>
                <input type="number" name="amount" class="form-control"
                       step="0.01" min="1" placeholder="100.00" required>
            </div>

            <div class="form-group">
                <label>ماذا تشتري؟</label>
                <input type="text" name="description" class="form-control"
                       placeholder="مثال: شراء هاتف، فاتورة كهرباء، اشتراك شهري" required>
            </div>

            <div class="form-group">
                <label>رقم PIN (4 أرقام)</label>
                <input type="password" name="pin" class="form-control"
                       maxlength="4" placeholder="****" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                {{ __('messages.payment_confirmation') }} 💳
            </button>
        </form>

        <div style="margin-top: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 10px;">
            <div style="display: flex; justify-content: space-between;">
                <strong>{{ __('messages.current_balance') }}:</strong>
                <span style="color: #667eea; font-weight: bold;">{{ __('messages.currency_symbol') }}{{ number_format(auth()->user()->balance, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
