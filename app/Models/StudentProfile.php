<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id', 'student_id', 'phone', 'date_of_birth', 'gender',
        'address', 'photo', 'guardian_name', 'guardian_phone', 'guardian_relation', 'status'
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function batches() { return $this->hasMany(StudentBatch::class, 'user_id', 'user_id'); }
}
