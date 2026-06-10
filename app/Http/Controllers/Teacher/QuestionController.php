<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::where('created_by', auth()->id())->with(['exam','subject'])->latest()->paginate(15);
        return view('teacher.questions.index', compact('questions'));
    }

    public function create()
    {
        $exams    = Exam::whereIn('batch_id', $this->myBatchIds())->get();
        $subjects = Subject::where('status','active')->get();
        return view('teacher.questions.form', compact('exams','subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id'        => 'nullable|exists:exams,id',
            'subject_id'     => 'required|exists:subjects,id',
            'question_text'  => 'required|string',
            'option_a'       => 'nullable|string|max:255',
            'option_b'       => 'nullable|string|max:255',
            'option_c'       => 'nullable|string|max:255',
            'option_d'       => 'nullable|string|max:255',
            'correct_answer' => 'nullable|in:a,b,c,d',
            'marks'          => 'required|integer|min:1',
        ]);
        $data['created_by'] = auth()->id();

        // If exam_id provided, ensure that exam belongs to teacher's batch
        if (!empty($data['exam_id'])) {
            $exam = \App\Models\Exam::find($data['exam_id']);
            abort_if(!$exam || !in_array($exam->batch_id, $this->myBatchIds()), 403, 'You are not assigned to the batch for this exam.');
        }

        Question::create($data);
        return redirect()->route('teacher.questions.index')->with('success', 'Question submit হয়েছে। Admin approve করবেন।');
    }

    public function destroy(Question $question)
    {
        abort_if($question->created_by !== auth()->id(), 403);
        $question->delete();
        return back()->with('success', 'Question মুছে ফেলা হয়েছে।');
    }

    private function myBatchIds(): array
    {
        return \App\Models\Batch::whereHas('subjects', fn($q) =>
            $q->where('batch_subjects.teacher_id', auth()->id())
        )->pluck('id')->toArray();
    }
}
