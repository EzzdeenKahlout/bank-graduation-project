<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'sender_id', 'receiver_id', 'transaction_type', 'amount',
        'description', 'merchant_name', 'reference_number', 
        'status', 'payment_method', 'card_id', ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Alias for sender (used in some views)
    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Card relationship
    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public static function generateReference()
    {
        return 'TXN' . time() . rand(1000, 9999);
    }

    public function getTypeAttribute()
    {
        return $this->attributes['transaction_type'] ?? null;
    }
}
