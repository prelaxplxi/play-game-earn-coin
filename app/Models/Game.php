<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'category',
        'url',
        'thumbnail',
        'description',
        'coins',
        'time_duration',
        'is_active',
    ];

    public function gameCategory()
    {
        return $this->belongsTo(GameCategory::class, 'category_id');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
