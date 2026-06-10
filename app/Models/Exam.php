<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'batch_id', 'subject_id', 'semester_id', 'title', 'type',
        'start_at', 'end_at', 'duration_minutes', 'total_marks', 'pass_marks',
        'status', 'approved_by', 'approved_at'
    ];
    protected $casts = ['start_at' => 'datetime', 'end_at' => 'datetime', 'approved_at' => 'datetime'];

    public function batch()    { return $this->belongsTo(Batch::class); }
    public function subject()  { return $this->belongsTo(Subject::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
    public function questions() { return $this->hasMany(Question::class); }
    public function attempts()  { return $this->hasMany(ExamAttempt::class); }
    public function approver()  { return $this->belongsTo(User::class, 'approved_by'); }
}
