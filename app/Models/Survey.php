<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = [
        'created_by', 'title', 'description', 'slug', 'is_active', 'closes_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'closes_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
