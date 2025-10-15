<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display users list
     */
    public function index(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('view_users')) {
            abort(403, 'Unauthorized action.');
        }

        $query = User::with('permissions');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by permission
        if ($request->has('permission')) {
            $query->whereHas('permissions', function($q) use ($request) {
                $q->where('name', $request->permission);
            });
        }

        $users = $query->latest()->paginate(20);
        $permissions = Permission::all();

        return view('admin.users.index', compact('users', 'permissions'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        // Check permission
        if (!auth()->user()->hasPermission('create_users')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = Permission::all();
        return view('admin.users.create', compact('permissions'));
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('create_users')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'pin_code' => 'required|numeric|digits:4',
            'balance' => 'nullable|numeric|min:0',
            'daily_limit' => 'nullable|numeric|min:0',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'pin_code' => Hash::make($validated['pin_code']),
            'balance' => $validated['balance'] ?? 0,
            'daily_limit' => $validated['daily_limit'] ?? 5000,
            'is_active' => true,
        ]);

        // Assign permissions
        if (!empty($validated['permissions'])) {
            $user->permissions()->attach($validated['permissions']);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', __('messages.user_created_successfully'));
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('view_users')) {
            abort(403, 'Unauthorized action.');
        }

        $user->load(['permissions', 'cards', 'transactions']);

        $stats = [
            'total_transactions' => \App\Models\Transaction::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })->count(),
            'total_spent' => $user->transactions()->where('status','completed')->sum('amount'),
            'total_received' => $user->receivedTransactions()->where('status','completed')->sum('amount'),
            'active_cards' => $user->cards()->where('status', 'active')->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('edit_users')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = Permission::all();
        $userPermissions = $user->permissions->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'permissions', 'userPermissions'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('edit_users')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'pin_code' => 'nullable|numeric|digits:4',
            'balance' => 'nullable|numeric|min:0',
            'daily_limit' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'daily_limit' => $validated['daily_limit'] ?? $user->daily_limit,
            'is_active' => $request->has('is_active'),
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        if (!empty($validated['pin_code'])) {
            $userData['pin_code'] = Hash::make($validated['pin_code']);
        }

        if (isset($validated['balance'])) {
            $userData['balance'] = $validated['balance'];
        }

        $user->update($userData);

        // Sync permissions
        if (isset($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        }

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', __('messages.user_updated_successfully'));
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('delete_users')) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', __('messages.cannot_delete_yourself'));
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', __('messages.user_deleted_successfully'));
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('edit_users')) {
            abort(403, 'Unauthorized action.');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', __('messages.user_status_updated'));
    }

    /**
     * Manage user permissions
     */
    public function permissions(User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = Permission::all();
        $userPermissions = $user->permissions->pluck('id')->toArray();

        return view('admin.users.permissions', compact('user', 'permissions', 'userPermissions'));
    }

    /**
     * Update user permissions
     */
    public function updatePermissions(Request $request, User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user->permissions()->sync($validated['permissions'] ?? []);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', __('messages.permissions_updated_successfully'));
    }

    /**
     * Export users
     */
    public function export(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('export_reports')) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::with('permissions')->get();

        $filename = 'users_export_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Balance', 'Daily Limit', 'Status', 'Permissions', 'Created At']);

            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->balance,
                    $user->daily_limit,
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->permissions->pluck('name')->implode(', '),
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
