<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id', 'student_id', 'started_at', 'submitted_at',
        'obtained_marks', 'percentage', 'gpa', 'grade', 'status', 'answer_script', 'feedback'
    ];
    protected $casts = ['started_at' => 'datetime', 'submitted_at' => 'datetime'];

    public function exam()    { return $this->belongsTo(Exam::class); }
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function answers() { return $this->hasMany(ExamAnswer::class); }
}
