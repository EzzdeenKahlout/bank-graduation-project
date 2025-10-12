@extends('layouts.app')

@section('title',  __('messages.login') )

@section('content')
<div style="max-width: 450px; margin: 3rem auto;">
    <div class="card">
        <div style="text-align: center; font-size: 4rem; margin-bottom: 1rem;">🔐</div>
        <h2 style="text-align: center; color: #667eea; margin-bottom: 2rem;">{{ __('messages.login') }}</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>{{ __('messages.email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="example@email.com" required autofocus>
            </div>

            <div class="form-group">
                <label>{{ __('messages.password') }}</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="remember"> {{ __('messages.remember_me') }}
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                دخول
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e0e0e0;">
            <p style="color: #666;">{{ __('messages.dont_have_account') }}</p>
            <a href="{{ route('register') }}" class="btn btn-secondary" style="margin-top: 1rem;">
                {{ __('messages.create') }} حساب جديد
            </a>
        </div>
    </div>
</div>
@endsection
