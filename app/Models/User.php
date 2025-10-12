<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use App\Traits\HasRolesAndPermissions;

class User extends Authenticatable
{
    use Notifiable , HasRolesAndPermissions;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'account_number', 
        'balance', 'pin_code', 'daily_limit', 'daily_spent',
        'daily_reset_date', 'qr_code', 'is_active', 
        'notifications_enabled', 'preferred_language'
    ];

    protected $hidden = ['password', 'remember_token', 'pin_code'];

    protected $casts = [
        'balance' => 'decimal:2',
        'daily_limit' => 'decimal:2',
        'daily_spent' => 'decimal:2',
        'daily_reset_date' => 'date',
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'notifications_enabled' => 'boolean',
    ];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'sender_id');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'receiver_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public static function generateAccountNumber()
    {
        return 'ACC' . rand(1000000000, 9999999999);
    }

    public static function generateQRCode()
    {
        return 'QR' . strtoupper(uniqid());
    }

    public function verifyPin($pin)
    {
        return Hash::check($pin, $this->pin_code);
    }

    public function resetDailyLimit()
    {
        if ($this->daily_reset_date != today()) {
            $this->update([
                'daily_spent' => 0,
                'daily_reset_date' => today()
            ]);
        }
    }

    public function canSpend($amount)
    {
        $this->resetDailyLimit();
        return ($this->daily_spent + $amount) <= $this->daily_limit;
    }
}
