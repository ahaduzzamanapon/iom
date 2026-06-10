<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['class_lesson_id', 'student_id', 'batch_id', 'date', 'status', 'marked_by'];
    protected $casts    = ['date' => 'date'];

    public function classLesson() { return $this->belongsTo(ClassLesson::class); }
    public function student()     { return $this->belongsTo(User::class, 'student_id'); }
    public function batch()       { return $this->belongsTo(Batch::class); }
    public function markedBy()    { return $this->belongsTo(User::class, 'marked_by'); }
}
