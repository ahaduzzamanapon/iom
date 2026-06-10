<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::where('student_id', auth()->id())->with('feeStructure')->latest()->paginate(15);
        $totalDue = Payment::where('student_id', auth()->id())->whereIn('status',['pending','partial'])->sum('amount');
        return view('student.payments.index', compact('payments','totalDue'));
    }
}
