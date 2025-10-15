<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'user_id', 'card_number', 'card_holder_name', 'card_type',
        'cvv', 'expiry_date', 'credit_limit', 'is_active', 
        'is_blocked', 'status'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'credit_limit' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateCardNumber()
    {
        return '5' . rand(100000000000000, 999999999999999);
    }

    public function isExpired()
    {
        return $this->expiry_date < now();
    }

    public function canUse()
    {
        return $this->is_active && !$this->is_blocked && !$this->isExpired();
}

    
    public function transactions()
    {
        // Transactions sent by the card owner
        return $this->hasMany(\App\Models\Transaction::class, 'sender_id', 'user_id');
    }

    public function receivedTransactions()
    {
        // Transactions received by the card owner
        return $this->hasMany(\App\Models\Transaction::class, 'receiver_id', 'user_id');
    }
}
