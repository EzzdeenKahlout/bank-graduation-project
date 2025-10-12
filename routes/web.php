<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LanguageController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->where('locale', 'ar|en');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('transactions')->group(function () {
        Route::get('/transfer-friend', [TransactionController::class, 'showTransferToFriend'])
            ->name('transfer.friend');
        Route::post('/transfer-friend', [TransactionController::class, 'transferToFriend']);

        Route::get('/pay-merchant', [TransactionController::class, 'showPayToMerchant'])
            ->name('pay.merchant');
        Route::post('/pay-merchant', [TransactionController::class, 'payToMerchant']);

        Route::get('/history', [TransactionController::class, 'history'])
            ->name('transactions.history');
    });

    Route::prefix('cards')->group(function () {
        Route::get('/', [CardController::class, 'index'])->name('cards.index');
        Route::get('/request', [CardController::class, 'requestNewCard'])->name('cards.request');
        Route::post('/request', [CardController::class, 'storeRequest']);
        Route::post('/{card}/toggle-block', [CardController::class, 'toggleBlock'])
            ->name('cards.toggle-block');
        Route::post('/{card}/toggle-active', [CardController::class, 'toggleActive'])
            ->name('cards.toggle-active');
    });

    Route::prefix(prefix: 'settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/profile', [SettingsController::class, 'updateProfile'])
            ->name('settings.profile');
        Route::post('/password', [SettingsController::class, 'updatePassword'])
            ->name('settings.password');
        Route::post('/pin', [SettingsController::class, 'updatePin'])
            ->name('settings.pin');
        Route::post('/daily-limit', [SettingsController::class, 'updateDailyLimit'])
            ->name('settings.daily-limit');
    });

    Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

    // مسارات الأدوار (Super Admin & Admin فقط)
Route::middleware(['auth', 'permission:manage_roles'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('roles', RoleController::class);
});

// مسارات إدارة أدوار المستخدمين
Route::middleware(['auth', 'permission:assign_roles'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users', [UserRoleController::class, 'index'])->name('users.index');
    Route::get('users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.edit-roles');
    Route::put('users/{user}/roles', [UserRoleController::class, 'update'])->name('users.update-roles');
});

// أمثلة على استخدام Middleware في المسارات
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    // مسارات خاصة بالـ Admin و Super Admin
});

Route::middleware(['auth', 'permission:view_reports'])->group(function () {
    Route::get('/reports', function () {
        return view('reports.index');
    })->name('reports.index');
});

Route::middleware(['auth', 'permission:transfer_money'])->group(function () {
    Route::get('/transfer/friend', function () {
        return view('transfer.friend');
    })->name('transfer.friend');
    });

});
