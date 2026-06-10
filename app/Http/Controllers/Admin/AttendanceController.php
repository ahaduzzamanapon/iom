<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;
use App\Services\ExportService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $batches = Batch::where('status','active')->with('course')->get();
        $records = Attendance::with(['student','classLesson','batch'])
            ->when(request('batch_id'), fn($q) => $q->where('batch_id', request('batch_id')))
            ->when(request('date'), fn($q) => $q->whereDate('date', request('date')))
            ->latest()->paginate(20);
        return view('admin.attendance.index', compact('batches','records'));
    }

    public function report()
    {
        $batches = Batch::with('course')->get();
        return view('admin.attendance.report', compact('batches'));
    }

    public function export(string $format)
    {
        $records = Attendance::with(['student','batch'])->get();
        return app(ExportService::class)->pdf('exports.attendance-pdf', [
            'title'   => 'Attendance Report',
            'records' => $records,
        ], 'attendance-' . now()->format('Ymd'));
    }
}
