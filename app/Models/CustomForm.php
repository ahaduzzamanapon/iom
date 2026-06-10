<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomForm extends Model
{
    protected $fillable = [
        'created_by', 'title', 'description', 'slug', 'is_active', 'closes_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'closes_at'  => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->slug ??= Str::slug($model->title) . '-' . Str::random(6);
        });
    }

    public function creator()   { return $this->belongsTo(User::class, 'created_by'); }
    public function fields()    { return $this->hasMany(FormField::class)->orderBy('order'); }
    public function responses() { return $this->hasMany(FormResponse::class); }
}
