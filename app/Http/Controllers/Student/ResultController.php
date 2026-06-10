<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Result;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::where('student_id', auth()->id())
            ->where('is_published', true)
            ->with(['semester', 'batch'])
            ->latest()->get();
        return view('student.results.index', compact('results'));
    }
}
