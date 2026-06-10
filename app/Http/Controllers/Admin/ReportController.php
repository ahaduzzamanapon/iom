<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AttendanceExport;
use App\Exports\PaymentsExport;
use App\Exports\ResultsExport;
use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Payment;
use App\Models\Result;
use App\Models\Semester;
use App\Models\StudentProfile;
use App\Services\ExportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ExportService $export) {}

    // ── Dashboard analytics overview ─────────────────────────────────
    public function index()
    {
        $stats = [
            'total_students' => StudentProfile::count(),
            'total_courses'  => Course::count(),
            'total_batches'  => Batch::count(),
            'total_revenue'  => Payment::where('status', 'paid')->sum('paid_amount'),
        ];

        $recentPayments  = Payment::with('user')->latest()->take(5)->get();
        $enrollmentByCourse = Course::withCount(['batches'])->get();

        return view('admin.reports.index', compact('stats', 'recentPayments', 'enrollmentByCourse'));
    }

    // ── Student Report ────────────────────────────────────────────────
    public function students(Request $request)
    {
        $students = StudentProfile::with(['user', 'batches.batch.course'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(20);

        $batches = Batch::with('course')->get();

        return view('admin.reports.students', compact('students', 'batches'));
    }

    public function exportStudents(string $format)
    {
        $students = StudentProfile::with('user')->get();

        if ($format === 'pdf') {
            return $this->export->pdf('exports.students-pdf', [
                'title'    => 'Student Report',
                'students' => $students,
            ], 'students-report-' . now()->format('Ymd'));
        }

        return $this->export->excel(new StudentsExport, 'students-report-' . now()->format('Ymd'));
    }

    // ── Attendance Report ─────────────────────────────────────────────
    public function attendance(Request $request)
    {
        $batches    = Batch::with('course')->get();
        $attendance = collect();

        if ($request->batch_id) {
            $attendance = Attendance::with(['user.studentProfile', 'classLesson'])
                ->where('batch_id', $request->batch_id)
                ->when($request->from, fn($q) => $q->whereDate('date', '>=', $request->from))
                ->when($request->to,   fn($q) => $q->whereDate('date', '<=', $request->to))
                ->orderBy('date')
                ->paginate(30);
        }

        return view('admin.reports.attendance', compact('batches', 'attendance'));
    }

    public function exportAttendance(string $format, Request $request)
    {
        $export = new AttendanceExport($request->batch_id, $request->from, $request->to);
        $fname  = 'attendance-report-' . now()->format('Ymd');

        if ($format === 'pdf') {
            $data = $export->collection();
            return $this->export->pdf('exports.attendance-pdf', [
                'title'      => 'Attendance Report',
                'attendance' => $data,
            ], $fname);
        }

        return $this->export->excel($export, $fname);
    }

    // ── Examination Report ────────────────────────────────────────────
    public function examination(Request $request)
    {
        $exams   = Exam::with(['batch', 'subject'])->latest()->paginate(20);
        $results = collect();

        if ($request->exam_id) {
            $results = Result::with(['user.studentProfile', 'exam'])
                ->where('exam_id', $request->exam_id)
                ->orderByDesc('obtained_marks')
                ->paginate(30);
        }

        return view('admin.reports.examination', compact('exams', 'results'));
    }

    public function exportExamination(string $format, Request $request)
    {
        $export = new ResultsExport($request->exam_id);
        $fname  = 'exam-results-' . now()->format('Ymd');

        if ($format === 'pdf') {
            return $this->export->pdf('exports.results-pdf', [
                'title'   => 'Examination Results Report',
                'results' => $export->collection(),
            ], $fname);
        }

        return $this->export->excel($export, $fname);
    }

    // ── Financial Report ──────────────────────────────────────────────
    public function financial(Request $request)
    {
        $payments = Payment::with(['user.studentProfile', 'feeStructure'])
            ->when($request->from,   fn($q) => $q->whereDate('paid_at', '>=', $request->from))
            ->when($request->to,     fn($q) => $q->whereDate('paid_at', '<=', $request->to))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest('paid_at')
            ->paginate(25);

        $totalRevenue = Payment::where('status', 'paid')
            ->when($request->from, fn($q) => $q->whereDate('paid_at', '>=', $request->from))
            ->when($request->to,   fn($q) => $q->whereDate('paid_at', '<=', $request->to))
            ->sum('paid_amount');

        return view('admin.reports.financial', compact('payments', 'totalRevenue'));
    }

    public function exportFinancial(string $format, Request $request)
    {
        $export = new PaymentsExport($request->from, $request->to, $request->status);
        $fname  = 'financial-report-' . now()->format('Ymd');

        if ($format === 'pdf') {
            return $this->export->pdf('exports.payments-pdf', [
                'title'    => 'Financial Report',
                'payments' => $export->collection(),
            ], $fname);
        }

        return $this->export->excel($export, $fname);
    }

    // ── Batch & Course Analytics ──────────────────────────────────────
    public function analytics()
    {
        $courses = Course::withCount(['batches', 'subjects'])->get();
        $batches = Batch::with('course')
            ->withCount([
                'students as student_count',
            ])
            ->get();

        $semesters = Semester::with('course')->get();

        return view('admin.reports.analytics', compact('courses', 'batches', 'semesters'));
    }
}
