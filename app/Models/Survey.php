<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'title',
        'description',
        'total_reward_coins',
        'is_active',
        'store_answers',
    ];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public function responses()
    {
        return $this->hasMany(UserSurveyResponse::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
