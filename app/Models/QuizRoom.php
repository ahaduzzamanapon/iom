<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QuizRoom extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = [
        'created_by', 'batch_id', 'title', 'description',
        'room_code', 'status', 'starts_at', 'ends_at', 'duration_minutes',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->room_code ??= strtoupper(Str::random(8));
        });
    }

    public function creator()    { return $this->belongsTo(User::class, 'created_by'); }
    public function batch()      { return $this->belongsTo(Batch::class); }
    public function questions()  { return $this->hasMany(QuizQuestion::class); }
    public function attempts()   { return $this->hasMany(QuizAttempt::class); }
}
