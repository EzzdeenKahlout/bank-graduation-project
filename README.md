# 💳 Laravel Digital Wallet / Banking System

نظام محفظة رقمية متكامل مبني على Laravel 12 مع نظام أدوار وصلاحيات مخصص.

<div dir="rtl">

## ✨ المميزات

### 🔐 نظام المصادقة والأمان
- تسجيل الدخول والتسجيل
- تشفير كلمات المرور باستخدام bcrypt
- نظام PIN للمعاملات (4 أرقام)
- حماية من CSRF
- نظام أدوار وصلاحيات مخصص (بدون Spatie)
- **أمان CVV**: لا يتم حفظ CVV في قاعدة البيانات (PCI DSS Compliant)

### 💰 المعاملات المالية
- **تحويل الأموال للأصدقاء**: تحويل فوري بين المستخدمين
- **الدفع للتجار**: الدفع للمحلات والخدمات
- **QR Code Payments**: الدفع السريع عبر QR
- **سجل المعاملات**: عرض كامل لجميع المعاملات
- **حد يومي للمصروفات**: حماية من الإنفاق الزائد

### 💳 إدارة البطاقات
- طلب بطاقات جديدة (Debit/Credit)
- تفعيل وإلغاء تفعيل البطاقات
- حظر وإلغاء حظر البطاقات
- عرض تفاصيل البطاقات

### 📊 لوحة التحكم
- عرض الرصيد الحالي
- نظرة سريعة على المعاملات
- إحصائيات البطاقات
- إشعارات فورية

### 🌐 دعم اللغات
- العربية (افتراضي)
- الإنجليزية
- سهولة التبديل بين اللغات

### 👥 نظام الأدوار والصلاحيات
- **Super Admin**: صلاحيات كاملة على النظام
- **Admin**: إدارة المستخدمين والبطاقات
- **Manager**: إدارة المعاملات والتقارير
- **User**: المستخدم العادي (افتراضي)

</div>

---

## 🚀 متطلبات التشغيل

- PHP 8.2 أو أحدث
- Composer
- SQLite (مضمن في المشروع)
- Laravel 12

---

## 📦 التثبيت

### 1. استنساخ المشروع
```bash
git clone <repository-url>
cd MY_APP
```

### 2. تثبيت Dependencies
```bash
composer install
npm install
```

### 3. إعداد البيئة
```bash
cp .env.example .env
php artisan key:generate
```

### 4. إنشاء قاعدة البيانات
```bash
# SQLite مضمنة في المشروع
touch database/database.sqlite
```

### 5. تشغيل Migrations والبيانات الأولية
```bash
php artisan migrate --seed
```

### 6. تشغيل المشروع
```bash
# تشغيل الـ Server
php artisan serve

# في نافذة terminal أخرى - تشغيل Vite
npm run dev
```

المشروع الآن يعمل على: http://localhost:8000

---

## 🔑 بيانات تسجيل الدخول

بعد تشغيل `php artisan migrate --seed`:

### Super Admin
- **Email**: superadmin@bank.com
- **Password**: password
- **الصلاحيات**: كل الصلاحيات

### Admin
- **Email**: admin@bank.com
- **Password**: password
- **الصلاحيات**: إدارة المستخدمين والبطاقات

### Manager
- **Email**: manager@bank.com
- **Password**: password
- **الصلاحيات**: المعاملات والتقارير

⚠️ **تحذير**: غير كلمات المرور في الإنتاج!

---

## 🏗️ هيكل المشروع

```
MY_APP/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # المصادقة
│   │   │   ├── DashboardController.php  # لوحة التحكم
│   │   │   ├── CardController.php       # إدارة البطاقات
│   │   │   ├── TransactionController.php # المعاملات
│   │   │   ├── RoleController.php       # إدارة الأدوار
│   │   │   └── UserRoleController.php   # تعيين الأدوار
│   │   └── Middleware/
│   │       ├── CheckRole.php            # التحقق من الأدوار
│   │       ├── CheckPermission.php      # التحقق من الصلاحيات
│   │       └── SetLocale.php            # تبديل اللغات
│   ├── Models/
│   │   ├── User.php                     # نموذج المستخدم
│   │   ├── Role.php                     # نموذج الدور
│   │   ├── Permission.php               # نموذج الصلاحية
│   │   ├── Card.php                     # نموذج البطاقة
│   │   ├── Transaction.php              # نموذج المعاملة
│   │   ├── Merchant.php                 # نموذج التاجر
│   │   └── Notification.php             # نموذج الإشعار
│   └── Traits/
│       └── HasRolesAndPermissions.php   # نظام الأدوار
├── database/
│   ├── migrations/                       # ملفات الـ Migrations
│   └── seeders/
│       ├── DatabaseSeeder.php           # البيانات الأولية
│       └── RolesAndPermissionsSeeder.php # الأدوار والصلاحيات
├── resources/
│   ├── views/                           # واجهات Blade
│   └── Lang/
│       ├── ar/messages.php              # اللغة العربية
│       └── en/messages.php              # اللغة الإنجليزية
└── routes/
    └── web.php                          # مسارات الويب
```

---

## 🛡️ نظام الأدوار والصلاحيات

### الأدوار المتاحة (Roles)

1. **super_admin** - صلاحيات كاملة
2. **admin** - إدارة المستخدمين والنظام
3. **manager** - إدارة المعاملات والتقارير
4. **user** - مستخدم عادي (افتراضي)

### الصلاحيات (Permissions)

#### إدارة المستخدمين
- `view_users` - عرض المستخدمين
- `create_users` - إنشاء مستخدمين
- `edit_users` - تعديل مستخدمين
- `delete_users` - حذف مستخدمين

#### إدارة البطاقات
- `view_cards` - عرض البطاقات
- `create_cards` - إنشاء بطاقات
- `approve_cards` - الموافقة على طلبات البطاقات
- `block_cards` - حظر البطاقات
- `delete_cards` - حذف البطاقات

#### إدارة المعاملات
- `view_transactions` - عرض معاملاتك
- `view_all_transactions` - عرض كل المعاملات
- `cancel_transactions` - إلغاء المعاملات
- `refund_transactions` - استرداد الأموال

#### التحويلات والدفع
- `transfer_money` - تحويل الأموال
- `pay_merchant` - الدفع للتجار
- `receive_money` - استلام الأموال

#### التقارير
- `view_reports` - عرض التقارير
- `export_reports` - تصدير التقارير

#### الإدارة
- `manage_roles` - إدارة الأدوار
- `manage_permissions` - إدارة الصلاحيات
- `assign_roles` - تعيين الأدوار
- `manage_settings` - إدارة الإعدادات
- `view_logs` - عرض السجلات

---

## 🔧 استخدام Middlewares

### في Routes

```php
// التحقق من دور واحد
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
});

// التحقق من عدة أدوار
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index']);
});

// التحقق من صلاحية
Route::middleware(['auth', 'permission:view_users'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});
```

### في Controllers

```php
public function __construct()
{
    $this->middleware('auth');
    $this->middleware('role:admin')->only(['destroy']);
    $this->middleware('permission:create_users')->only(['create', 'store']);
}
```

---

## 💻 أمثلة الاستخدام

### التحقق من الأدوار والصلاحيات

```php
// في Blade
@role('admin')
    <p>هذا المحتوى للـ Admin فقط</p>
@endrole

@permission('view_users')
    <a href="{{ route('users.index') }}">عرض المستخدمين</a>
@endpermission

// في Controller
if ($user->hasRole('admin')) {
    // الكود هنا
}

if ($user->hasPermission('view_users')) {
    // الكود هنا
}

// تعيين دور
$user->assignRole('admin');

// إزالة دور
$user->removeRole('admin');

// تحديث أدوار المستخدم
$user->syncRoles(['admin', 'manager']);

// إعطاء صلاحية
$role->givePermissionTo('view_users');
```

---

## 🌍 تغيير اللغة

```php
// في Controller
session(['locale' => 'ar']);
app()->setLocale('ar');

// في Blade
<a href="{{ route('language.switch', 'ar') }}">العربية</a>
<a href="{{ route('language.switch', 'en') }}">English</a>
```

---

## 📋 قاعدة البيانات

### الجداول الرئيسية

- **users** - بيانات المستخدمين
- **roles** - الأدوار
- **permissions** - الصلاحيات
- **role_user** - علاقة المستخدمين بالأدوار
- **permission_role** - علاقة الأدوار بالصلاحيات
- **cards** - البطاقات
- **transactions** - المعاملات
- **merchants** - التجار
- **notifications** - الإشعارات
- **card_requests** - طلبات البطاقات

---

## ⚠️ ملاحظات أمنية

1. ✅ **CVV**: لا يتم حفظ CVV في قاعدة البيانات (PCI DSS Compliant)
2. ✅ **PIN Codes**: مشفرة باستخدام bcrypt
3. ✅ **Passwords**: مشفرة باستخدام bcrypt
4. ✅ **CSRF Protection**: مفعلة على جميع النماذج
5. ✅ **Daily Limits**: حماية من الإنفاق الزائد
6. ⚠️ **في Production**: غير جميع كلمات المرور والأسرار

---

## 🐛 التصحيح (Debugging)

```bash
# عرض Logs
tail -f storage/logs/laravel.log

# تفعيل Debug Mode (في .env)
APP_DEBUG=true

# مسح Cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 📚 الملفات الإضافية

- **FIXES_DOCUMENTATION.md** - ملخص جميع الإصلاحات التي تمت
- **.env.example** - مثال على ملف البيئة

---

## 🤝 المساهمة

إذا وجدت أي مشكلة أو لديك اقتراح:
1. افتح Issue
2. اقترح تحسين
3. أرسل Pull Request

---

## 📄 الترخيص

هذا المشروع مفتوح المصدر تحت ترخيص MIT.

---

## 📞 التواصل

إذا كان لديك أي استفسار، لا تتردد في التواصل!

---

**تم التحديث**: 12 أكتوبر 2025

🚀 **Laravel 12** | 💳 **Banking System** | 🔐 **Secure** | 🌍 **Multi-Language**
