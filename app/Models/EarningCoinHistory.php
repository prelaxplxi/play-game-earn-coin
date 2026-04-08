<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EarningCoinHistory extends Model
{
    protected $fillable = [
        'user_id',
        'earning_coins',
        'type',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
