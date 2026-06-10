<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::with('creator')->withCount('responses')->latest()->paginate(15);
        return view('admin.surveys.index', compact('surveys'));
    }

    public function create()
    {
        return view('admin.surveys.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'                              => 'required|string|max:255',
            'description'                        => 'nullable|string',
            'closes_at'                          => 'nullable|date',
            'fields'                             => 'required|array|min:1',
            'fields.*.question_key'              => 'required|string',
            'fields.*.label'                     => 'required|string',
            'fields.*.type'                      => 'required|in:text,textarea,select,radio,checkbox,date,number',
            'fields.*.default_next_question_key' => 'nullable|string',
            'fields.*.options'                   => 'nullable|array',
        ]);

        // Validate question keys uniqueness and targets existence
        $validKeys = collect($request->fields)->map(fn($f) => \Illuminate\Support\Str::slug($f['question_key'], '_'))->toArray();
        if (collect($validKeys)->duplicates()->isNotEmpty()) {
            return back()->withInput()->withErrors(['fields' => 'সবগুলো Question Key অনন্য (Unique) হতে হবে।']);
        }

        $validKeys[] = 'end'; // Allow 'end' as a valid target

        foreach ($request->fields as $field) {
            // Check default next target
            if (!empty($field['default_next_question_key'])) {
                $target = \Illuminate\Support\Str::slug($field['default_next_question_key'], '_');
                if (!in_array($target, $validKeys)) {
                    return back()->withInput()->withErrors(['fields' => "Question Key '{$field['question_key']}' এর Default Next Target '{$field['default_next_question_key']}' সঠিক নয় (এটি কোনো প্রশ্ন কি বা 'end' হতে হবে)।"]);
                }
            }
            // Check options branching targets
            if (isset($field['options']) && is_array($field['options'])) {
                foreach ($field['options'] as $opt) {
                    if (!empty($opt['next_question_key'])) {
                        $target = \Illuminate\Support\Str::slug($opt['next_question_key'], '_');
                        if (!in_array($target, $validKeys)) {
                            return back()->withInput()->withErrors(['fields' => "Question Key '{$field['question_key']}' এর ব্রাঞ্চ অপশন '{$opt['value']}' এর Target Key '{$opt['next_question_key']}' সঠিক নয়।"]);
                        }
                    }
                }
            }
        }

        $survey = Survey::create([
            'created_by'  => auth()->id(),
            'title'       => $request->title,
            'description' => $request->description,
            'closes_at'   => $request->closes_at,
            'slug'        => Str::slug($request->title) . '-' . Str::random(6),
        ]);

        foreach ($request->fields as $index => $field) {
            $options = null;
            if (in_array($field['type'], ['select', 'radio', 'checkbox']) && isset($field['options'])) {
                // Filter empty options
                $options = array_values(array_filter($field['options'], function($opt) {
                    return !empty($opt['value']);
                }));
            }

            $survey->questions()->create([
                'question_key'              => Str::slug($field['question_key'], '_'),
                'label'                     => $field['label'],
                'type'                      => $field['type'],
                'options'                   => $options,
                'default_next_question_key' => !empty($field['default_next_question_key']) ? Str::slug($field['default_next_question_key'], '_') : null,
                'is_start'                  => ($index === 0) || (isset($field['is_start']) && $field['is_start'] == '1'),
                'required'                  => isset($field['required']) && $field['required'] == '1',
                'order'                     => $index + 1,
            ]);
        }

        return redirect()->route('admin.surveys.show', $survey)->with('success', 'Survey তৈরি হয়েছে।');
    }

    public function show(Survey $survey)
    {
        $survey->load(['questions', 'responses.user']);
        $shareLink = url('/surveys/' . $survey->slug);
        return view('admin.surveys.show', compact('survey', 'shareLink'));
    }

    public function edit(Survey $survey)
    {
        $survey->load('questions');
        return view('admin.surveys.form', compact('survey'));
    }

    public function update(Request $request, Survey $survey)
    {
        $request->validate([
            'title'                              => 'required|string|max:255',
            'description'                        => 'nullable|string',
            'closes_at'                          => 'nullable|date',
            'fields'                             => 'required|array|min:1',
            'fields.*.question_key'              => 'required|string',
            'fields.*.label'                     => 'required|string',
            'fields.*.type'                      => 'required|in:text,textarea,select,radio,checkbox,date,number',
            'fields.*.default_next_question_key' => 'nullable|string',
            'fields.*.options'                   => 'nullable|array',
        ]);

        // Validate question keys uniqueness and targets existence
        $validKeys = collect($request->fields)->map(fn($f) => \Illuminate\Support\Str::slug($f['question_key'], '_'))->toArray();
        if (collect($validKeys)->duplicates()->isNotEmpty()) {
            return back()->withInput()->withErrors(['fields' => 'সবগুলো Question Key অনন্য (Unique) হতে হবে।']);
        }

        $validKeys[] = 'end'; // Allow 'end' as a valid target

        foreach ($request->fields as $field) {
            // Check default next target
            if (!empty($field['default_next_question_key'])) {
                $target = \Illuminate\Support\Str::slug($field['default_next_question_key'], '_');
                if (!in_array($target, $validKeys)) {
                    return back()->withInput()->withErrors(['fields' => "Question Key '{$field['question_key']}' এর Default Next Target '{$field['default_next_question_key']}' সঠিক নয় (এটি কোনো প্রশ্ন কি বা 'end' হতে হবে)।"]);
                }
            }
            // Check options branching targets
            if (isset($field['options']) && is_array($field['options'])) {
                foreach ($field['options'] as $opt) {
                    if (!empty($opt['next_question_key'])) {
                        $target = \Illuminate\Support\Str::slug($opt['next_question_key'], '_');
                        if (!in_array($target, $validKeys)) {
                            return back()->withInput()->withErrors(['fields' => "Question Key '{$field['question_key']}' এর ব্রাঞ্চ অপশন '{$opt['value']}' এর Target Key '{$opt['next_question_key']}' সঠিক নয়।"]);
                        }
                    }
                }
            }
        }

        $survey->update([
            'title'       => $request->title,
            'description' => $request->description,
            'closes_at'   => $request->closes_at,
        ]);

        // Recreate fields to keep dynamic ordering and mapping clean
        $survey->questions()->delete();

        foreach ($request->fields as $index => $field) {
            $options = null;
            if (in_array($field['type'], ['select', 'radio', 'checkbox']) && isset($field['options'])) {
                $options = array_values(array_filter($field['options'], function($opt) {
                    return !empty($opt['value']);
                }));
            }

            $survey->questions()->create([
                'question_key'              => Str::slug($field['question_key'], '_'),
                'label'                     => $field['label'],
                'type'                      => $field['type'],
                'options'                   => $options,
                'default_next_question_key' => !empty($field['default_next_question_key']) ? Str::slug($field['default_next_question_key'], '_') : null,
                'is_start'                  => ($index === 0) || (isset($field['is_start']) && $field['is_start'] == '1'),
                'required'                  => isset($field['required']) && $field['required'] == '1',
                'order'                     => $index + 1,
            ]);
        }

        return redirect()->route('admin.surveys.show', $survey)->with('success', 'Survey আপডেট হয়েছে।');
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();
        return redirect()->route('admin.surveys.index')->with('success', 'Survey মুছে ফেলা হয়েছে।');
    }

    public function responses(Survey $survey)
    {
        $responses = $survey->responses()->with('user')->latest()->paginate(20);
        return view('admin.surveys.responses', compact('survey', 'responses'));
    }

    public function toggle(Survey $survey)
    {
        $survey->update(['is_active' => !$survey->is_active]);
        $status = $survey->is_active ? 'active' : 'inactive';
        return back()->with('success', "Survey {$status} করা হয়েছে।");
    }
}
