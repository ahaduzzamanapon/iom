<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'category', 'image', 'type', 'status', 'admin_reply', 'replied_by'];

    public function user()    { return $this->belongsTo(User::class); }
    public function replier() { return $this->belongsTo(User::class, 'replied_by'); }
}
