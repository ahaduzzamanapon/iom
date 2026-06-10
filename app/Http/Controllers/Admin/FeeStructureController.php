<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\Course;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index()
    {
        $fees = FeeStructure::with('course')->latest()->paginate(15);
        return view('admin.fee-structures.index', compact('fees'));
    }

    public function create()
    {
        $courses = Course::where('status','active')->get();
        return view('admin.fee-structures.form', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:admission,monthly,package,other',
            'amount'      => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
        ]);
        FeeStructure::create($data);
        return redirect()->route('admin.fee-structures.index')->with('success', 'Fee structure তৈরি হয়েছে।');
    }

    public function edit(FeeStructure $feeStructure)
    {
        $courses = Course::where('status','active')->get();
        return view('admin.fee-structures.form', compact('feeStructure','courses'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $data = $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:255',
            'type'        => 'required|in:admission,monthly,package,other',
            'amount'      => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
        ]);
        $feeStructure->update($data);
        return redirect()->route('admin.fee-structures.index')->with('success', 'Fee structure আপডেট হয়েছে।');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        if (\App\Models\Payment::where('fee_structure_id', $feeStructure->id)->exists()) {
            return back()->with('error', 'এই Fee Structureের সাথে Payment রেকর্ড সংযুক্ত আছে, মুছে ফেলা যাবে না।');
        }
        $feeStructure->delete();
        return back()->with('success', 'Fee structure মুছে ফেলা হয়েছে।');
    }
}
