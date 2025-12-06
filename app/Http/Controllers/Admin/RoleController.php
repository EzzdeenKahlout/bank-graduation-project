<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{


    /**
     * عرض قائمة الأدوار
     */
     public function index()
    {
        $roles = Role::with(['permissions', 'users'])->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * عرض صفحة إنشاء دور جديد
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * حفظ دور جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|alpha_dash',
            'display_name' => 'required',
            'description' => 'nullable',
            'permissions' => 'array',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', __('messages.role_created_successfully'));
    }

    /**
     * عرض صفحة تعديل الدور
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('group');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        // تحديد ما إذا كان Super Admin
        $isSuperAdmin = $role->name === 'super_admin';

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions', 'isSuperAdmin'));
    }

    /**
     * تحديث الدور
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|alpha_dash',
            'display_name' => 'required',
            'description' => 'nullable',
            'permissions' => 'array',
        ]);

        // منع تعديل اسم Super Admin
        if ($role->name === 'super_admin') {
            $role->update([
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            // لا نغير الصلاحيات - Super Admin يحتفظ بكل الصلاحيات
            return redirect()->route('admin.roles.index')
                ->with('success', __('messages.role_updated_successfully'));
        }

        // للأدوار الأخرى - تحديث عادي
        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        // تحديث الصلاحيات
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->sync([]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', __('messages.role_updated_successfully'));
    }

    /**
     * حذف الدور
     */
    public function destroy(Role $role)
    {
        // منع حذف Super Admin
        if ($role->name === 'super_admin') {
            return back()->with('error', __('messages.cannot_delete_super_admin'));
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', __('messages.role_deleted_successfully'));
    }
}
