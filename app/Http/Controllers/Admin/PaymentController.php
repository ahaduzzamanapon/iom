<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\FeeStructure;
use App\Models\User;
use App\Services\ExportService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['student','feeStructure'])
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->latest()->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $students      = User::role('student')->get();
        $feeStructures = FeeStructure::where('status','active')->get();
        return view('admin.payments.form', compact('students','feeStructures'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'       => 'required|exists:users,id',
            'fee_structure_id' => 'nullable|exists:fee_structures,id',
            'amount'           => 'required|numeric|min:0',
            'discount'         => 'nullable|numeric|min:0',
            'paid_amount'      => 'required|numeric|min:0',
            'payment_method'   => 'required|in:online,manual,sslcommerz',
            'due_date'         => 'nullable|date',
        ]);
        $data['invoice_number'] = 'INV-' . strtoupper(uniqid());
        $data['recorded_by']   = auth()->id();

        // Student must actually have student role
        $student = User::find($data['student_id']);
        if (!$student || !$student->hasRole('student')) {
            return back()->withInput()->with('error', 'নির্বাচিত ব্যক্তি Student নন।');
        }

        $net = ($data['amount'] - ($data['discount'] ?? 0));

        // Guard: discount cannot exceed total amount
        if (($data['discount'] ?? 0) > $data['amount']) {
            return back()->withInput()->with('error', 'Discount total amountের চেয়ে বেশি হতে পারবে না।');
        }

        // Guard: paid_amount cannot exceed net payable
        if ($data['paid_amount'] > $net) {
            return back()->withInput()->with('error', 'Paid amount net payable (মোট বিয়োগ বাদ discount) এর চেয়ে বেশি হতে পারবে না।');
        }

        $data['status'] = $data['paid_amount'] >= $net ? 'paid' :
                         ($data['paid_amount'] > 0 ? 'partial' : 'pending');
        if ($data['status'] === 'paid') $data['paid_at'] = now();
        Payment::create($data);
        return redirect()->route('admin.payments.index')->with('success', 'Payment record করা হয়েছে।');
    }

    public function report()
    {
        $total   = Payment::where('status','paid')->sum('paid_amount');
        $pending = Payment::whereIn('status',['pending','partial'])->count();
        return view('admin.payments.report', compact('total','pending'));
    }

    public function export(string $format)
    {
        $payments = Payment::with(['student','feeStructure'])->get();
        return app(ExportService::class)->pdf('exports.payments-pdf', [
            'title'    => 'Payment Report',
            'payments' => $payments,
        ], 'payments-' . now()->format('Ymd'));
    }

    public function destroy(Payment $payment)
    {
        if ($payment->status === 'paid') {
            return back()->with('error', 'পরিশোধিত (paid) payment মুছে ফেলা যাবে না। প্রয়োজনে status হাতে পরিবর্তন করুন।');
        }
        $payment->delete();
        return back()->with('success', 'Payment মুছে ফেলা হয়েছে।');
    }
}
