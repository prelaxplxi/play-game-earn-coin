<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestionOption extends Model
{
    protected $fillable = [
        'question_id',
        'option_text',
    ];

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}