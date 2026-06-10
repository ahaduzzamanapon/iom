<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Course;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('course')->latest()->paginate(15);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $courses = Course::where('status','active')->get();
        return view('admin.subjects.form', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id'    => 'required|exists:courses,id',
            'name'         => 'required|string|max:255',
            'name_bn'      => 'nullable|string|max:255',
            'code'         => 'nullable|string|max:50',
            'credit_hours' => 'required|integer|min:1',
            'description'  => 'nullable|string',
            'status'       => 'required|in:active,inactive',
        ]);
        Subject::create($data);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject তৈরি হয়েছে।');
    }

    public function edit(Subject $subject)
    {
        $courses = Course::where('status','active')->get();
        return view('admin.subjects.form', compact('subject','courses'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'course_id'    => 'required|exists:courses,id',
            'name'         => 'required|string|max:255',
            'name_bn'      => 'nullable|string|max:255',
            'code'         => 'nullable|string|max:50',
            'credit_hours' => 'required|integer|min:1',
            'description'  => 'nullable|string',
            'status'       => 'required|in:active,inactive',
        ]);
        $subject->update($data);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject আপডেট হয়েছে।');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->modules()->count() > 0) {
            return back()->with('error', 'এই Subjectে ' . $subject->modules()->count() . 'টি Module আছে। আগে Module মুছুন।');
        }
        if (\DB::table('batch_subjects')->where('subject_id', $subject->id)->exists()) {
            return back()->with('error', 'এই Subject একটি batchে সংযুক্ত আছে। প্রথমে সেটি সরান।');
        }
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject মুছে ফেলা হয়েছে।');
    }
}
