<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Subject;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::with('subject.course')->withCount('classLessons')->latest()->paginate(15);
        return view('admin.modules.index', compact('modules'));
    }

    public function create()
    {
        $subjects = Subject::with('course')->where('status','active')->get();
        return view('admin.modules.form', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id'  => 'required|exists:subjects,id',
            'name'        => 'required|string|max:255',
            'name_bn'     => 'nullable|string|max:255',
            'order'       => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);
        Module::create($data);
        return redirect()->route('admin.modules.index')->with('success', 'Module তৈরি হয়েছে।');
    }

    public function edit(Module $module)
    {
        $subjects = Subject::with('course')->where('status','active')->get();
        return view('admin.modules.form', compact('module','subjects'));
    }

    public function update(Request $request, Module $module)
    {
        $data = $request->validate([
            'subject_id'  => 'required|exists:subjects,id',
            'name'        => 'required|string|max:255',
            'name_bn'     => 'nullable|string|max:255',
            'order'       => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);
        $module->update($data);
        return redirect()->route('admin.modules.index')->with('success', 'Module আপডেট হয়েছে।');
    }

    public function destroy(Module $module)
    {
        if ($module->classLessons()->count() > 0) {
            return back()->with('error', 'এই Moduleে ' . $module->classLessons()->count() . 'টি Class Lesson আছে। আগে Lessons মুছুন, তারপর Module মুছুন।');
        }
        $module->delete();
        return redirect()->route('admin.modules.index')->with('success', 'Module মুছে ফেলা হয়েছে।');
    }
}
