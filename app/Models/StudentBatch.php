<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentBatch extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = ['user_id', 'batch_id', 'enrolled_at', 'status'];

    public function user()  { return $this->belongsTo(User::class); }
    public function batch() { return $this->belongsTo(Batch::class); }
}
