<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_room_id', 'question', 'option_a', 'option_b', 'option_c', 'option_d',
        'correct_option', 'marks', 'order',
    ];

    public function quizRoom() { return $this->belongsTo(QuizRoom::class); }
}
