@extends('layouts.app')

@section('title',  __('messages.settings') )

@section('content')
<div style="max-width: 800px; margin: 2rem auto;">
    <h1 style="color: white; margin-bottom: 2rem;">⚙️ الإعدادات</h1>

    <!-- {{ __('messages.profile') }} -->
    <div class="card" style="margin-bottom: 2rem;">
        <h3 style="color: #667eea; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
            👤 {{ __('messages.personal_info') }}
        </h3>
        <form method="POST" action="{{ route('settings.profile') }}">
            @csrf
            <div class="form-group">
                <label>{{ __('messages.name') }} الكامل</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div class="form-group">
                <label>{{ __('messages.email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>
            <div class="form-group">
                <label>{{ __('messages.phone') }}</label>
                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('messages.save') }} التغييرات</button>
        </form>
    </div>

    <!-- {{ __('messages.password') }} -->
    <div class="card" style="margin-bottom: 2rem;">
        <h3 style="color: #667eea; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
            🔒 {{ __('messages.change_password') }}
        </h3>
        <form method="POST" action="{{ route('settings.password') }}">
            @csrf
            <div class="form-group">
                <label>{{ __('messages.current_password') }}</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>{{ __('messages.new_password') }}</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>تأكيد {{ __('messages.new_password') }}</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">تحديث {{ __('messages.password') }}</button>
        </form>
    </div>

    <!-- رقم PIN -->
    <div class="card" style="margin-bottom: 2rem;">
        <h3 style="color: #667eea; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
            🔢 رقم PIN لل{{ __('messages.payment') }}
        </h3>
        <form method="POST" action="{{ route('settings.pin') }}">
            @csrf
            @if($user->pin_code)
            <div class="form-group">
                <label>رقم PIN الحالي</label>
                <input type="password" name="current_pin" class="form-control" maxlength="4" placeholder="****">
            </div>
            @endif
            <div class="form-group">
                <label>رقم PIN جديد (4 أرقام)</label>
                <input type="password" name="new_pin" class="form-control" maxlength="4" placeholder="****" required>
            </div>
            <div class="form-group">
                <label>{{ __('messages.confirm') }} رقم PIN</label>
                <input type="password" name="new_pin_confirmation" class="form-control" maxlength="4" placeholder="****" required>
            </div>
            <button type="submit" class="btn btn-primary">
                {{ $user->pin_code ? 'تحديث PIN' :  __('messages.create') .' PIN' }}
            </button>
        </form>
    </div>

    <!-- {{ __('messages.daily_limit') }} -->
    <div class="card" style="margin-bottom: 2rem;">
        <h3 style="color: #667eea; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
            💰 {{ __('messages.daily_limit') }} للمصروفات
        </h3>
        <form method="POST" action="{{ route('settings.daily-limit') }}">
            @csrf
            <div class="form-group">
                <label>{{ __('messages.daily_limit') }} (₪)</label>
                <input type="number" name="daily_limit" class="form-control" 
                       value="{{ $user->daily_limit }}" min="100" max="50000" step="100" required>
                <small style="color: #666;">
                    الحد الأدنى: {{ __('messages.currency_symbol') }}100 | الحد الأقصى: ₪50,000<br>
                    المصروف {{ __('messages.today') }}: ₪{{ number_format($user->daily_spent, 2) }}
                </small>
            </div>
            <button type="submit" class="btn btn-primary">حفظ {{ __('messages.daily_limit') }}</button>
        </form>
    </div>

    <!-- {{ __('messages.info') }} الحساب -->
    <div class="card">
        <h3 style="color: #667eea; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
            📊 {{ __('messages.info') }} الحساب
        </h3>
        <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px;">
            <div style="display: grid; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e0e0e0;">
                    <span><strong>{{ __('messages.account_number') }}:</strong></span>
                    <span style="color: #667eea; font-family: monospace;">{{ $user->account_number }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e0e0e0;">
                    <span><strong>رمز QR:</strong></span>
                    <span style="color: #667eea; font-family: monospace;">{{ $user->qr_code ?? 'غير متوفر' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e0e0e0;">
                    <span><strong>{{ __('messages.member_since') }}:</strong></span>
                    <span>{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                    <span><strong>{{ __('messages.active_cards') }}:</strong></span>
                    <span style="color: #28a745; font-weight: bold;">{{ $user->cards->where('is_active', true)->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">العودة ل{{ __('messages.dashboard') }}</a>
    </div>
</div>
@endsection