<?php

namespace App\Exports;

use App\Models\Result;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResultsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(private ?int $examId = null) {}

    public function collection()
    {
        return Result::with(['user.studentProfile', 'exam.subject'])
            ->when($this->examId, fn($q) => $q->where('exam_id', $this->examId))
            ->orderByDesc('obtained_marks')
            ->get();
    }

    public function headings(): array
    {
        return ['Student ID', 'Student Name', 'Exam', 'Subject', 'Total Marks', 'Obtained Marks', 'Percentage', 'Grade', 'GPA', 'Status'];
    }

    public function map($row): array
    {
        $pct = $row->exam->total_marks > 0
            ? round(($row->obtained_marks / $row->exam->total_marks) * 100, 2)
            : 0;

        return [
            $row->user->studentProfile->student_id ?? '—',
            $row->user->name ?? '—',
            $row->exam->title ?? '—',
            $row->exam->subject->name ?? '—',
            $row->exam->total_marks,
            $row->obtained_marks,
            $pct . '%',
            $row->grade ?? '—',
            $row->gpa ?? '—',
            $row->status ?? '—',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
