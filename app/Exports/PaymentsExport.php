<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private ?string $from = null,
        private ?string $to = null,
        private ?string $status = null,
    ) {}

    public function collection()
    {
        return Payment::with(['user.studentProfile', 'feeStructure'])
            ->when($this->from,   fn($q) => $q->whereDate('paid_at', '>=', $this->from))
            ->when($this->to,     fn($q) => $q->whereDate('paid_at', '<=', $this->to))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->latest('paid_at')
            ->get();
    }

    public function headings(): array
    {
        return ['Transaction ID', 'Student ID', 'Student Name', 'Fee Type', 'Amount (৳)', 'Method', 'Status', 'Paid At', 'Remarks'];
    }

    public function map($row): array
    {
        return [
            $row->transaction_id ?? '—',
            $row->user->studentProfile->student_id ?? '—',
            $row->user->name ?? '—',
            $row->feeStructure->name ?? 'Manual',
            number_format($row->amount, 2),
            ucfirst($row->method),
            ucfirst($row->status),
            $row->paid_at ? $row->paid_at->format('d M Y') : '—',
            $row->remarks ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
