<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\ExamAttempt;
use App\Models\Batch;
use App\Models\Semester;
use App\Models\User;
use App\Services\ExportService;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::with(['student','semester','batch'])
            ->when(request('batch_id'), fn($q) => $q->where('batch_id', request('batch_id')))
            ->when(request('semester_id'), fn($q) => $q->where('semester_id', request('semester_id')))
            ->latest()->paginate(15);
        $batches   = Batch::all();
        $semesters = Semester::all();
        return view('admin.results.index', compact('results','batches','semesters'));
    }

    public function publish(Result $result)
    {
        if ($result->is_published) {
            return back()->with('info', 'Result ইতিমধ্যে published আছে।');
        }
        $result->update(['is_published' => true, 'published_at' => now()]);
        return back()->with('success', 'Result publish হয়েছে।');
    }

    public function export(string $format)
    {
        $results = Result::with(['student','semester','batch'])->get();
        return app(ExportService::class)->pdf('exports.results-pdf', [
            'title'   => 'Result Report',
            'results' => $results,
        ], 'results-' . now()->format('Ymd'));
    }

    /**
     * Generate transcript PDF for a specific student (EX-10/EX-14).
     */
    public function transcript(User $student)
    {
        $attempts = ExamAttempt::with(['exam.subject', 'exam.semester'])
            ->where('student_id', $student->id)
            ->where('status', 'evaluated')
            ->orderBy('created_at')
            ->get();

        $semesterResults = Result::with('semester')
            ->where('student_id', $student->id)
            ->orderBy('semester_id')
            ->get();

        return app(ExportService::class)->pdf('exports.transcript-pdf', [
            'title'          => 'Academic Transcript',
            'subtitle'       => 'Student: ' . $student->name . ' | ID: ' . $student->id,
            'student'        => $student,
            'attempts'       => $attempts,
            'semesterResults'=> $semesterResults,
        ], 'transcript-' . $student->id . '-' . now()->format('Ymd'));
    }
}
