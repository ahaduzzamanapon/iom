<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['course_id', 'name', 'name_bn', 'order', 'start_date', 'end_date', 'status'];

    public function course()  { return $this->belongsTo(Course::class); }
    public function batches() { return $this->hasMany(Batch::class); }
    public function exams()   { return $this->hasMany(Exam::class); }
    public function results() { return $this->hasMany(Result::class); }
}
