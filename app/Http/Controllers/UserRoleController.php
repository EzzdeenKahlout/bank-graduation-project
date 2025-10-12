<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:assign_roles');
    }

    /**
     * عرض المستخدمين وأدوارهم
     */
    public function index()
    {
        $users = User::with('roles')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * عرض صفحة تعديل أدوار المستخدم
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('admin.users.edit-roles', compact('user', 'roles', 'userRoles'));
    }

    /**
     * تحديث أدوار المستخدم
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        // منع إزالة Super Admin من نفسه
        if ($user->id === auth()->id() && $user->hasRole('super_admin')) {
            $superAdminRole = Role::where('name', 'super_admin')->first();
            if (!in_array($superAdminRole->id, $request->roles ?? [])) {
                return back()->with('error', __('messages.cannot_remove_own_super_admin'));
            }
        }

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        } else {
            $user->roles()->sync([]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.user_roles_updated'));
    }
}
