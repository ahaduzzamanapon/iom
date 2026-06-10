<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Semester;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    private function myBatchIds(): array
    {
        return Batch::whereHas('subjects', fn($q) =>
            $q->where('batch_subjects.teacher_id', auth()->id())
        )->pluck('id')->toArray();
    }

    public function index()
    {
        $exams = Exam::whereIn('batch_id', $this->myBatchIds())
            ->with(['batch.course', 'subject', 'semester'])
            ->latest()
            ->paginate(15);
        return view('teacher.exams.index', compact('exams'));
    }

    public function create()
    {
        $batches = Batch::whereIn('id', $this->myBatchIds())
            ->with(['course', 'subjects' => function($q) {
                $q->where('batch_subjects.teacher_id', auth()->id());
            }])
            ->get();
            
        $subjectIds = \DB::table('batch_subjects')->where('teacher_id', auth()->id())->pluck('subject_id')->unique()->toArray();
        $subjects  = Subject::whereIn('id', $subjectIds)->where('status', 'active')->get();
        $semesters = Semester::where('status', 'active')->get();
        return view('teacher.exams.form', compact('batches', 'subjects', 'semesters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'batch_id'         => 'required|exists:batches,id',
            'subject_id'       => 'required|exists:subjects,id',
            'semester_id'      => 'nullable|exists:semesters,id',
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:mcq,written,re_exam',
            'total_marks'      => 'required|integer|min:1',
            'pass_marks'       => 'required|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:1',
            'start_at'         => 'nullable|date',
            'end_at'           => 'nullable|date|after_or_equal:start_at',
        ]);

        $myBatchIds = $this->myBatchIds();
        $subjectIds = \DB::table('batch_subjects')->where('teacher_id', auth()->id())->pluck('subject_id')->unique()->toArray();
        abort_if(!in_array($data['batch_id'], $myBatchIds), 403, 'You are not assigned to this batch.');
        abort_if(!in_array($data['subject_id'], $subjectIds), 403, 'You are not assigned to teach this subject.');

        if ($data['pass_marks'] > $data['total_marks']) {
            return back()->withInput()->with('error', 'Pass marks cannot exceed total marks.');
        }

        $data['status'] = 'draft';
        Exam::create($data);
        return redirect()->route('teacher.exams.index')->with('success', 'Exam তৈরি হয়েছে। Admin approve করলে publish হবে।');
    }

    public function destroy(Exam $exam)
    {
        abort_if(!in_array($exam->batch_id, $this->myBatchIds()), 403);
        $exam->delete();
        return back()->with('success', 'Exam মুছে ফেলা হয়েছে।');
    }

    public function assignQuestionsForm(Exam $exam)
    {
        abort_if(!in_array($exam->batch_id, $this->myBatchIds()), 403);
        $exam->load(['subject', 'batch']);
        
        // Find all approved questions for this subject
        $allQuestions = \App\Models\Question::where('subject_id', $exam->subject_id)
            ->where('status', 'approved')
            ->get();
            
        $assignedQuestions = $allQuestions->where('exam_id', $exam->id);
        $unassignedQuestions = $allQuestions->where('exam_id', '!=', $exam->id);

        return view('teacher.exams.assign_questions', compact('exam', 'assignedQuestions', 'unassignedQuestions'));
    }

    public function assignQuestionsStore(Request $request, Exam $exam)
    {
        abort_if(!in_array($exam->batch_id, $this->myBatchIds()), 403);
        $questionIds = $request->input('question_ids', []);

        // 1. Unassign all questions currently assigned to this exam
        \App\Models\Question::where('exam_id', $exam->id)->update(['exam_id' => null]);

        // 2. Assign selected questions to this exam
        if (!empty($questionIds)) {
            \App\Models\Question::whereIn('id', $questionIds)
                ->where('subject_id', $exam->subject_id)
                ->update(['exam_id' => $exam->id]);
        }

        return redirect()->route('teacher.exams.index')->with('success', 'Exam questions successfully updated.');
    }
}
