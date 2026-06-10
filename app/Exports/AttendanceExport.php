<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private ?int $batchId = null,
        private ?string $from = null,
        private ?string $to = null,
    ) {}

    public function collection()
    {
        return Attendance::with(['user.studentProfile', 'classLesson', 'batch'])
            ->when($this->batchId, fn($q) => $q->where('batch_id', $this->batchId))
            ->when($this->from,    fn($q) => $q->whereDate('date', '>=', $this->from))
            ->when($this->to,      fn($q) => $q->whereDate('date', '<=', $this->to))
            ->orderBy('date')
            ->get();
    }

    public function headings(): array
    {
        return ['Date', 'Student ID', 'Student Name', 'Batch', 'Class/Lesson', 'Status', 'Remarks'];
    }

    public function map($row): array
    {
        return [
            $row->date,
            $row->user->studentProfile->student_id ?? '—',
            $row->user->name ?? '—',
            $row->batch->name ?? '—',
            $row->classLesson->title ?? '—',
            ucfirst($row->status),
            $row->remarks ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
