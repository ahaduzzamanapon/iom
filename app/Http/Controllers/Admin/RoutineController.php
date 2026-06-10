<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    public function index()
    {
        $routines = Routine::with(['batch.course','subject','teacher'])->get()->groupBy('batch_id');
        $batches  = Batch::where('status','active')->with('course')->get();
        return view('admin.routines.index', compact('routines','batches'));
    }

    public function create()
    {
        $batches  = Batch::where('status','active')->with('course')->get();
        $subjects = Subject::where('status','active')->get();
        $teachers = User::role('teacher')->get();
        return view('admin.routines.form', compact('batches','subjects','teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'batch_id'   => 'required|exists:batches,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'day'        => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'room'       => 'nullable|string|max:100',
            'type'       => 'required|in:class,exam',
        ]);

        // Teacher must actually have the teacher role
        $teacher = User::find($data['teacher_id']);
        if (!$teacher || !$teacher->hasRole('teacher')) {
            return back()->withInput()->with('error', 'নির্বাচিত ব্যর্ক্তি একজন teacher নন।');
        }

        // Prevent duplicate slot: same batch + day + overlapping start_time
        $conflict = Routine::where('batch_id', $data['batch_id'])
            ->where('day', $data['day'])
            ->where('start_time', $data['start_time'])
            ->exists();
        if ($conflict) {
            return back()->withInput()->with('error', 'এই batchের জন্য এই দিন ও সময়ে ইতিমধ্যে একটি routine আছে।');
        }

        Routine::create($data);
        return redirect()->route('admin.routines.index')->with('success', 'Routine তৈরি হয়েছে।');
    }

    public function destroy(Routine $routine)
    {
        $routine->delete();
        return back()->with('success', 'Routine মুছে ফেলা হয়েছে।');
    }
}
