<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Batch;
use App\Models\Payment;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\Exam;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'students'         => StudentProfile::where('status', 'active')->count(),
            'teachers'         => TeacherProfile::where('status', 'active')->count(),
            'batches'          => Batch::where('status', 'active')->count(),
            'pending_admissions' => Admission::where('status', 'pending')->count(),
            'pending_exams'    => Exam::where('status', 'draft')->count(),
            'total_revenue'    => Payment::where('status', 'paid')->sum('paid_amount'),
        ];

        $recent_admissions = Admission::with('course')->latest()->take(6)->get();
        $recent_payments   = Payment::with('student')->latest()->take(6)->get();

        return view('admin.dashboard', compact('stats', 'recent_admissions', 'recent_payments'));
    }
}
