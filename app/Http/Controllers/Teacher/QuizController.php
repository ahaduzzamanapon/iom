<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\QuizRoom;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * List quizzes created by this teacher. (QZ-01)
     */
    public function index()
    {
        $quizzes = QuizRoom::with(['batch'])
            ->withCount('attempts')
            ->where('created_by', auth()->id())
            ->latest()
            ->paginate(15);

        return view('teacher.quiz.index', compact('quizzes'));
    }

    public function create()
    {
        $batches = Batch::where('status', 'active')->with('course')->get();
        return view('teacher.quiz.form', compact('batches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'batch_id'         => 'nullable|exists:batches,id',
            'duration_minutes' => 'required|integer|min:1',
            'starts_at'        => 'nullable|date',
            'ends_at'          => 'nullable|date|after_or_equal:starts_at',
        ]);

        $data['created_by'] = auth()->id();

        // Restrict: if batch_id provided, it must be teacher's own batch
        if (!empty($data['batch_id'])) {
            $myBatchIds = \App\Models\Batch::whereHas('subjects', fn($q) =>
                $q->where('batch_subjects.teacher_id', auth()->id())
            )->pluck('id')->toArray();
            abort_if(!in_array($data['batch_id'], $myBatchIds), 403, 'You are not assigned to this batch.');
        }

        $quiz = QuizRoom::create($data);

        return redirect()->route('teacher.quiz.show', $quiz)
            ->with('success', 'Quiz room তৈরি হয়েছে। এখন questions যোগ করুন।');
    }

    public function show(QuizRoom $quiz)
    {
        // Only the creator can manage
        if ($quiz->created_by !== auth()->id()) {
            abort(403);
        }
        $quiz->load(['questions', 'attempts.user']);
        return view('teacher.quiz.show', compact('quiz'));
    }

    public function edit(QuizRoom $quiz)
    {
        // Only the creator can edit
        if ($quiz->created_by !== auth()->id()) {
            abort(403);
        }

        $batches = Batch::where('status', 'active')->with('course')->get();
        return view('teacher.quiz.edit', compact('quiz', 'batches'));
    }

    public function update(Request $request, QuizRoom $quiz)
    {
        if ($quiz->created_by !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'batch_id'         => 'nullable|exists:batches,id',
            'duration_minutes' => 'required|integer|min:1',
            'starts_at'        => 'nullable|date',
            'ends_at'          => 'nullable|date|after_or_equal:starts_at',
            'status'           => 'required|in:draft,active,closed',
        ]);

        // Restrict: if batch_id provided, it must be teacher's own batch
        if (!empty($data['batch_id'])) {
            $myBatchIds = \App\Models\Batch::whereHas('subjects', fn($q) =>
                $q->where('batch_subjects.teacher_id', auth()->id())
            )->pluck('id')->toArray();
            abort_if(!in_array($data['batch_id'], $myBatchIds), 403, 'You are not assigned to this batch.');
        }

        $quiz->update($data);

        return redirect()->route('teacher.quiz.index')
            ->with('success', 'Quiz room সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy(QuizRoom $quiz)
    {
        if ($quiz->created_by !== auth()->id()) {
            abort(403);
        }
        if ($quiz->attempts()->count() > 0) {
            return back()->with('error', 'এই Quizে ' . $quiz->attempts()->count() . 'জন ছাত্র অংশ নিয়েছেন, মুছে ফেলা যাবে না।');
        }
        $quiz->delete();
        return redirect()->route('teacher.quiz.index')
            ->with('success', 'Quiz room মুছে ফেলা হয়েছে।');
    }

    // ── Question Management ──────────────────────────────────────────
    public function storeQuestion(Request $request, QuizRoom $quiz)
    {
        if ($quiz->created_by !== auth()->id()) abort(403);

        // Prevent modifying questions after students have started attempting
        if ($quiz->attempts()->count() > 0) {
            return back()->with('error', 'ছাত্ররা ইতিমধ্যে Quiz শুরু করেছে, এখন প্রশ্ন যোগ বা বাদ দেওয়া যাবে না।');
        }

        $data = $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'nullable|string',
            'option_d'       => 'nullable|string',
            'correct_option' => 'required|in:a,b,c,d',
            'marks'          => 'required|integer|min:1',
        ]);

        $data['quiz_room_id'] = $quiz->id;
        $data['order']        = $quiz->questions()->count() + 1;
        $quiz->questions()->create($data);

        return back()->with('success', 'Question যোগ হয়েছে।');
    }

    public function destroyQuestion(QuizRoom $quiz, int $questionId)
    {
        if ($quiz->created_by !== auth()->id()) abort(403);
        
        // Prevent modifying questions after students have started attempting
        if ($quiz->attempts()->count() > 0) {
            return back()->with('error', 'ছাত্ররা ইতিমধ্যে Quiz শুরু করেছে, এখন প্রশ্ন বাদ দেওয়া যাবে না।');
        }

        $quiz->questions()->findOrFail($questionId)->delete();
        return back()->with('success', 'Question মুছে ফেলা হয়েছে।');
    }
}