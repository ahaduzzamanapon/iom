<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount(['batches', 'subjects'])->latest()->paginate(15);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'name_bn'        => 'nullable|string|max:255',
            'type'           => 'required|in:short_term,long_term',
            'duration_years' => 'required|integer|min:1|max:10',
            'description'    => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ]);
        Course::create($data);
        return redirect()->route('admin.courses.index')->with('success', 'Course তৈরি হয়েছে।');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.form', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'name_bn'        => 'nullable|string|max:255',
            'type'           => 'required|in:short_term,long_term',
            'duration_years' => 'required|integer|min:1|max:10',
            'description'    => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ]);
        $course->update($data);
        return redirect()->route('admin.courses.index')->with('success', 'Course আপডেট হয়েছে।');
    }

    public function destroy(Course $course)
    {
        if ($course->batches()->count() > 0) {
            return back()->with('error', 'এই Courseে ' . $course->batches()->count() . 'টি Batch আছে। আগে সেগুলো মুছুন।');
        }
        if ($course->subjects()->count() > 0) {
            return back()->with('error', 'এই Courseে ' . $course->subjects()->count() . 'টি Subject আছে। আগে সেগুলো মুছুন।');
        }
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course মুছে ফেলা হয়েছে।');
    }
}
