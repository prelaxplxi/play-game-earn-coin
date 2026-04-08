<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSurveyAnswer extends Model
{
    protected $fillable = [
        'response_id',
        'question_id',
        'option_id',
        'is_correct',
    ];

    public function response()
    {
        return $this->belongsTo(UserSurveyResponse::class, 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    public function option()
    {
        return $this->belongsTo(SurveyQuestionOption::class, 'option_id');
    }
}
