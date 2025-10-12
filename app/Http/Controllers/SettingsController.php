<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'phone', 'email']));

        return back()->with('success', 'تم تحديث الملف الشخصي');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'تم تحديث كلمة المرور');
    }

    public function updatePin(Request $request)
    {
        $request->validate([
            'current_pin' => 'nullable|digits:4',
            'new_pin' => 'required|digits:4|confirmed',
        ]);

        $user = auth()->user();

        if ($user->pin_code && $request->current_pin) {
            if (!$user->verifyPin($request->current_pin)) {
                return back()->withErrors(['current_pin' => 'الرقم السري الحالي غير صحيح']);
            }
        }

        $user->update(['pin_code' => Hash::make($request->new_pin)]);

        return back()->with('success', 'تم تحديث الرقم السري');
    }

    public function updateDailyLimit(Request $request)
    {
        $request->validate([
            'daily_limit' => 'required|numeric|min:100|max:50000',
        ]);

        auth()->user()->update(['daily_limit' => $request->daily_limit]);

        return back()->with('success', 'تم تحديث الحد اليومي');
    }
}
