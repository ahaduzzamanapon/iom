<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = ['course_id', 'name', 'name_bn', 'code', 'credit_hours', 'description', 'status'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_subjects')->withPivot('teacher_id')->withTimestamps();
    }
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
