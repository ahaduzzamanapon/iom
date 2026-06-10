<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with(['exam','subject','creator'])
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->latest()->paginate(15);
        return view('admin.questions.index', compact('questions'));
    }

    public function approve(Question $question)
    {
        $question->update(['status' => 'approved']);
        return back()->with('success', 'Question approve হয়েছে।');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Question মুছে ফেলা হয়েছে।');
    }
}
