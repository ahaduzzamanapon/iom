<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Readmission;
use Illuminate\Http\Request;

class ReadmissionController extends Controller
{
    public function index()
    {
        $readmissions = Readmission::where('user_id', auth()->id())->latest()->get();
        return view('student.readmission.index', compact('readmissions'));
    }

    public function create()
    {
        $courses = Course::where('status', 'active')->get();
        return view('student.readmission.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'reason'    => 'required|string|max:1000',
        ]);

        // Only inactive/suspended students can apply for readmission
        $profile = \App\Models\StudentProfile::where('user_id', auth()->id())->first();
        if ($profile && $profile->status === 'active') {
            return back()->with('error', 'আপনি ইতিমধ্যে সক্রিয় শিক্ষার্থী। Readmission প্রযোজ্য নয়।');
        }

        // Check no pending application already
        $existing = Readmission::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return back()->with('error', 'আপনার একটি pending readmission আবেদন আছে।');
        }

        Readmission::create(array_merge($data, ['user_id' => auth()->id()]));

        return redirect()->route('student.readmission.index')
            ->with('success', 'Readmission আবেদন জমা হয়েছে।');
    }
}
