<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSemesterPromotion extends Model
{
    protected $fillable = [
        'user_id','from_semester_id','to_semester_id','promoted_by','status','remarks','promoted_at'
    ];
    protected $casts = ['promoted_at' => 'datetime'];

    public function student()      { return $this->belongsTo(User::class, 'user_id'); }
    public function fromSemester() { return $this->belongsTo(Semester::class, 'from_semester_id'); }
    public function toSemester()   { return $this->belongsTo(Semester::class, 'to_semester_id'); }
    public function promotedBy()   { return $this->belongsTo(User::class, 'promoted_by'); }
}
