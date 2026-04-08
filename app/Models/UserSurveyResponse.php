<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSurveyResponse extends Model
{
    protected $fillable = [
        'user_id',
        'survey_id',
        'reward_coins',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers()
    {
        return $this->hasMany(UserSurveyAnswer::class, 'response_id');
    }
}
