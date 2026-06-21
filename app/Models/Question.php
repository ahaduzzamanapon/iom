<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = [
        'exam_id', 'subject_id', 'created_by', 'question_text',
        'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer', 'marks', 'status'
    ];

    public function exam()    { return $this->belongsTo(Exam::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function answers() { return $this->hasMany(ExamAnswer::class); }
}
