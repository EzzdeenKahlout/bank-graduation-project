@extends('layouts.app')

@section('title', 'البنك الرقمي - الصفحة '. __('messages.home') )

@section('content')
<div style="text-align: center; color: white; padding: 4rem 2rem;">
    <h1 style="font-size: 3rem; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
        🏦 البنك الرقمي المتقدم
    </h1>
    <p style="font-size: 1.5rem; margin-bottom: 2rem;">
        إدارة أموالك بسهولة وأمان تام
    </p>

    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('register') }}" class="btn btn-primary" style="font-size: 1.2rem; padding: 1rem 2rem;">
            ابدأ الآن مجاناً ✨
        </a>
        <a href="{{ route('login') }}" class="btn btn-secondary" style="font-size: 1.2rem; padding: 1rem 2rem; background: white; color: #667eea;">
            {{ __('messages.login') }}
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">💳</div>
        <h3 style="color: #667eea; margin-bottom: 1rem;">بطاقات ذكية</h3>
        <p>احصل على بطاقتك الرقمية فوراً مع ميزات أمان متقدمة</p>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🚀</div>
        <h3 style="color: #667eea; margin-bottom: 1rem;">{{ __('messages.transfer') }}ات فورية</h3>
        <p>حوّل الأموال لأصدقائك بسرعة وبدون {{ __('messages.fee') }}</p>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🛒</div>
        <h3 style="color: #667eea; margin-bottom: 1rem;">ال{{ __('messages.payment') }} للتجار</h3>
        <p>ا{{ __('messages.payment') }} في آلاف المتاجر بضغطة زر واحدة</p>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📊</div>
        <h3 style="color: #667eea; margin-bottom: 1rem;">تتبع المصروفات</h3>
        <p>راقب معاملاتك ومصروفاتك بسهولة</p>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🔒</div>
        <h3 style="color: #667eea; margin-bottom: 1rem;">أمان عالي</h3>
        <p>حماية متقدمة لأموالك وبياناتك</p>
    </div>

    <div class="card" style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📱</div>
        <h3 style="color: #667eea; margin-bottom: 1rem;">سهل الاستخدام</h3>
        <p>واجهة بسيطة وعصرية لجميع العمليات</p>
    </div>
</div>
@endsection
