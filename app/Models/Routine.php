<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    protected $fillable = ['batch_id','subject_id','teacher_id','day','start_time','end_time','room','type'];

    public function batch()   { return $this->belongsTo(Batch::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
}
