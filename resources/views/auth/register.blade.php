@extends('layouts.app')

@section('title',  __('messages.create') .' حساب')

@section('content')
<div style="max-width: 500px; margin: 3rem auto;">
    <div class="card">
        <div style="text-align: center; font-size: 4rem; margin-bottom: 1rem;">✨</div>
        <h2 style="text-align: center; color: #667eea; margin-bottom: 2rem;">{{ __('messages.create') }} حساب جديد</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label>{{ __('messages.name') }} الكامل</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.phone') }}</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="0501234567" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.password') }}</label>
                <input type="password" name="password" class="form-control" placeholder="8 أحرف على الأقل" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.confirm_password') }}</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="form-group">
                <label>رقم PIN لل{{ __('messages.payment') }} (4 أرقام)</label>
                <input type="password" name="pin" class="form-control" maxlength="4" placeholder="****" required>
                <small style="color: #666;">ستحتاج هذا الرقم لتأكيد جميع {{ __('messages.transactions') }}</small>
            </div>

            <div class="form-group">
                <label>{{ __('messages.confirm') }} PIN</label>
                <input type="password" name="pin_confirmation" class="form-control" maxlength="4" placeholder="****" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                {{ __('messages.create') }} الحساب
            </button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; color: #666;">
            {{ __('messages.already_have_account') }}
            <a href="{{ route('login') }}" style="color: #667eea; font-weight: bold;">سجل الدخول</a>
        </p>
    </div>
</div>
@endsection
