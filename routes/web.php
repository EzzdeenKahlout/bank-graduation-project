<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CardManagementController;
use App\Http\Controllers\Admin\PermissionsManagementController;
use App\Http\Controllers\Admin\TransactionManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\RoleController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Language Switch
Route::get('language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->where('locale', 'ar|en');

/*
|--------------------------------------------------------------------------
| Guest Routes (Login & Register)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions
    Route::prefix('transactions')->group(function () {
        Route::get('/transfer-friend', [TransactionController::class, 'showTransferToFriend'])
            ->name('transfer.friend');
        Route::post('/transfer-friend', [TransactionController::class, 'transferToFriend']);

        Route::get('/pay-merchant', [TransactionController::class, 'showPayToMerchant'])
            ->name('pay.merchant');
        Route::post('/pay-merchant', [TransactionController::class, 'payToMerchant']);

        // API endpoint for fetching merchants with search
        Route::get('/api/merchants', [TransactionController::class, 'getMerchants'])
            ->name('api.merchants');

        Route::get('/history', [TransactionController::class, 'history'])
            ->name('transactions.history');
    });

    // Cards
    Route::prefix('cards')->group(function () {
        Route::get('/', [CardController::class, 'index'])->name('cards.index');
        Route::get('/request', [CardController::class, 'requestNewCard'])->name('cards.request');
        Route::post('/request', [CardController::class, 'storeRequest']);
        Route::post('/{card}/toggle-block', [CardController::class, 'toggleBlock'])
            ->name('cards.toggle-block');
        Route::post('/{card}/toggle-active', [CardController::class, 'toggleActive'])
            ->name('cards.toggle-active');
    });

    // Settings
    Route::prefix('settings')->group(function () {
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
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Super Admin & Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->middleware('permission:view_admin_dashboard')->name('dashboard');
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->middleware('permission:view_admin_analytics')->name('analytics');
    Route::get('/health', [AdminDashboardController::class, 'health'])->middleware('permission:view_admin_health')->name('health');

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->middleware('permission:view_users')->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->middleware('permission:create_users')->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->middleware('permission:create_users')->name('store');
        Route::get('/{user}', [UserManagementController::class, 'show'])->middleware('permission:view_users')->name('show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->middleware('permission:edit_users')->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->middleware('permission:edit_users')->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->middleware('permission:delete_users')->name('destroy');
        Route::post('/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->middleware('permission:edit_users')->name('toggle-status');
        Route::get('/{user}/permissions', [UserManagementController::class, 'permissions'])->middleware('permission:assign_roles')->name('permissions');
        Route::put('/{user}/permissions', [UserManagementController::class, 'updatePermissions'])->middleware('permission:assign_roles')->name('update-permissions');
        Route::get('/export/csv', [UserManagementController::class, 'export'])->middleware('permission:view_users')->name('export');
    });

    // Cards Management
    Route::prefix('cards')->name('cards.')->group(function () {
        Route::post('/requests/{cardRequest}/approve', [CardManagementController::class, 'approveRequest'])->middleware('permission:view_cards')->name('requests.approve');
        Route::get('/', [CardManagementController::class, 'index'])->middleware('permission:view_cards')->name('index');
        Route::get('/create', [CardManagementController::class, 'create'])->middleware('permission:create_cards')->name('create');
        Route::post('/', [CardManagementController::class, 'store'])->middleware('permission:create_cards')->name('store');
        Route::get('/{card}', [CardManagementController::class, 'show'])->name('show');
        Route::post('/{card}/block', [CardManagementController::class, 'block'])->middleware('permission:block_cards')->name('block');
        Route::post('/{card}/unblock', [CardManagementController::class, 'unblock'])->name('unblock');
        Route::delete('/{card}', [CardManagementController::class, 'destroy'])->middleware('permission:delete_cards')->name('destroy');
        Route::get('/export/csv', [CardManagementController::class, 'export'])->middleware('permission:view_cards')->name('export');

        // Card Requests
        Route::get('/requests/list', [CardManagementController::class, 'requests'])->name('requests');
        Route::post('/requests/{request}/approve', [CardManagementController::class, 'approveRequest'])->middleware('permission:approve_cards')->name('approve-request');
        Route::post('/requests/{request}/reject', [CardManagementController::class, 'rejectRequest'])->name('reject-request');
    });

    // Transactions Management
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionManagementController::class, 'index'])->middleware('permission:view_transactions')->name('index');
        Route::get('/{transaction}', [TransactionManagementController::class, 'show'])->middleware('permission:view_transactions')->name('show');
        Route::post('/{transaction}/cancel', [TransactionManagementController::class, 'cancel'])->middleware('permission:cancel_transactions')->name('cancel');
        Route::post('/{transaction}/refund', [TransactionManagementController::class, 'refund'])->middleware('permission:refund_transactions')->name('refund');
        Route::get('/export/csv', [TransactionManagementController::class, 'export'])->middleware('permission:view_transactions')->name('export');
        Route::get('/analytics/report', [TransactionManagementController::class, 'analytics'])->middleware('permission:view_admin_analytics')->name('analytics');
    });

    // Permissions Management
    
    // Roles Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->middleware('permission:manage_roles')->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\RoleController::class, 'create'])->middleware('permission:manage_roles')->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\RoleController::class, 'store'])->middleware('permission:manage_roles')->name('store');
        Route::get('/{role}/edit', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])->middleware('permission:manage_roles')->name('edit');
        Route::put('/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])->middleware('permission:manage_roles')->name('update');
        Route::delete('/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])->middleware('permission:manage_roles')->name('destroy');
    });
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionsManagementController::class, 'index'])->name('index');
        Route::get('/create', [PermissionsManagementController::class, 'create'])->name('create');
        Route::post('/', [PermissionsManagementController::class, 'store'])->name('store');
        Route::get('/{permission}/edit', [PermissionsManagementController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [PermissionsManagementController::class, 'update'])->name('update');
        Route::delete('/{permission}', [PermissionsManagementController::class, 'destroy'])->name('destroy');
        Route::get('/{permission}/users', [PermissionsManagementController::class, 'users'])->name('users');
        Route::post('/bulk-assign', [PermissionsManagementController::class, 'bulkAssign'])->name('bulk-assign');
        Route::post('/bulk-remove', [PermissionsManagementController::class, 'bulkRemove'])->name('bulk-remove');
        Route::post('/seed', [PermissionsManagementController::class, 'seed'])->name('seed');
        Route::get('/matrix/view', [PermissionsManagementController::class, 'matrix'])->name('matrix');
    });
});

/*
|--------------------------------------------------------------------------
| Permission-based Routes
|--------------------------------------------------------------------------
*/

// Reports (requires view_reports permission)
Route::middleware(['auth', 'permission:view_reports'])->group(function () {
    Route::get('/reports', function () {
        return view('reports.index');
    })->name('reports.index');
});

// Transfer to Friend (requires transfer_money permission)
Route::middleware(['auth', 'permission:transfer_money'])->group(function () {
    Route::get('/transfer/friend', function () {
        return view('transfer.friend');
    })->name('transfer.friend');
});