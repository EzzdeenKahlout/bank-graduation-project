<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // جدول الأدوار
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // super_admin, admin, manager, user
            $table->string('display_name'); // Super Admin, Admin, Manager, User
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // جدول الصلاحيات
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // manage_users, manage_cards, etc.
            $table->string('display_name'); // Manage Users, Manage Cards, etc.
            $table->string('description')->nullable();
            $table->string('group')->default('general'); // general, users, transactions, cards
            $table->timestamps();
        });

        // جدول ربط المستخدمين بالأدوار (many-to-many)
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'role_id']);
        });

        // جدول ربط الأدوار بالصلاحيات (many-to-many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['role_id', 'permission_id']);
        });

        // إضافة حقل role_id للمستخدمين (اختياري - للدور الافتراضي)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('default_role_id')->nullable()->constrained('roles')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['default_role_id']);
            $table->dropColumn('default_role_id');
        });
        
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
