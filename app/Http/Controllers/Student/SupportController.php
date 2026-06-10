<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('user_id', auth()->id())->latest()->paginate(10);
        return view('student.support.index', compact('tickets'));
    }

    public function create() { return view('student.support.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'nullable|string|max:100',
        ]);
        $data['user_id'] = auth()->id();
        $data['type']    = 'student';
        SupportTicket::create($data);
        return redirect()->route('student.support.index')->with('success', 'Ticket submit হয়েছে।');
    }

    public function show(SupportTicket $support)
    {
        abort_if($support->user_id !== auth()->id(), 403);
        return view('student.support.show', compact('support'));
    }

    // Public form
    public function publicForm() { return view('support'); }

    public function publicStore(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        $data['type'] = 'public';
        SupportTicket::create($data);
        return back()->with('success', 'আপনার message পাঠানো হয়েছে।');
    }
}
