<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
    {
        // إنشاء الصلاحيات
        $permissions = [
            // إدارة المستخدمين
            ['name' => 'view_users', 'display_name' => 'View Users', 'description' => 'Can view users list', 'group' => 'users'],
            ['name' => 'create_users', 'display_name' => 'Create Users', 'description' => 'Can create new users', 'group' => 'users'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users', 'description' => 'Can edit users', 'group' => 'users'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users', 'description' => 'Can delete users', 'group' => 'users'],

            // إدارة البطاقات
            ['name' => 'view_cards', 'display_name' => 'View Cards', 'description' => 'Can view cards', 'group' => 'cards'],
            ['name' => 'create_cards', 'display_name' => 'Create Cards', 'description' => 'Can create cards', 'group' => 'cards'],
            ['name' => 'approve_cards', 'display_name' => 'Approve Cards', 'description' => 'Can approve card requests', 'group' => 'cards'],
            ['name' => 'block_cards', 'display_name' => 'Block Cards', 'description' => 'Can block cards', 'group' => 'cards'],
            ['name' => 'delete_cards', 'display_name' => 'Delete Cards', 'description' => 'Can delete cards', 'group' => 'cards'],

            // إدارة المعاملات
            ['name' => 'view_transactions', 'display_name' => 'View Transactions', 'description' => 'Can view transactions', 'group' => 'transactions'],
            ['name' => 'view_all_transactions', 'display_name' => 'View All Transactions', 'description' => 'Can view all users transactions', 'group' => 'transactions'],
            ['name' => 'cancel_transactions', 'display_name' => 'Cancel Transactions', 'description' => 'Can cancel transactions', 'group' => 'transactions'],
            ['name' => 'refund_transactions', 'display_name' => 'Refund Transactions', 'description' => 'Can refund transactions', 'group' => 'transactions'],

            // التحويلات والدفع
            ['name' => 'transfer_money', 'display_name' => 'Transfer Money', 'description' => 'Can transfer money to friends', 'group' => 'transfers'],
            ['name' => 'pay_merchant', 'display_name' => 'Pay Merchant', 'description' => 'Can pay merchants', 'group' => 'transfers'],
            ['name' => 'receive_money', 'display_name' => 'Receive Money', 'description' => 'Can receive money', 'group' => 'transfers'],

            // التقارير
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'description' => 'Can view reports', 'group' => 'reports'],
            ['name' => 'export_reports', 'display_name' => 'Export Reports', 'description' => 'Can export reports', 'group' => 'reports'],

            // إدارة الأدوار والصلاحيات
            ['name' => 'manage_roles', 'display_name' => 'Manage Roles', 'description' => 'Can manage roles', 'group' => 'roles'],
            ['name' => 'manage_permissions', 'display_name' => 'Manage Permissions', 'description' => 'Can manage permissions', 'group' => 'roles'],
            ['name' => 'assign_roles', 'display_name' => 'Assign Roles', 'description' => 'Can assign roles to users', 'group' => 'roles'],

            // الإعدادات
            ['name' => 'manage_settings', 'display_name' => 'Manage Settings', 'description' => 'Can manage system settings', 'group' => 'settings'],
            ['name' => 'view_logs', 'display_name' => 'View Logs', 'description' => 'Can view system logs', 'group' => 'settings'],
        
            ['name' => 'view_admin_dashboard', 'display_name' => 'View Admin Dashboard', 'description' => 'Can view admin dashboard', 'group' => 'admin'],
            ['name' => 'view_admin_analytics', 'display_name' => 'View Admin Analytics', 'description' => 'Can view admin analytics', 'group' => 'admin'],
            ['name' => 'view_admin_health', 'display_name' => 'View Admin Health', 'description' => 'Can view system health', 'group' => 'admin'],
];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // إنشاء الأدوار
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Admin',
                'description' => 'Has full access to everything',
            ]
        );

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Admin',
                'description' => 'Can manage users and system',
            ]
        );

        $managerRole = Role::firstOrCreate(
            ['name' => 'manager'],
            [
                'display_name' => 'Manager',
                'description' => 'Can manage transactions and view reports',
            ]
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            [
                'display_name' => 'User',
                'description' => 'Regular user with basic permissions',
            ]
        );

        // إعطاء صلاحيات للـ Super Admin (كل الصلاحيات)
        $superAdminRole->permissions()->sync(Permission::all());

        // إعطاء صلاحيات للـ Admin
        $adminPermissions = Permission::whereIn('name', [
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_cards', 'create_cards', 'approve_cards', 'block_cards',
            'view_all_transactions', 'cancel_transactions',
            'view_reports',
            'assign_roles',
        ])->get();
        $adminRole->permissions()->sync($adminPermissions);

        // إعطاء صلاحيات للـ Manager
        $managerPermissions = Permission::whereIn('name', [
            'view_users',
            'view_cards', 'approve_cards',
            'view_all_transactions', 'cancel_transactions', 'refund_transactions',
            'view_reports', 'export_reports',
        ])->get();
        $managerRole->permissions()->sync($managerPermissions);

        // إعطاء صلاحيات للـ User (المستخدم العادي)
        $userPermissions = Permission::whereIn('name', [
            'view_cards',
            'view_transactions',
            'transfer_money',
            'pay_merchant',
            'receive_money',
        ])->get();
        $userRole->permissions()->sync($userPermissions);

        // إنشاء مستخدم Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@bank.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'account_number' => 'SA' . str_pad(1, 10, '0', STR_PAD_LEFT),
                'balance' => 1000000,
                'is_active' => true,
            ]
        );
        $superAdmin->assignRole('super_admin');

        // إنشاء مستخدم Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@bank.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'account_number' => 'SA' . str_pad(2, 10, '0', STR_PAD_LEFT),
                'balance' => 500000,
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // إنشاء مستخدم Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@bank.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'account_number' => 'SA' . str_pad(3, 10, '0', STR_PAD_LEFT),
                'balance' => 100000,
                'is_active' => true,
            ]
        );
        $manager->assignRole('manager');

        $this->command->info('✅ Roles and permissions created successfully!');
        $this->command->info('');
        $this->command->info('📧 Login credentials:');
        $this->command->info('Super Admin: superadmin@bank.com / password');
        $this->command->info('Admin: admin@bank.com / password');
        $this->command->info('Manager: manager@bank.com / password');
    }
}
