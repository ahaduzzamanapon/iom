<?php

namespace App\Exports;

use App\Models\StudentProfile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return StudentProfile::with(['user', 'batches.batch.course'])->get();
    }

    public function headings(): array
    {
        return ['#', 'Student ID', 'Name', 'Email', 'Phone', 'Gender', 'Guardian', 'Batch', 'Course', 'Status', 'Joined'];
    }

    public function map($student): array
    {
        $batch  = $student->batches->first();
        return [
            $student->id,
            $student->student_id,
            $student->user->name ?? '—',
            $student->user->email ?? '—',
            $student->phone ?? '—',
            ucfirst($student->gender ?? '—'),
            $student->guardian_name ?? '—',
            $batch?->batch?->name ?? '—',
            $batch?->batch?->course?->name ?? '—',
            ucfirst($student->status ?? 'active'),
            $student->created_at?->format('d M Y') ?? '—',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
