<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\QuizRoom;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Show available/joinable quizzes for the student's batch
    public function index()
    {
        $user     = auth()->user();
        $batchIds = $user->studentBatches()->where('status', 'active')->pluck('batch_id');

        $quizzes = QuizRoom::where('status', 'active')
            ->where(fn($q) => $q->whereNull('batch_id')->orWhereIn('batch_id', $batchIds))
            ->withCount('questions')
            ->with('batch')
            ->latest()
            ->get();

        $attempts = QuizAttempt::where('user_id', $user->id)
            ->pluck('quiz_room_id')
            ->toArray();

        return view('student.quiz.index', compact('quizzes', 'attempts'));
    }

    // Join quiz by room code
    public function join(Request $request)
    {
        $request->validate(['room_code' => 'required|string']);

        $quiz = QuizRoom::where('room_code', strtoupper($request->room_code))
            ->where('status', 'active')
            ->firstOrFail();

        $this->validateAccess($quiz);

        return redirect()->route('student.quiz.take', $quiz);
    }

    // Show quiz questions (start/take)
    public function take(QuizRoom $quiz)
    {
        $this->validateAccess($quiz);

        // Already attempted?
        $attempt = QuizAttempt::where('quiz_room_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($attempt?->submitted_at) {
            return redirect()->route('student.quiz.result', $quiz)
                ->with('info', 'আপনি এই quiz ইতিমধ্যে submit করেছেন।');
        }

        $questions = $quiz->questions()->orderBy('order')->get();

        return view('student.quiz.take', compact('quiz', 'questions', 'attempt'));
    }

    // Submit quiz
    public function submit(Request $request, QuizRoom $quiz)
    {
        $this->validateAccess($quiz);

        // Prevent submission after quiz closes
        if ($quiz->ends_at && now()->gt($quiz->ends_at)) {
            return redirect()->route('student.quiz.index')
                ->with('error', 'এই Quizের সময় শেষ হয়ে গেছে, আর submit করা যাবে না।');
        }

        $attempt = QuizAttempt::firstOrCreate([
            'quiz_room_id' => $quiz->id,
            'user_id'      => auth()->id(),
        ], ['total' => $quiz->questions()->sum('marks')]);

        if ($attempt->submitted_at) {
            return redirect()->route('student.quiz.result', $quiz);
        }

        $questions = $quiz->questions()->get();
        $score     = 0;

        foreach ($questions as $question) {
            $selected  = $request->input("answers.{$question->id}");
            $isCorrect = $selected === $question->correct_option;

            QuizAttemptAnswer::updateOrCreate(
                ['quiz_attempt_id' => $attempt->id, 'quiz_question_id' => $question->id],
                ['selected_option' => $selected, 'is_correct' => $isCorrect]
            );

            if ($isCorrect) {
                $score += $question->marks;
            }
        }

        $attempt->update([
            'score'        => $score,
            'total'        => $questions->sum('marks'),
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.quiz.result', $quiz);
    }

    // Show result
    public function result(QuizRoom $quiz)
    {
        $this->validateAccess($quiz);

        $attempt = QuizAttempt::with('answers.question')
            ->where('quiz_room_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('student.quiz.result', compact('quiz', 'attempt'));
    }

    private function validateAccess(QuizRoom $quiz)
    {
        if ($quiz->batch_id) {
            $user = auth()->user();
            $batchIds = $user->studentBatches()->where('status', 'active')->pluck('batch_id')->toArray();
            abort_if(!in_array($quiz->batch_id, $batchIds), 403, 'You are not enrolled in the batch for this quiz.');
        }
    }
}
