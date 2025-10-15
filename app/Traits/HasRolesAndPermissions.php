<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRolesAndPermissions
{
    /**
     * العلاقة مع الأدوار
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * العلاقة مع الصلاحيات (مباشرة للمستخدم)
     */
    public function permissions()
    {
        return $this->belongsToMany(\App\Models\Permission::class, 'permission_user');
    }

    /**
     * إعطاء دور للمستخدم
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role instanceof Role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }

        return $this;
    }

    /**
     * إزالة دور من المستخدم
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role instanceof Role) {
            $this->roles()->detach($role->id);
        }

        return $this;
    }

    /**
     * التحقق من وجود دور
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->hasRole($r)) {
                    return true;
                }
            }
            return false;
        }

        return $this->roles->contains($role);
    }

    /**
     * التحقق من جميع الأدوار
     */
    public function hasAllRoles($roles)
    {
        if (is_string($roles)) {
            return $this->hasRole($roles);
        }

        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * التحقق من وجود صلاحية (مباشرة أو عبر الأدوار)
     */
    public function hasPermission($permission)
    {
        // Direct permission
        if ($this->permissions()->where('name', $permission)->exists()) {
            return true;
        }

        // Via roles
        foreach ($this->roles as $role) {
            if ($role->permissions()->where('name', $permission)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * التحقق من أي صلاحية
     */
    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            return $this->hasPermission($permissions);
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * التحقق من جميع الصلاحيات
     */
    public function hasAllPermissions($permissions)
    {
        if (is_string($permissions)) {
            return $this->hasPermission($permissions);
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * الحصول على كل الصلاحيات (مباشرة + عبر الأدوار) بشكل فريد
     */
    public function getAllPermissions()
    {
        $direct = $this->permissions()->get();
        $viaRoles = $this->roles()->with('permissions')->get()->pluck('permissions')->flatten();
        return $direct->merge($viaRoles)->unique('id')->values();
    }

    /**
     * التحقق إذا كان Super Admin
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

    /**
     * التحقق إذا كان Admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->isSuperAdmin();
    }
}
