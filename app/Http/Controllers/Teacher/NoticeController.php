<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::where('created_by', auth()->id())->latest()->paginate(15);
        return view('teacher.notices.index', compact('notices'));
    }

    public function create()
    {
        return view('teacher.notices.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'type'       => 'required|in:general,exam,holiday,urgent',
            'visible_to' => 'required|in:student,teacher',  // teachers cannot broadcast to 'all'
            'expires_at' => 'nullable|date|after:today',
        ]);
        $data['created_by'] = auth()->id();
        Notice::create($data);
        return redirect()->route('teacher.notices.index')->with('success', 'Notice publish হয়েছে।');
    }

    public function destroy(Notice $notice)
    {
        abort_if($notice->created_by !== auth()->id(), 403);
        $notice->delete();
        return back()->with('success', 'Notice মুছে ফেলা হয়েছে।');
    }
}
