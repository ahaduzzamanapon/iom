<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = \App\Models\Scholarship::with(['student','approver'])->latest()->paginate(15);
        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        $students = User::role('student')->get();
        return view('admin.scholarships.form', compact('students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'       => 'required|exists:users,id',
            'title'            => 'required|string|max:255',
            'discount_amount'  => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'reason'           => 'nullable|string',
        ]);

        // Must provide exactly one discount type
        if (!empty($data['discount_amount']) && !empty($data['discount_percent'])) {
            return back()->withInput()->with('error', 'Discount amount অথবা percent একটি দিন, একসাথে দুটো দেওয়া যাবে না।');
        }
        if (empty($data['discount_amount']) && empty($data['discount_percent'])) {
            return back()->withInput()->with('error', 'অন্তত একটি discount (amount বা percent) দিতে হবে।');
        }
        \App\Models\Scholarship::create($data);
        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship আবেদন তৈরি হয়েছে।');
    }

    public function approve(Request $request, \App\Models\Scholarship $scholarship)
    {
        if ($scholarship->status === 'approved') {
            return back()->with('info', 'এই Scholarship ইতিমধ্যে approve করা আছে।');
        }
        $scholarship->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'Scholarship approve হয়েছে।');
    }

    public function destroy(\App\Models\Scholarship $scholarship)
    {
        if ($scholarship->status === 'approved') {
            return back()->with('error', 'অনুমোদিত Scholarship মুছে ফেলা যাবে না।');
        }
        $scholarship->delete();
        return back()->with('success', 'Scholarship মুছে ফেলা হয়েছে।');
    }
}
