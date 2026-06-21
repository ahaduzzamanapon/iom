<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Readmission extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = [
        'user_id', 'course_id', 'batch_id', 'reason',
        'status', 'reviewed_by', 'reviewed_at', 'remarks',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function user()     { return $this->belongsTo(User::class); }
    public function course()   { return $this->belongsTo(Course::class); }
    public function batch()    { return $this->belongsTo(Batch::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
