<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = [
        'course_id', 'user_id', 'applicant_name', 'applicant_email', 'applicant_phone',
        'date_of_birth', 'gender', 'guardian_name', 'guardian_phone', 'address',
        'photo', 'status', 'reviewed_by', 'remarks', 'reviewed_at'
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function course()   { return $this->belongsTo(Course::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function documents() { return $this->hasMany(AdmissionDocument::class); }
}
