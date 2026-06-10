<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchTransfer;
use App\Models\Course;
use App\Models\Readmission;
use App\Models\StudentBatch;
use App\Models\StudentProfile;
use Illuminate\Http\Request;

class ReadmissionController extends Controller
{
    // ── Readmissions ──────────────────────────────────────────────────
    public function index()
    {
        $readmissions = Readmission::with(['user.studentProfile', 'course', 'batch'])
            ->latest()->paginate(15);
        return view('admin.readmissions.index', compact('readmissions'));
    }

    public function create()
    {
        $students = StudentProfile::with('user')->where('status', 'inactive')->get();
        $courses  = Course::where('status', 'active')->get();
        $batches  = Batch::where('status', 'active')->with('course')->get();
        return view('admin.readmissions.create', compact('students', 'courses', 'batches'));
    }

    // Admin-direct readmission (RA-03)
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'batch_id'  => 'required|exists:batches,id',
            'reason'    => 'required|string|max:1000',
        ]);

        $readmission = Readmission::create(array_merge($data, [
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]));

        // Reactivate student and enroll in new batch (guard against duplicate enrollment)
        StudentProfile::where('user_id', $data['user_id'])->update(['status' => 'active']);
        $alreadyEnrolled = StudentBatch::where('user_id', $data['user_id'])
            ->where('batch_id', $data['batch_id'])
            ->where('status', 'active')
            ->exists();
        if (!$alreadyEnrolled) {
            StudentBatch::create([
                'user_id'     => $data['user_id'],
                'batch_id'    => $data['batch_id'],
                'enrolled_at' => now()->toDateString(),
            ]);
        }

        return redirect()->route('admin.readmissions.index')->with('success', 'Readmission সম্পন্ন হয়েছে।');
    }

    // Student-submitted application approval (RA-02)
    public function approve(Readmission $readmission, Request $request)
    {
        $request->validate(['batch_id' => 'required|exists:batches,id']);

        $readmission->update([
            'status'      => 'approved',
            'batch_id'    => $request->batch_id,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        StudentProfile::where('user_id', $readmission->user_id)->update(['status' => 'active']);
        $alreadyEnrolled = StudentBatch::where('user_id', $readmission->user_id)
            ->where('batch_id', $request->batch_id)
            ->where('status', 'active')
            ->exists();
        if (!$alreadyEnrolled) {
            StudentBatch::create([
                'user_id'     => $readmission->user_id,
                'batch_id'    => $request->batch_id,
                'enrolled_at' => now()->toDateString(),
            ]);
        }

        return back()->with('success', 'Readmission অনুমোদন হয়েছে।');
    }

    public function reject(Readmission $readmission, Request $request)
    {
        $request->validate(['remarks' => 'required|string|max:500']);
        $readmission->update([
            'status'      => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'remarks'     => $request->remarks,
        ]);
        return back()->with('success', 'Readmission প্রত্যাখ্যান করা হয়েছে।');
    }

    // ── Course/Batch Transfers (RA-04) ────────────────────────────────
    public function transfers()
    {
        $transfers = BatchTransfer::with(['user.studentProfile', 'fromBatch.course', 'toBatch.course'])
            ->latest()->paginate(15);
        return view('admin.readmissions.transfers', compact('transfers'));
    }

    public function storeTransfer(Request $request)
    {
        $data = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'from_batch_id'=> 'required|exists:batches,id',
            'to_batch_id'  => 'required|exists:batches,id|different:from_batch_id',
            'reason'       => 'nullable|string|max:500',
        ]);

        // Guard: student must actually be enrolled in the from_batch
        $fromEnrollment = StudentBatch::where('user_id', $data['user_id'])
            ->where('batch_id', $data['from_batch_id'])
            ->where('status', 'active')
            ->first();
        if (!$fromEnrollment) {
            return back()->with('error', 'শিক্ষার্থী এই ব্যাচে ভর্তি নেই, transfer সম্ভব নয়।');
        }

        // Guard: student must not already be in the to_batch
        $alreadyInTarget = StudentBatch::where('user_id', $data['user_id'])
            ->where('batch_id', $data['to_batch_id'])
            ->where('status', 'active')
            ->exists();
        if ($alreadyInTarget) {
            return back()->with('error', 'শিক্ষার্থী ইতিমধ্যে এই ব্যাচে আছেন।');
        }

        // Execute transfer immediately
        StudentBatch::where('user_id', $data['user_id'])
            ->where('batch_id', $data['from_batch_id'])
            ->update(['status' => 'transferred']);

        StudentBatch::create([
            'user_id'     => $data['user_id'],
            'batch_id'    => $data['to_batch_id'],
            'enrolled_at' => now()->toDateString(),
        ]);

        BatchTransfer::create(array_merge($data, [
            'status'         => 'approved',
            'approved_by'    => auth()->id(),
            'transferred_at' => now(),
        ]));

        return redirect()->route('admin.readmissions.transfers')->with('success', 'Transfer সম্পন্ন হয়েছে।');
    }
}
