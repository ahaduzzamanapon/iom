<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with(['course', 'semester'])->withCount('students')->latest()->paginate(15);
        return view('admin.batches.index', compact('batches'));
    }

    public function create()
    {
        $courses   = Course::where('status', 'active')->get();
        $semesters = Semester::with('course')->get();
        return view('admin.batches.form', compact('courses', 'semesters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'name'        => 'required|string|max:255',
            'name_bn'     => 'nullable|string|max:255',
            'capacity'    => 'required|integer|min:1',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
            'status'      => 'required|in:active,inactive,completed',
        ]);
        // Validate that semester belongs to the chosen course BEFORE creating
        $semester = \App\Models\Semester::findOrFail($data['semester_id']);
        if ($semester->course_id !== (int)$data['course_id']) {
            return back()->withInput()->with('error', 'Semesterটি নির্বাচিত কোর্সের নয়।');
        }
        Batch::create($data);
        return redirect()->route('admin.batches.index')->with('success', 'Batch তৈরি হয়েছে।');
    }

    public function edit(Batch $batch)
    {
        $courses   = Course::where('status', 'active')->get();
        $semesters = Semester::with('course')->get();
        return view('admin.batches.form', compact('batch', 'courses', 'semesters'));
    }

    public function update(Request $request, Batch $batch)
    {
        $data = $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'semester_id' => 'required|exists:semesters,id',
            'name'        => 'required|string|max:255',
            'name_bn'     => 'nullable|string|max:255',
            'capacity'    => 'required|integer|min:1',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
            'status'      => 'required|in:active,inactive,completed',
        ]);
        // Validate that semester belongs to the chosen course BEFORE updating
        $semester = \App\Models\Semester::findOrFail($data['semester_id']);
        if ($semester->course_id !== (int)$data['course_id']) {
            return back()->withInput()->with('error', 'Semesterটি নির্বাচিত কোর্সের নয়।');
        }
        $batch->update($data);
        return redirect()->route('admin.batches.index')->with('success', 'Batch আপডেট হয়েছে।');
    }

    public function destroy(Batch $batch)
    {
        $enrolledCount = \App\Models\StudentBatch::where('batch_id', $batch->id)
            ->whereIn('status', ['active', 'completed'])
            ->count();
        if ($enrolledCount > 0) {
            return back()->with('error', 'এই Batchএ ' . $enrolledCount . 'জন Student সংযুক্ত আছেন, মুছে ফেলা যাবে না।');
        }
        $batch->delete();
        return redirect()->route('admin.batches.index')->with('success', 'Batch মুছে ফেলা হয়েছে।');
    }

    public function assignSubject(Request $request, Batch $batch)
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        // Ensure the assigned user actually has the teacher role
        if (!empty($data['teacher_id'])) {
            $teacher = \App\Models\User::find($data['teacher_id']);
            abort_if(!$teacher || !$teacher->hasRole('teacher'), 422, 'Selected user is not a teacher.');
        }

        $batch->subjects()->syncWithoutDetaching([$data['subject_id'] => ['teacher_id' => $data['teacher_id'] ?? null]]);
        return back()->with('success', 'Subject assign হয়েছে।');
    }
}
