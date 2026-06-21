<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = ['course_id', 'semester_id', 'name', 'name_bn', 'capacity', 'start_date', 'end_date', 'status'];

    public function course()      { return $this->belongsTo(Course::class); }
    public function semester()    { return $this->belongsTo(Semester::class); }
    public function subjects()    { return $this->belongsToMany(Subject::class, 'batch_subjects')->withPivot('teacher_id')->withTimestamps(); }
    public function students()    { return $this->hasMany(StudentBatch::class); }
    public function classLessons() { return $this->hasMany(ClassLesson::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function exams()       { return $this->hasMany(Exam::class); }

    public function enrolledCount(): int
    {
        return $this->students()->where('status', 'active')->count();
    }
}
