<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = ['created_by', 'title', 'body', 'scope', 'batch_id', 'course_id', 'is_published', 'published_at'];
    protected $casts    = ['is_published' => 'boolean', 'published_at' => 'datetime'];

    public function author()  { return $this->belongsTo(User::class, 'created_by'); }
    public function batch()   { return $this->belongsTo(Batch::class); }
    public function course()  { return $this->belongsTo(Course::class); }
}
