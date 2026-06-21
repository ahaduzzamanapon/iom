<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = ['subject_id', 'name', 'name_bn', 'order', 'description'];

    public function subject()      { return $this->belongsTo(Subject::class); }
    public function classLessons() { return $this->hasMany(ClassLesson::class)->orderBy('order'); }
}
