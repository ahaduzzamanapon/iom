<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassLesson;
use App\Models\StudentBatch;
use App\Models\Batch;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    private function myBatchIds(): array
    {
        return Batch::whereHas('subjects', fn($q) =>
            $q->where('batch_subjects.teacher_id', auth()->id())
        )->pluck('id')->toArray();
    }

    public function index(int $batch)
    {
        $myBatchIds = $this->myBatchIds();
        abort_if(!in_array($batch, $myBatchIds), 403, 'You are not assigned to this batch.');

        $lessons  = ClassLesson::where('batch_id', $batch)->with('module.subject')->get();
        $students = StudentBatch::where('batch_id', $batch)->where('status','active')->with('user')->get();
        return view('teacher.attendance.index', compact('lessons','students','batch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_lesson_id' => 'required|exists:class_lessons,id',
            'batch_id'        => 'required|exists:batches,id',
            'date'            => 'required|date',
            'attendance'      => 'required|array',
            'attendance.*'    => 'in:present,absent,late',
        ]);

        $myBatchIds = $this->myBatchIds();
        abort_if(!in_array($request->batch_id, $myBatchIds), 403, 'You are not assigned to this batch.');

        $lesson = ClassLesson::findOrFail($request->class_lesson_id);
        abort_if($lesson->batch_id !== (int)$request->batch_id, 400, 'Invalid class lesson batch.');

        foreach ($request->attendance as $studentId => $status) {
            $isEnrolled = StudentBatch::where('user_id', $studentId)->where('batch_id', $request->batch_id)->exists();
            if (!$isEnrolled) continue;

            Attendance::updateOrCreate(
                ['class_lesson_id' => $request->class_lesson_id, 'student_id' => $studentId],
                ['batch_id' => $request->batch_id, 'date' => $request->date, 'status' => $status, 'marked_by' => auth()->id()]
            );
        }
        return back()->with('success', 'Attendance save হয়েছে।');
    }
}
