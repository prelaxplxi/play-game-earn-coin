<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    protected $fillable = [
        'user_id',
        'withdraw_coins',
        'amount',
        'payment_type',
        'payment_details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
