<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = [
        'name', 'business_type', 'email', 'phone', 
        'merchant_id', 'qr_code', 'is_verified'
    ];

    public static function generateMerchantId()
    {
        return 'MER' . rand(100000, 999999);
    }
}