<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassLesson;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Notice;
use App\Models\Exam;
use App\Models\Result;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $profile = $user->studentProfile;
        $batches = $user->studentBatches()->where('status','active')->with('batch.course')->get();
        $batchIds = $batches->pluck('batch_id');

        $upcomingClasses = ClassLesson::whereIn('batch_id', $batchIds)
            ->where('type','live')
            ->where('scheduled_at','>=',now())
            ->orderBy('scheduled_at')->take(5)->get();

        $recentPayments = Payment::where('student_id', $user->id)->latest()->take(3)->get();
        $duePayments    = Payment::where('student_id', $user->id)->whereIn('status',['pending','partial'])->count();

        $notices = Notice::where(function($q) use ($batchIds) {
            $q->where('scope','universal')
              ->orWhere(fn($q2) => $q2->where('scope','batch')->whereIn('batch_id', $batchIds));
        })->where('is_published', true)->latest()->take(5)->get();

        $attendancePercent = $this->calcAttendance($user->id, $batchIds);
        $latestResult = Result::where('student_id', $user->id)->where('is_published', true)->latest()->first();
        $minAttendance = (int) \App\Models\SystemSetting::get('min_attendance', 75);

        return view('student.dashboard', compact(
            'profile','batches','upcomingClasses','recentPayments',
            'duePayments','notices','attendancePercent','latestResult','minAttendance'
        ));
    }

    private function calcAttendance($userId, $batchIds): int
    {
        $total   = Attendance::where('student_id', $userId)->whereIn('batch_id', $batchIds)->count();
        $present = Attendance::where('student_id', $userId)->whereIn('batch_id', $batchIds)
                             ->whereIn('status',['present','late'])->count();
        return $total > 0 ? (int) round(($present / $total) * 100) : 0;
    }
}
