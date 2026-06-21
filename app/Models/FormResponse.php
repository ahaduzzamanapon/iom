<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = [
        'custom_form_id', 'user_id', 'answers', 'respondent_name', 'respondent_email',
    ];

    protected $casts = ['answers' => 'array'];

    public function form() { return $this->belongsTo(CustomForm::class, 'custom_form_id'); }
    public function user() { return $this->belongsTo(User::class); }
}
