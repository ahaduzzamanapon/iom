<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = [
        'survey_id', 'user_id', 'answers', 'respondent_name', 'respondent_email',
    ];

    protected $casts = [
        'answers' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
