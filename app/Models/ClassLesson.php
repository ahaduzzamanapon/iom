<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassLesson extends Model
{
    protected $fillable = [
        'module_id', 'batch_id', 'title', 'title_bn', 'type',
        'youtube_url', 'file_path', 'meet_link', 'zoom_link',
        'meeting_provider', 'zoom_meeting_id', 'zoom_start_url',
        'scheduled_at', 'order', 'is_published', 'publish_at',
        'created_by', 'duration_mins', 'description', 'video_url', 'meeting_link',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'scheduled_at' => 'datetime',
        'publish_at'   => 'datetime',
    ];

    public function module()      { return $this->belongsTo(Module::class); }
    public function batch()       { return $this->belongsTo(Batch::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function progress()    { return $this->hasMany(ClassProgress::class); }
}
