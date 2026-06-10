<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = UserNotification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        // mark all as read
        UserNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(int $id)
    {
        UserNotification::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function readAll()
    {
        UserNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'সব notification পড়া হয়েছে।');
    }

    /**
     * Returns unread count — used by layouts via AJAX or view injection.
     */
    public static function unreadCount(): int
    {
        return auth()->check()
            ? UserNotification::where('user_id', auth()->id())->unread()->count()
            : 0;
    }
}
