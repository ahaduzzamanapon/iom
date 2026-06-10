<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\User;
use App\Models\Course;
use App\Services\ExportService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['student','course'])->latest()->paginate(15);
        return view('admin.certificates.index', compact('certificates'));
    }

    public function generate(Request $request, User $student)
    {
        $request->validate(['course_id' => 'required|exists:courses,id']);

        // Ensure the student is/was enrolled in a batch for this course
        $enrolled = \App\Models\StudentBatch::where('user_id', $student->id)
            ->whereHas('batch', fn($q) => $q->where('course_id', $request->course_id))
            ->exists();
        abort_if(!$enrolled, 422, 'शিক্ষার্থী এই কোর্সে ভর্তি ছিলেন না, সার্টিফিকেট দেওয়া যাবে না।');

        // Prevent duplicate certificate for the same student + course
        $existing = \App\Models\Certificate::where('student_id', $student->id)
            ->where('course_id', $request->course_id)
            ->first();
        if ($existing) {
            return redirect()->route('admin.certificates.index')
                ->with('info', 'এই কোর্সের জন্য আগেই সার্টিফিকেট দেওয়া হয়েছে (#' . $existing->certificate_number . ')।');
        }

        $number = 'CERT-' . strtoupper(uniqid());
        $qrData = url('/verify/' . $number);
        $qrPath = 'qrcodes/' . $number . '.svg';

        // Save QR code
        $dir = public_path('uploads/qrcodes');
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        file_put_contents(public_path('uploads/' . $qrPath),
            QrCode::size(200)->generate($qrData));

        $cert = Certificate::create([
            'student_id'         => $student->id,
            'course_id'          => $request->course_id,
            'certificate_number' => $number,
            'qr_code'            => $qrPath,
            'issued_date'        => now()->toDateString(),
        ]);

        // Generate PDF
        return app(ExportService::class)->pdf('exports.certificate-pdf', [
            'title'       => 'Course Completion Certificate',
            'certificate' => $cert->load(['student','course']),
        ], 'certificate-' . $number);
    }
}
