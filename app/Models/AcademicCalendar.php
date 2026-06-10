<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    protected $fillable = [
        'course_id', 'semester_id', 'title', 'type',
        'start_date', 'end_date', 'description', 'is_published', 'created_by',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'is_published' => 'boolean',
    ];

    public function course()   { return $this->belongsTo(Course::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
    public function creator()  { return $this->belongsTo(User::class, 'created_by'); }

    public function scopePublished($q) { return $q->where('is_published', true); }

    public function getDurationAttribute(): string
    {
        if (!$this->end_date || $this->start_date->eq($this->end_date)) {
            return $this->start_date->format('d M Y');
        }
        return $this->start_date->format('d M') . ' – ' . $this->end_date->format('d M Y');
    }

    public static function typeColors(): array
    {
        return [
            'holiday'          => 'badge-red',
            'exam'             => 'badge-blue',
            'event'            => 'badge-green',
            'class_suspension' => 'badge-yellow',
            'other'            => 'badge-gray',
        ];
    }
}
