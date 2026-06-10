<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassLesson;
use App\Models\ClassProgress;

class LearningController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $batchIds = $user->studentBatches()->where('status','active')->pluck('batch_id');

        $lessons = ClassLesson::whereIn('batch_id', $batchIds)
            ->where('is_published', true)
            ->with(['module.subject','batch'])
            ->orderBy('order')->paginate(20);

        $completed = ClassProgress::where('student_id', $user->id)
            ->where('is_completed', true)->pluck('class_lesson_id')->toArray();

        return view('student.learning.index', compact('lessons','completed'));
    }

    public function markComplete(ClassLesson $lesson)
    {
        $user = auth()->user();
        $batchIds = $user->studentBatches()->where('status','active')->pluck('batch_id')->toArray();

        abort_if(!in_array($lesson->batch_id, $batchIds), 403, 'You are not enrolled in the batch for this lesson.');

        ClassProgress::updateOrCreate(
            ['student_id' => $user->id, 'class_lesson_id' => $lesson->id],
            ['is_completed' => true, 'completed_at' => now()]
        );
        return response()->json(['ok' => true]);
    }
}
