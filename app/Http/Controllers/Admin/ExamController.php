<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Semester;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with(['batch.course', 'subject', 'semester'])
            ->when(request('type'), fn($q) => $q->where('type', request('type')))
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->latest()->paginate(15);
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $batches   = Batch::with(['course', 'subjects'])->where('status', 'active')->get();
        $subjects  = Subject::where('status', 'active')->get();
        $semesters = Semester::where('status', 'active')->get();
        return view('admin.exams.form', compact('batches', 'subjects', 'semesters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'batch_id'         => 'required|exists:batches,id',
            'subject_id'       => 'required|exists:subjects,id',
            'semester_id'      => 'required|exists:semesters,id',
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:mcq,written,re_exam,improvement',
            'start_at'         => 'nullable|date',
            'end_at'           => 'nullable|date',
            'duration_minutes' => 'required|integer|min:5',
            'total_marks'      => 'required|integer|min:1',
            'pass_marks'       => 'required|integer|min:1',
        ]);

        if ($data['pass_marks'] > $data['total_marks']) {
            return back()->withInput()->with('error', 'Pass marks cannot exceed total marks.');
        }

        // start_at must be before end_at if both provided
        if (!empty($data['start_at']) && !empty($data['end_at']) && $data['start_at'] >= $data['end_at']) {
            return back()->withInput()->with('error', 'Exam start time must be before end time.');
        }

        Exam::create($data);
        return redirect()->route('admin.exams.index')->with('success', 'Exam তৈরি হয়েছে।');
    }

    public function show(Exam $exam)
    {
        $exam->load(['batch.course','subject','semester','questions','attempts.student']);
        return view('admin.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        $batches   = Batch::with(['course', 'subjects'])->where('status','active')->get();
        $subjects  = Subject::where('status','active')->get();
        $semesters = Semester::where('status','active')->get();
        return view('admin.exams.form', compact('exam','batches','subjects','semesters'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'batch_id'         => 'required|exists:batches,id',
            'subject_id'       => 'required|exists:subjects,id',
            'semester_id'      => 'required|exists:semesters,id',
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:mcq,written,re_exam,improvement',
            'start_at'         => 'nullable|date',
            'end_at'           => 'nullable|date',
            'duration_minutes' => 'required|integer|min:5',
            'total_marks'      => 'required|integer|min:1',
            'pass_marks'       => 'required|integer|min:1',
            'status'           => 'required|in:draft,approved,published,completed',
        ]);

        if ($data['pass_marks'] > $data['total_marks']) {
            return back()->withInput()->with('error', 'Pass marks cannot exceed total marks.');
        }

        // start_at must be before end_at if both provided
        if (!empty($data['start_at']) && !empty($data['end_at']) && $data['start_at'] >= $data['end_at']) {
            return back()->withInput()->with('error', 'Exam start time must be before end time.');
        }

        // If exam has attempts, disallow changing marks (would corrupt scores)
        if ($exam->attempts()->count() > 0) {
            if ($data['total_marks'] != $exam->total_marks || $data['pass_marks'] != $exam->pass_marks) {
                return back()->withInput()->with('error', 'ছাত্ররা পরীক্ষা দিয়েছেন, মার্কস পরিবর্তন করা যাবে না।');
            }
        }

        $exam->update($data);
        return redirect()->route('admin.exams.index')->with('success', 'Exam আপডেট হয়েছে।');
    }

    public function destroy(Exam $exam)
    {
        if ($exam->attempts()->count() > 0) {
            return back()->with('error', 'এই Examে ' . $exam->attempts()->count() . 'জন ছাত্র পরীক্ষা দিয়েছেন, মুছে ফেলা যাবে না।');
        }
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success', 'Exam মুছে ফেলা হয়েছে।');
    }

    public function assignQuestionsForm(Exam $exam)
    {
        $exam->load(['subject', 'batch']);
        
        // Find all approved questions for this subject
        $allQuestions = \App\Models\Question::where('subject_id', $exam->subject_id)
            ->where('status', 'approved')
            ->get();
            
        $assignedQuestions = $allQuestions->where('exam_id', $exam->id);
        $unassignedQuestions = $allQuestions->where('exam_id', '!=', $exam->id);

        return view('admin.exams.assign_questions', compact('exam', 'assignedQuestions', 'unassignedQuestions'));
    }

    public function assignQuestionsStore(Request $request, Exam $exam)
    {
        $questionIds = $request->input('question_ids', []);

        // 1. Unassign all questions currently assigned to this exam
        \App\Models\Question::where('exam_id', $exam->id)->update(['exam_id' => null]);

        // 2. Assign selected questions to this exam
        if (!empty($questionIds)) {
            \App\Models\Question::whereIn('id', $questionIds)
                ->where('subject_id', $exam->subject_id)
                ->update(['exam_id' => $exam->id]);
        }

        return redirect()->route('admin.exams.show', $exam)->with('success', 'Exam questions successfully updated.');
    }
}
