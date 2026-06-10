<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['student_id', 'course_id', 'certificate_number', 'qr_code', 'file_path', 'issued_date'];
    protected $casts    = ['issued_date' => 'date'];

    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function course()  { return $this->belongsTo(Course::class); }
}
