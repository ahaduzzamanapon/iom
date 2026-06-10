<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notice;

class NoticeController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $batchIds = $user->studentBatches()->where('status', 'active')->pluck('batch_id');
        $courseIds = \App\Models\Batch::whereIn('id', $batchIds)->pluck('course_id');

        $notices = Notice::where(function ($q) use ($batchIds, $courseIds) {
            $q->where('scope', 'universal')
              ->orWhere(fn($q2) => $q2->where('scope', 'batch')->whereIn('batch_id', $batchIds))
              ->orWhere(fn($q3) => $q3->where('scope', 'course')->whereIn('course_id', $courseIds));
        })
        ->where('is_published', true)
        ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
        ->latest()->paginate(15);

        return view('student.notices.index', compact('notices'));
    }
}
