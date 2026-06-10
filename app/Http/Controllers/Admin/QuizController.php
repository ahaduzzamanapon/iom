<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\QuizAttempt;
use App\Models\QuizRoom;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = QuizRoom::with(['creator', 'batch'])->withCount('attempts')->latest()->paginate(15);
        return view('admin.quiz.index', compact('quizzes'));
    }

    public function create()
    {
        $batches = Batch::where('status', 'active')->with('course')->get();
        return view('admin.quiz.form', compact('batches'));
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
        $quiz = QuizRoom::create($data);

        return redirect()->route('admin.quiz.show', $quiz)->with('success', 'Quiz room তৈরি হয়েছে।');
    }

    public function show(QuizRoom $quiz)
    {
        $quiz->load(['questions', 'attempts.user.studentProfile']);
        return view('admin.quiz.show', compact('quiz'));
    }

    public function edit(QuizRoom $quiz)
    {
        $batches = Batch::where('status', 'active')->with('course')->get();
        return view('admin.quiz.form', compact('quiz', 'batches'));
    }

    public function update(Request $request, QuizRoom $quiz)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'batch_id'         => 'nullable|exists:batches,id',
            'duration_minutes' => 'required|integer|min:1',
            'starts_at'        => 'nullable|date',
            'ends_at'          => 'nullable|date|after_or_equal:starts_at',
            'status'           => 'required|in:draft,active,closed',
        ]);

        $quiz->update($data);
        return redirect()->route('admin.quiz.show', $quiz)->with('success', 'Quiz room আপডেট হয়েছে।');
    }

    public function destroy(QuizRoom $quiz)
    {
        if ($quiz->attempts()->count() > 0) {
            return back()->with('error', 'এই Quizে ' . $quiz->attempts()->count() . 'জন ছাত্র অংশ নিয়েছেন, মুছে ফেলা যাবে না।');
        }
        $quiz->delete();
        return redirect()->route('admin.quiz.index')->with('success', 'Quiz room মুছে ফেলা হয়েছে।');
    }

    // ── Question management ───────────────────────────────────────────
    public function storeQuestion(Request $request, QuizRoom $quiz)
    {
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
        $data['order'] = $quiz->questions()->count() + 1;

        $quiz->questions()->create($data);
        return back()->with('success', 'Question যোগ করা হয়েছে।');
    }

    public function destroyQuestion(QuizRoom $quiz, int $questionId)
    {
        $quiz->questions()->findOrFail($questionId)->delete();
        return back()->with('success', 'Question মুছে ফেলা হয়েছে।');
    }

    // ── Leaderboard ───────────────────────────────────────────────────
    public function leaderboard(QuizRoom $quiz)
    {
        $attempts = QuizAttempt::with('user.studentProfile')
            ->where('quiz_room_id', $quiz->id)
            ->orderByDesc('score')
            ->get();
        return view('admin.quiz.leaderboard', compact('quiz', 'attempts'));
    }
}
