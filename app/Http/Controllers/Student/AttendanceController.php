<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $batchIds = $user->studentBatches()->where('status','active')->pluck('batch_id');
        $records  = Attendance::where('student_id', $user->id)
            ->whereIn('batch_id', $batchIds)
            ->with(['classLesson','batch'])
            ->latest()->paginate(20);
        $total   = Attendance::where('student_id', $user->id)->whereIn('batch_id', $batchIds)->count();
        $present = Attendance::where('student_id', $user->id)->whereIn('batch_id', $batchIds)->whereIn('status',['present','late'])->count();
        $percent = $total > 0 ? round(($present/$total)*100) : 0;
        return view('student.attendance.index', compact('records','percent','total','present'));
    }
}
