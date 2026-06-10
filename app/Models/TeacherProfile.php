<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    protected $fillable = ['user_id', 'teacher_id', 'phone', 'qualification', 'specialization', 'photo', 'status'];

    public function user() { return $this->belongsTo(User::class); }
}
