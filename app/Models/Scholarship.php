<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $fillable = [
        'student_id','title','discount_amount','discount_percent','reason','status','approved_by','approved_at'
    ];
    protected $casts = ['approved_at' => 'datetime'];

    public function student()  { return $this->belongsTo(User::class, 'student_id'); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}
