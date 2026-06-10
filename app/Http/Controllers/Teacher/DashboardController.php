<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\ClassLesson;
use App\Models\Exam;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $teacherId = $user->id;

        $myBatches  = Batch::whereHas('subjects', fn($q) =>
            $q->where('batch_subjects.teacher_id', $teacherId)
        )->with('course')->get();

        $myClasses  = ClassLesson::whereIn('batch_id', $myBatches->pluck('id'))
            ->where('type','live')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->take(5)->get();

        $myExams = Exam::where('status','published')
            ->whereIn('batch_id', $myBatches->pluck('id'))
            ->take(5)->get();

        return view('teacher.dashboard', compact('myBatches','myClasses','myExams'));
    }
}
