<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackedEvent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'click_id',
        'click_db_id',
        'event_name',
        'event_time',
        'device_id',
        'app_user_id',
        'transaction_id',
        'revenue',
        'currency',
        'meta_json',
        'raw_payload',
        'created_at'
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'meta_json' => 'array',
        'raw_payload' => 'array',
        'created_at' => 'datetime',
        'revenue' => 'decimal:2'
    ];

    public function click()
    {
        return $this->belongsTo(Click::class, 'click_id', 'click_id');
    }
}
