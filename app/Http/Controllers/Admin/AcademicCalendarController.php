<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use App\Models\Course;
use App\Models\Semester;
use Illuminate\Http\Request;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        $events = AcademicCalendar::with(['course', 'semester'])
            ->when(request('type'), fn($q) => $q->where('type', request('type')))
            ->when(request('course_id'), fn($q) => $q->where('course_id', request('course_id')))
            ->orderBy('start_date')
            ->paginate(20);

        $courses   = Course::all();
        $semesters = Semester::all();

        return view('admin.academic-calendar.index', compact('events', 'courses', 'semesters'));
    }

    public function create()
    {
        $courses   = Course::all();
        $semesters = Semester::all();
        return view('admin.academic-calendar.form', compact('courses', 'semesters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:holiday,exam,event,class_suspension,other',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'course_id'    => 'nullable|exists:courses,id',
            'semester_id'  => 'nullable|exists:semesters,id',
            'description'  => 'nullable|string',
            'is_published' => 'boolean',
        ]);
        $data['created_by']   = auth()->id();
        $data['is_published'] = $request->boolean('is_published', true);

        // If both course_id and semester_id provided, validate they match
        if (!empty($data['course_id']) && !empty($data['semester_id'])) {
            $semester = \App\Models\Semester::find($data['semester_id']);
            if ($semester && $semester->course_id !== (int)$data['course_id']) {
                return back()->withInput()->with('error', 'Semesterটি নির্বাচিত কোর্সের নয়।');
            }
        }

        AcademicCalendar::create($data);
        return redirect()->route('admin.academic-calendars.index')
            ->with('success', 'Academic calendar event যোগ হয়েছে।');
    }

    public function edit(AcademicCalendar $academicCalendar)
    {
        $courses   = Course::all();
        $semesters = Semester::all();
        return view('admin.academic-calendar.form', [
            'event'     => $academicCalendar,
            'courses'   => $courses,
            'semesters' => $semesters,
        ]);
    }

    public function update(Request $request, AcademicCalendar $academicCalendar)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:holiday,exam,event,class_suspension,other',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'course_id'    => 'nullable|exists:courses,id',
            'semester_id'  => 'nullable|exists:semesters,id',
            'description'  => 'nullable|string',
            'is_published' => 'boolean',
        ]);
        $data['is_published'] = $request->boolean('is_published', true);

        $academicCalendar->update($data);
        return redirect()->route('admin.academic-calendars.index')
            ->with('success', 'Event আপডেট হয়েছে।');
    }

    public function destroy(AcademicCalendar $academicCalendar)
    {
        $academicCalendar->delete();
        return back()->with('success', 'Event মুছে ফেলা হয়েছে।');
    }
}
