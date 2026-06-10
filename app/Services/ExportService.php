<?php

namespace App\Services;

use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /**
     * Export data as PDF with branded institute header.
     */
    public function pdf(string $view, array $data, string $filename = 'report'): mixed
    {
        $data['settings'] = SystemSetting::allKeyed();
        $data['export_date'] = now()->format('d M Y');

        $pdf = Pdf::loadView($view, $data)
            ->setPaper('a4', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download($filename . '.pdf');
    }

    /**
     * Export data as Excel file.
     */
    public function excel(object $exportClass, string $filename = 'report'): BinaryFileResponse|StreamedResponse
    {
        return Excel::download($exportClass, $filename . '.xlsx');
    }

    /**
     * Resolve which export type to run based on format string.
     */
    public function export(string $format, string $pdfView, array $pdfData, object $excelClass, string $filename): mixed
    {
        return match (strtolower($format)) {
            'pdf'   => $this->pdf($pdfView, $pdfData, $filename),
            'excel' => $this->excel($excelClass, $filename),
            default => abort(400, 'Invalid export format.'),
        };
    }
}
