<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = ['student_id', 'semester_id', 'batch_id', 'cgpa', 'overall_grade', 'is_published', 'published_at'];
    protected $casts    = ['is_published' => 'boolean', 'published_at' => 'datetime'];

    public function student()  { return $this->belongsTo(User::class, 'student_id'); }
    public function semester() { return $this->belongsTo(Semester::class); }
    public function batch()    { return $this->belongsTo(Batch::class); }
}
