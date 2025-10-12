<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string',
            'pin' => 'required|digits:4|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'account_number' => User::generateAccountNumber(),
            'balance' => 1000.00,
            'pin_code' => Hash::make($request->pin),
            'qr_code' => User::generateQRCode(),
            'daily_limit' => 5000.00,
            'daily_spent' => 0,
            'daily_reset_date' => today(),
            'preferred_language' => app()->getLocale(),
        ]);

        // إعطاء المستخدم دور "user" الافتراضي
        $user->assignRole('user');

        // إنشاء بطاقة افتراضية بدون CVV (لأسباب أمنية)
        Card::create([
            'user_id' => $user->id,
            'card_number' => Card::generateCardNumber(),
            'card_holder_name' => $user->name,
            'card_type' => 'debit',
            'expiry_date' => now()->addYears(3),
            'is_active' => true,
            'is_blocked' => false,
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', __('messages.registration_success'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            session(['locale' => auth()->user()->preferred_language]);
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email' => __('auth.failed')]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
