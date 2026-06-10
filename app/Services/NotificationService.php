<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send a generic email notification.
     */
    public function sendEmail(User $user, string $subject, string $body): void
    {
        try {
            Mail::send([], [], function ($mail) use ($user, $subject, $body) {
                $mail->to($user->email, $user->name)
                     ->subject($subject)
                     ->html($body);
            });
        } catch (\Throwable $e) {
            \Log::error("NotificationService email failed for user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Notify student of live class reminder.
     */
    public function liveClassReminder(User $user, array $classInfo): void
    {
        $subject = "📡 Live Class Reminder — {$classInfo['title']}";
        $body    = "
            <p>প্রিয় {$user->name},</p>
            <p>আপনার <strong>{$classInfo['title']}</strong> live class শুরু হবে:</p>
            <p><strong>সময়:</strong> {$classInfo['scheduled_at']}</p>
            <p><strong>Join Link:</strong> <a href='{$classInfo['meet_link']}'>{$classInfo['meet_link']}</a></p>
            <br>
            <p>IOM Academic Team</p>
        ";
        $this->sendEmail($user, $subject, $body);
    }

    /**
     * Notify student of low attendance warning.
     */
    public function lowAttendanceAlert(User $user, float $percentage): void
    {
        $subject = '⚠️ Low Attendance Alert — IOM';
        $body    = "
            <p>প্রিয় {$user->name},</p>
            <p>আপনার বর্তমান attendance: <strong>{$percentage}%</strong></p>
            <p>Minimum প্রয়োজন: <strong>75%</strong></p>
            <p>যদি attendance না বাড়ানো হয়, আপনি exam-এ অংশ নিতে পারবেন না।</p>
            <br>
            <p>IOM Academic Team</p>
        ";
        $this->sendEmail($user, $subject, $body);
    }

    /**
     * Notify student of exam result publication.
     */
    public function resultPublished(User $user, string $examTitle): void
    {
        $subject = "📊 Result Published — {$examTitle}";
        $body    = "
            <p>প্রিয় {$user->name},</p>
            <p><strong>{$examTitle}</strong> এর result প্রকাশিত হয়েছে।</p>
            <p>আপনার dashboard-এ login করে result দেখুন।</p>
            <br>
            <p>IOM Academic Team</p>
        ";
        $this->sendEmail($user, $subject, $body);
    }

    /**
     * Notify student of payment due reminder.
     */
    public function paymentDueReminder(User $user, float $dueAmount): void
    {
        $subject = '💳 Payment Due Reminder — IOM';
        $body    = "
            <p>প্রিয় {$user->name},</p>
            <p>আপনার বর্তমান বকেয়া: <strong>৳ {$dueAmount}</strong></p>
            <p>অনুগ্রহ করে যত তাড়াতাড়ি সম্ভব পেমেন্ট করুন।</p>
            <br>
            <p>IOM Finance Team</p>
        ";
        $this->sendEmail($user, $subject, $body);
    }

    /**
     * Notify a set of users with a custom notice.
     */
    public function broadcastNotice(iterable $users, string $noticeTitle, string $noticeBody): void
    {
        foreach ($users as $user) {
            $this->sendEmail($user, "📢 নোটিশ: {$noticeTitle}", $noticeBody);
        }
    }
}
