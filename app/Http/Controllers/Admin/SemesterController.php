<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::with('course')->latest()->paginate(15);
        return view('admin.semesters.index', compact('semesters'));
    }

    public function create()
    {
        $courses = Course::where('status', 'active')->get();
        return view('admin.semesters.form', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'name'       => 'required|string|max:255',
            'name_bn'    => 'nullable|string|max:255',
            'order'      => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'required|in:upcoming,active,completed',
        ]);
        Semester::create($data);
        return redirect()->route('admin.semesters.index')->with('success', 'Semester তৈরি হয়েছে।');
    }

    public function edit(Semester $semester)
    {
        $courses = Course::where('status', 'active')->get();
        return view('admin.semesters.form', compact('semester', 'courses'));
    }

    public function update(Request $request, Semester $semester)
    {
        $data = $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'name'       => 'required|string|max:255',
            'name_bn'    => 'nullable|string|max:255',
            'order'      => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'required|in:upcoming,active,completed',
        ]);
        $semester->update($data);
        return redirect()->route('admin.semesters.index')->with('success', 'Semester আপডেট হয়েছে।');
    }

    public function destroy(Semester $semester)
    {
        if ($semester->batches()->count() > 0) {
            return back()->with('error', 'এই Semesterে ' . $semester->batches()->count() . 'টি Batch সংযুক্ত আছে। আগে সেসব Batch মুছুন বা সরান।');
        }
        $semester->delete();
        return redirect()->route('admin.semesters.index')->with('success', 'Semester মুছে ফেলা হয়েছে।');
    }
}
