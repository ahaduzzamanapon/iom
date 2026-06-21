<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = ['quiz_room_id', 'user_id', 'score', 'total', 'submitted_at'];

    protected $casts = ['submitted_at' => 'datetime'];

    public function quizRoom() { return $this->belongsTo(QuizRoom::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function answers()  { return $this->hasMany(QuizAttemptAnswer::class); }
}
