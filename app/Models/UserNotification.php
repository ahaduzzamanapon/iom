<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use \App\Traits\Trackable;
    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'action_url', 'is_read', 'read_at',
    ];

    protected $casts = [
        'is_read'  => 'boolean',
        'read_at'  => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function scopeUnread($q) { return $q->where('is_read', false); }

    /**
     * Create a notification for a user quickly.
     */
    public static function notify(int $userId, string $type, string $title, string $message, ?string $url = null): self
    {
        return self::create([
            'user_id'    => $userId,
            'type'       => $type,
            'title'      => $title,
            'message'    => $message,
            'action_url' => $url,
        ]);
    }
}
