<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionsManagementController extends Controller
{
    /**
     * Display permissions list
     */
    public function index()
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = Permission::withCount('users')->get()->groupBy('category');

        $stats = [
            'total' => Permission::count(),
            'assigned' => Permission::has('users')->count(),
            'users_with_permissions' => User::has('permissions')->count(),
        ];

        return view('admin.permissions.index', compact('permissions', 'stats'));
    }

    /**
     * Show create permission form
     */
    public function create()
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Permission::select('category')->distinct()->pluck('category');

        return view('admin.permissions.create', compact('categories'));
    }

    /**
     * Store new permission
     */
    public function store(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
        ]);

        // Auto-generate slug from name
        $validated['name'] = Str::slug(str_replace(' ', '_', $validated['name']), '_');

        Permission::create($validated);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', __('messages.permission_created_successfully'));
    }

    /**
     * Show edit permission form
     */
    public function edit(Permission $permission)
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Permission::select('category')->distinct()->pluck('category');

        return view('admin.permissions.edit', compact('permission', 'categories'));
    }

    /**
     * Update permission
     */
    public function update(Request $request, Permission $permission)
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
        ]);

        $permission->update($validated);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', __('messages.permission_updated_successfully'));
    }

    /**
     * Delete permission
     */
    public function destroy(Permission $permission)
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        // Check if permission is assigned to users
        if ($permission->users()->count() > 0) {
            return back()->with('error', __('messages.cannot_delete_assigned_permission'));
        }

        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', __('messages.permission_deleted_successfully'));
    }

    /**
     * Show users with specific permission
     */
    public function users(Permission $permission)
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $users = $permission->users()->paginate(20);

        return view('admin.permissions.users', compact('permission', 'users'));
    }

    /**
     * Bulk assign permissions
     */
    public function bulkAssign(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        foreach ($validated['user_ids'] as $userId) {
            $user = User::find($userId);
            $user->permissions()->syncWithoutDetaching($validated['permission_ids']);
        }

        return back()->with('success', __('messages.permissions_assigned_successfully'));
    }

    /**
     * Bulk remove permissions
     */
    public function bulkRemove(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        foreach ($validated['user_ids'] as $userId) {
            $user = User::find($userId);
            $user->permissions()->detach($validated['permission_ids']);
        }

        return back()->with('success', __('messages.permissions_removed_successfully'));
    }

    /**
     * Seed default permissions
     */
    public function seed()
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $defaultPermissions = [
            // Admin Dashboard
            ['name' => 'view_admin_dashboard', 'display_name' => 'View Admin Dashboard', 'category' => 'Admin', 'description' => 'Access admin dashboard'],
            ['name' => 'view_system_health', 'display_name' => 'View System Health', 'category' => 'Admin', 'description' => 'View system health status'],

            // Users
            ['name' => 'view_users', 'display_name' => 'View Users', 'category' => 'Users', 'description' => 'View all users'],
            ['name' => 'create_users', 'display_name' => 'Create Users', 'category' => 'Users', 'description' => 'Create new users'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users', 'category' => 'Users', 'description' => 'Edit user information'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users', 'category' => 'Users', 'description' => 'Delete users'],

            // Cards
            ['name' => 'view_cards', 'display_name' => 'View Cards', 'category' => 'Cards', 'description' => 'View all cards'],
            ['name' => 'create_cards', 'display_name' => 'Create Cards', 'category' => 'Cards', 'description' => 'Create new cards'],
            ['name' => 'approve_cards', 'display_name' => 'Approve Cards', 'category' => 'Cards', 'description' => 'Approve card requests'],
            ['name' => 'block_cards', 'display_name' => 'Block Cards', 'category' => 'Cards', 'description' => 'Block/Unblock cards'],
            ['name' => 'delete_cards', 'display_name' => 'Delete Cards', 'category' => 'Cards', 'description' => 'Delete cards'],

            // Transactions
            ['name' => 'view_all_transactions', 'display_name' => 'View All Transactions', 'category' => 'Transactions', 'description' => 'View all user transactions'],
            ['name' => 'cancel_transactions', 'display_name' => 'Cancel Transactions', 'category' => 'Transactions', 'description' => 'Cancel pending transactions'],
            ['name' => 'refund_transactions', 'display_name' => 'Refund Transactions', 'category' => 'Transactions', 'description' => 'Refund completed transactions'],

            // Reports
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'category' => 'Reports', 'description' => 'View analytics and reports'],
            ['name' => 'export_reports', 'display_name' => 'Export Reports', 'category' => 'Reports', 'description' => 'Export data to CSV/Excel'],

            // Permissions
            ['name' => 'manage_permissions', 'display_name' => 'Manage Permissions', 'category' => 'System', 'description' => 'Create and manage permissions'],

            // Settings
            ['name' => 'manage_settings', 'display_name' => 'Manage Settings', 'category' => 'System', 'description' => 'Manage system settings'],
            ['name' => 'view_logs', 'display_name' => 'View Logs', 'category' => 'System', 'description' => 'View system logs'],
        ];

        foreach ($defaultPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', __('messages.default_permissions_created'));
    }

    /**
     * Permission matrix view
     */
    public function matrix()
    {
        // Check permission
        if (!auth()->user()->hasPermission('manage_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::with('permissions')->get();
        $permissions = Permission::all()->groupBy('category');

        return view('admin.permissions.matrix', compact('users', 'permissions'));
    }
}
