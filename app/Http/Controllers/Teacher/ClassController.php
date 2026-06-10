<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassLesson;
use App\Models\Module;
use App\Models\Batch;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    private function myBatchIds(): array
    {
        return Batch::whereHas('subjects', fn($q) =>
            $q->where('batch_subjects.teacher_id', auth()->id())
        )->pluck('id')->toArray();
    }

    private function mySubjectIds(): array
    {
        return \DB::table('batch_subjects')
            ->where('teacher_id', auth()->id())
            ->pluck('subject_id')
            ->unique()
            ->toArray();
    }

    public function index()
    {
        $classes = ClassLesson::whereIn('batch_id', $this->myBatchIds())
            ->with(['module.subject', 'batch'])
            ->latest()
            ->paginate(15);
        return view('teacher.classes.index', compact('classes'));
    }

    public function create()
    {
        $batches = Batch::whereIn('id', $this->myBatchIds())->where('status', 'active')->with('course')->get();
        $modules = Module::whereIn('subject_id', $this->mySubjectIds())->where('status', 'active')->with('subject')->get();
        return view('teacher.classes.form', compact('batches', 'modules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'batch_id'      => 'required|exists:batches,id',
            'module_id'     => 'required|exists:modules,id',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'video_url'     => 'nullable|url|max:500',
            'meeting_link'  => 'nullable|url|max:500',
            'scheduled_at'  => 'nullable|date',
            'duration_mins' => 'nullable|integer|min:1',
            'type'          => 'required|in:recorded,live,text',
        ]);

        $module = Module::findOrFail($data['module_id']);
        abort_if(!in_array($data['batch_id'], $this->myBatchIds()), 403, 'You are not assigned to this batch.');
        abort_if(!in_array($module->subject_id, $this->mySubjectIds()), 403, 'You are not assigned to teach this subject.');

        $data['created_by'] = auth()->id();
        ClassLesson::create($data);
        return redirect()->route('teacher.classes.index')->with('success', 'Class তৈরি হয়েছে।');
    }

    public function destroy(ClassLesson $class)
    {
        abort_if($class->created_by !== auth()->id(), 403, 'Permission denied.');
        $class->delete();
        return back()->with('success', 'Class মুছে ফেলা হয়েছে।');
    }
}
