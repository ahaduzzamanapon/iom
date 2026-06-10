<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StudentBatch;
use App\Models\Batch;
use App\Services\ExportService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = StudentProfile::with(['user', 'batches.batch.course'])
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->when(request('search'), fn($q) => $q->where('student_id', 'like', '%'.request('search').'%')
                ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%'.request('search').'%')))
            ->latest()->paginate(15);
        return view('admin.students.index', compact('students'));
    }

    public function show(StudentProfile $student)
    {
        $student->load(['user', 'batches.batch.course']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(StudentProfile $student)
    {
        $student->load('user');
        $batches = Batch::where('status', 'active')->with('course')->get();
        return view('admin.students.edit', compact('student', 'batches'));
    }

    public function update(Request $request, StudentProfile $student)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'guardian_name'  => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'status'         => 'required|in:active,inactive,transferred,suspended',
        ]);
        $student->update($request->only(['phone','guardian_name','guardian_phone','address','status']));
        $student->user->update(['name' => $request->name]);
        return redirect()->route('admin.students.show', $student)->with('success', 'Student আপডেট হয়েছে।');
    }

    public function promote(Request $request, User $student)
    {
        $request->validate([
            'from_semester_id' => 'required|exists:semesters,id',
            'to_semester_id'   => 'required|exists:semesters,id',
            'batch_id'         => 'required|exists:batches,id',
            'status'           => 'required|in:promoted,held_back,re_exam',
        ]);

        \App\Models\StudentSemesterPromotion::create([
            'user_id'          => $student->id,
            'from_semester_id' => $request->from_semester_id,
            'to_semester_id'   => $request->to_semester_id,
            'promoted_by'      => auth()->id(),
            'status'           => $request->status,
            'remarks'          => $request->remarks,
            'promoted_at'      => now(),
        ]);

        if ($request->status === 'promoted') {
            // Only mark the specific from-batch as completed, not ALL batches
            StudentBatch::where('user_id', $student->id)
                ->where('batch_id', $request->batch_id)
                ->update(['status' => 'completed']);
            StudentBatch::create([
                'user_id'     => $student->id,
                'batch_id'    => $request->batch_id,
                'enrolled_at' => now()->toDateString(),
            ]);
        }

        return back()->with('success', 'Student promote করা হয়েছে।');
    }

    public function export(string $format)
    {
        $students = StudentProfile::with('user')->get();
        $service  = app(ExportService::class);

        if ($format === 'pdf') {
            return $service->pdf('exports.students-pdf', [
                'title'    => 'Student List',
                'students' => $students,
            ], 'students-' . now()->format('Ymd'));
        }

        return $service->excel(new \App\Exports\StudentsExport, 'students-' . now()->format('Ymd'));
    }

    public function destroy(StudentProfile $student)
    {
        $student->user->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student মুছে ফেলা হয়েছে।');
    }
}
