<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::with(['author','batch','course'])->latest()->paginate(15);
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        $batches = Batch::where('status','active')->get();
        $courses = Course::where('status','active')->get();
        return view('admin.notices.form', compact('batches','courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'scope'      => 'required|in:universal,batch,course',
            'batch_id'   => 'nullable|exists:batches,id|required_if:scope,batch',
            'course_id'  => 'nullable|exists:courses,id|required_if:scope,course',
        ]);

        // Clear irrelevant scope targets
        if ($data['scope'] === 'universal') {
            $data['batch_id']  = null;
            $data['course_id'] = null;
        } elseif ($data['scope'] === 'batch') {
            $data['course_id'] = null;
        } elseif ($data['scope'] === 'course') {
            $data['batch_id']  = null;
        }
        $data['created_by']   = auth()->id();
        $data['is_published'] = true;
        $data['published_at'] = now();
        Notice::create($data);
        return redirect()->route('admin.notices.index')->with('success', 'Notice publish হয়েছে।');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return back()->with('success', 'Notice মুছে ফেলা হয়েছে।');
    }
}
