<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRPayment extends Model
{
    protected $table = 'qr_payments';
    
    protected $fillable = [
        'qr_code', 'merchant_id', 'user_id', 'amount',
        'description', 'is_used', 'expires_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateQRCode()
    {
        return 'QRP' . strtoupper(uniqid()) . rand(1000, 9999);
    }

    public function isValid()
    {
        return !$this->is_used && $this->expires_at > now();
    }
}