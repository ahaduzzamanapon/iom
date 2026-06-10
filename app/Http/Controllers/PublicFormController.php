<?php

// Public form submission controller (no auth required)

namespace App\Http\Controllers;

use App\Models\CustomForm;
use App\Models\FormResponse;
use Illuminate\Http\Request;

class PublicFormController extends Controller
{
    public function show(string $slug)
    {
        $form = CustomForm::where('slug', $slug)
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('closes_at')->orWhere('closes_at', '>=', now()))
            ->with('fields')
            ->firstOrFail();

        return view('public.form', compact('form'));
    }

    public function submit(Request $request, string $slug)
    {
        $form = CustomForm::where('slug', $slug)
            ->where('is_active', true)
            ->with('fields')
            ->firstOrFail();

        // Prevent submission after closing date (POST-bypass guard)
        if ($form->closes_at && now()->gt($form->closes_at)) {
            return back()->with('error', 'এই formের সময়সীমা শেষ হয়ে গেছে, আর submit করা যাবে না।');
        }

        // Prevent duplicate submission from the same authenticated user
        if (auth()->check()) {
            $alreadySubmitted = FormResponse::where('custom_form_id', $form->id)
                ->where('user_id', auth()->id())
                ->exists();
            if ($alreadySubmitted) {
                return back()->with('info', 'আপনি ইতিমধ্যে এই form submit করেছেন।');
            }
        }

        // Build dynamic validation rules
        $rules = [];
        foreach ($form->fields as $field) {
            $rule = $field->required ? ['required'] : ['nullable'];
            if ($field->type === 'file') {
                $rule[] = 'file|max:5120';
            }
            $rules["field_{$field->id}"] = $rule;
        }

        $validated = $request->validate($rules);

        // Build answers array
        $answers = [];
        foreach ($form->fields as $field) {
            $key = "field_{$field->id}";
            if ($field->type === 'file' && $request->hasFile($key)) {
                $answers[$field->id] = $request->file($key)->store('form-uploads', 'public');
            } else {
                $answers[$field->id] = $request->input($key);
            }
        }

        FormResponse::create([
            'custom_form_id'    => $form->id,
            'user_id'           => auth()->id(),
            'answers'           => $answers,
            'respondent_name'   => $request->input('respondent_name'),
            'respondent_email'  => $request->input('respondent_email'),
        ]);

        return back()->with('success', 'আপনার response জমা হয়েছে।');
    }
}
