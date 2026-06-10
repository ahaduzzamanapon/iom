<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Course;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StudentBatch;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdmissionController extends Controller
{
    public function index()
    {
        $admissions = Admission::with('course')
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->when(request('course_id'), fn($q) => $q->where('course_id', request('course_id')))
            ->latest()->paginate(15);
        $courses = Course::where('status', 'active')->get();
        return view('admin.admissions.index', compact('admissions', 'courses'));
    }

    public function show(Admission $admission)
    {
        $admission->load(['course', 'documents', 'reviewer']);
        return view('admin.admissions.show', compact('admission'));
    }

    public function approve(Request $request, Admission $admission)
    {
        if ($admission->status !== 'pending') {
            return back()->with('error', 'এই admission ইতিমধ্যে processed।');
        }

        $request->validate(['batch_id' => 'required|exists:batches,id']);

        $batch = Batch::findOrFail($request->batch_id);
        abort_if($batch->course_id !== $admission->course_id, 400, 'Selected batch does not belong to the applied course.');

        // Generate Student ID
        $count     = StudentProfile::count() + 1;
        $studentId = 'IOM-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Generate temp password
        $password = Str::random(8);

        // Create user account
        $user = User::create([
            'name'     => $admission->applicant_name,
            'email'    => $admission->applicant_email,
            'password' => Hash::make($password),
        ]);
        $user->assignRole('student');

        // Student profile
        StudentProfile::create([
            'user_id'          => $user->id,
            'student_id'       => $studentId,
            'phone'            => $admission->applicant_phone,
            'date_of_birth'    => $admission->date_of_birth,
            'gender'           => $admission->gender,
            'address'          => $admission->address,
            'photo'            => $admission->photo,
            'guardian_name'    => $admission->guardian_name,
            'guardian_phone'   => $admission->guardian_phone,
        ]);

        // Batch enrollment
        StudentBatch::create([
            'user_id'     => $user->id,
            'batch_id'    => $request->batch_id,
            'enrolled_at' => now()->toDateString(),
        ]);

        // Update admission
        $admission->update([
            'status'      => 'approved',
            'user_id'     => $user->id,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // TODO: Send email with credentials ($user->email, $studentId, $password)

        return redirect()->route('admin.admissions.index')
            ->with('success', "Admission approved! Student ID: {$studentId} | Temp Password: {$password}");
    }

    public function reject(Request $request, Admission $admission)
    {
        $request->validate(['remarks' => 'required|string|max:500']);
        $admission->update([
            'status'      => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'remarks'     => $request->remarks,
        ]);
        return back()->with('success', 'Admission rejected।');
    }

    // Public form
    public function publicForm()
    {
        $courses = Course::where('status', 'active')->get();
        return view('admission', compact('courses'));
    }

    public function publicStore(Request $request)
    {
        $data = $request->validate([
            'course_id'      => 'required|exists:courses,id',
            'applicant_name' => 'required|string|max:255',
            'applicant_email'=> 'required|email|unique:admissions,applicant_email|unique:users,email',
            'applicant_phone'=> 'required|string|max:20',
            'date_of_birth'  => 'nullable|date',
            'gender'         => 'nullable|in:male,female,other',
            'guardian_name'  => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'address'        => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('admissions/photos', 'public');
        }

        Admission::create($data);
        return back()->with('success', 'আপনার আবেদন জমা হয়েছে। আমরা শীঘ্রই যোগাযোগ করব।');
    }

    // Admin create
    public function create()
    {
        $courses = Course::where('status', 'active')->get();
        return view('admin.admissions.create', compact('courses'));
    }

    public function store(Request $request)
    {
        return $this->publicStore($request);
    }

    public function destroy(Admission $admission)
    {
        $admission->delete();
        return back()->with('success', 'Admission মুছে ফেলা হয়েছে।');
    }
}
