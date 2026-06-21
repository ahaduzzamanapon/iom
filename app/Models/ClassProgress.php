<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassProgress extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = ['student_id', 'class_lesson_id', 'is_completed', 'completed_at'];
    protected $casts    = ['is_completed' => 'boolean', 'completed_at' => 'datetime'];

    public function student()     { return $this->belongsTo(User::class, 'student_id'); }
    public function classLesson() { return $this->belongsTo(ClassLesson::class); }
}
