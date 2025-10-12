<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merchant;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // أولاً: إنشاء الأدوار والصلاحيات والمستخدمين
        $this->call(RolesAndPermissionsSeeder::class);

        // ثانياً: إنشاء التجار
        $merchants = [
            ['name' => 'Super-Pharm', 'type' => 'صيدلية', 'email' => 'super@example.com', 'phone' => '972501234567'],
            ['name' => 'Shufersal', 'type' => 'سوبر ماركت', 'email' => 'shufersal@example.com', 'phone' => '972501234568'],
            ['name' => 'Pizza Hut', 'type' => 'مطعم', 'email' => 'pizza@example.com', 'phone' => '972501234569'],
            ['name' => 'Rami Levy', 'type' => 'تسوق', 'email' => 'rami@example.com', 'phone' => '972501234570'],
            ['name' => 'Castro', 'type' => 'أزياء', 'email' => 'castro@example.com', 'phone' => '972501234571'],
            ['name' => 'Zara', 'type' => 'ملابس', 'email' => 'zara@example.com', 'phone' => '972501234572'],
            ['name' => 'Netflix', 'type' => 'بث', 'email' => 'netflix@example.com', 'phone' => '972501234573'],
        ];

        foreach ($merchants as $merchant) {
            Merchant::create([
                'name' => $merchant['name'],
                'business_type' => $merchant['type'],
                'email' => $merchant['email'],
                'phone' => $merchant['phone'],
                'merchant_id' => Merchant::generateMerchantId(),
                'is_verified' => true,
            ]);
        }
    }
}
