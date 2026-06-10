<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = [
        'survey_id', 'question_key', 'label', 'type', 'options', 'default_next_question_key', 'is_start', 'required', 'order',
    ];

    protected $casts = [
        'options'   => 'array',
        'is_start'  => 'boolean',
        'required'  => 'boolean',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
