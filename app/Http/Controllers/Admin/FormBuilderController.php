<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use App\Models\FormField;
use App\Models\FormResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{
    public function index()
    {
        $forms = CustomForm::with('creator')->withCount('responses')->latest()->paginate(15);
        return view('admin.forms.index', compact('forms'));
    }

    public function create()
    {
        return view('admin.forms.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'closes_at'     => 'nullable|date',
            'fields'        => 'required|array|min:1',
            'fields.*.label'=> 'required|string',
            'fields.*.type' => 'required|in:text,textarea,select,radio,checkbox,file,date,number',
        ]);

        $form = CustomForm::create([
            'created_by'  => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'closes_at'   => $request->closes_at,
            'slug'        => Str::slug($request->title) . '-' . Str::random(6),
        ]);

        foreach ($request->fields as $index => $field) {
            $form->fields()->create([
                'label'    => $field['label'],
                'type'     => $field['type'],
                'options'  => isset($field['options']) ? array_filter(explode(',', $field['options'])) : null,
                'required' => isset($field['required']),
                'order'    => $index + 1,
            ]);
        }

        return redirect()->route('admin.forms.show', $form)->with('success', 'Form তৈরি হয়েছে।');
    }

    public function show(CustomForm $form)
    {
        $form->load(['fields', 'responses.user']);
        $shareLink = url('/forms/' . $form->slug);
        return view('admin.forms.show', compact('form', 'shareLink'));
    }

    public function edit(CustomForm $form)
    {
        $form->load('fields');
        return view('admin.forms.form', compact('form'));
    }

    public function update(Request $request, CustomForm $form)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'closes_at'   => 'nullable|date',
            'is_active'   => 'nullable|boolean',
        ]);

        $form->update($request->only('title', 'description', 'closes_at', 'is_active'));
        return back()->with('success', 'Form আপডেট হয়েছে।');
    }

    public function destroy(CustomForm $form)
    {
        $form->delete();
        return redirect()->route('admin.forms.index')->with('success', 'Form মুছে ফেলা হয়েছে।');
    }

    public function responses(CustomForm $form)
    {
        $responses = $form->responses()->with('user')->latest()->paginate(20);
        return view('admin.forms.responses', compact('form', 'responses'));
    }

    // Toggle active status
    public function toggle(CustomForm $form)
    {
        $form->update(['is_active' => ! $form->is_active]);
        $status = $form->is_active ? 'active' : 'inactive';
        return back()->with('success', "Form {$status} করা হয়েছে।");
    }
}
