<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;

class PublicSurveyController extends Controller
{
    public function show(string $slug)
    {
        $survey = Survey::where('slug', $slug)
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('closes_at')->orWhere('closes_at', '>=', now()))
            ->with('questions')
            ->firstOrFail();

        return view('public.survey', compact('survey'));
    }

    public function submit(Request $request, string $slug)
    {
        $survey = Survey::where('slug', $slug)
            ->where('is_active', true)
            ->with('questions')
            ->firstOrFail();

        // Time window check
        if ($survey->closes_at && now()->gt($survey->closes_at)) {
            return back()->with('error', 'এই Survey-এর সময়সীমা শেষ হয়ে গেছে।');
        }

        // Duplicate submission guard for authenticated users
        if (auth()->check()) {
            $alreadySubmitted = SurveyResponse::where('survey_id', $survey->id)
                ->where('user_id', auth()->id())
                ->exists();
            if ($alreadySubmitted) {
                return back()->with('info', 'আপনি ইতিমধ্যে এই Survey সাবমিট করেছেন।');
            }
        }

        // Validate respondent details for guests
        if (auth()->guest()) {
            $request->validate([
                'respondent_name'  => 'required|string|max:255',
                'respondent_email' => 'required|email|max:255',
            ]);
        }

        $questions = $survey->questions->keyBy('question_key');
        $startQuestion = $survey->questions->firstWhere('is_start', true) ?: $survey->questions->first();
        
        if (!$startQuestion) {
            return back()->with('error', 'Survey has no questions.');
        }

        $currentKey = $startQuestion->question_key;
        $answers = [];
        $visited = [];

        // Validate and trace dynamic path
        while (!empty($currentKey) && $currentKey !== 'end' && isset($questions[$currentKey])) {
            if (in_array($currentKey, $visited)) {
                break; // prevent loop cycle
            }
            $visited[] = $currentKey;
            $q = $questions[$currentKey];
            
            $inputKey = "q_{$q->question_key}";

            // Run validation
            $request->validate([
                $inputKey => $q->required ? 'required' : 'nullable',
            ]);

            $val = $request->input($inputKey);
            $answers[$q->question_key] = $val;

            // Find next branching step
            $nextKey = null;
            if (in_array($q->type, ['select', 'radio']) && !empty($val) && is_array($q->options)) {
                foreach ($q->options as $opt) {
                    if (trim($opt['value']) === trim($val) && !empty($opt['next_question_key'])) {
                        $nextKey = $opt['next_question_key'];
                        break;
                    }
                }
            }

            if (empty($nextKey)) {
                $nextKey = $q->default_next_question_key;
            }

            $currentKey = $nextKey;
        }

        SurveyResponse::create([
            'survey_id'        => $survey->id,
            'user_id'          => auth()->id(),
            'answers'          => $answers,
            'respondent_name'  => $request->input('respondent_name'),
            'respondent_email' => $request->input('respondent_email'),
        ]);

        return back()->with('success', 'আপনার Survey Response সফলভাবে জমা হয়েছে।');
    }
}
