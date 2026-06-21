<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = ['course_id', 'title', 'type', 'amount', 'description', 'status'];
    public function course()   { return $this->belongsTo(Course::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
