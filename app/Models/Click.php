<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'click_id',
        'campaign_id',
        'source',
        'sub_source',
        'device_id',
        'app_user_id',
        'ip_address',
        'user_agent',
        'landing_url',
        'referrer',
        'meta_json',
        'created_at'
    ];

    protected $casts = [
        'meta_json' => 'array',
        'created_at' => 'datetime'
    ];

    public function events()
    {
        return $this->hasMany(TrackedEvent::class, 'click_id', 'click_id');
    }
}
