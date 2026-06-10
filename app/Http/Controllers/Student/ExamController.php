<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $batchIds = $user->studentBatches()->where('status','active')->pluck('batch_id');
        $exams    = Exam::whereIn('batch_id', $batchIds)->where('status','published')->with('subject')->get();
        $attempts = ExamAttempt::where('student_id', $user->id)->pluck('exam_id')->toArray();
        return view('student.exams.index', compact('exams','attempts'));
    }

    public function start(Request $request, Exam $exam)
    {
        $user = auth()->user();
        $batchIds = $user->studentBatches()->where('status','active')->pluck('batch_id')->toArray();
        abort_if(!in_array($exam->batch_id, $batchIds), 403, 'You are not enrolled in the batch for this exam.');

        // Verify Attendance-based Exam Eligibility (ATT-07)
        $totalClasses = \App\Models\ClassLesson::where('batch_id', $exam->batch_id)
            ->where('is_published', true)
            ->count();
        $attendedClasses = \App\Models\Attendance::where('batch_id', $exam->batch_id)
            ->where('student_id', $user->id)
            ->whereIn('status', ['present', 'late'])
            ->count();
        $attendancePct = $totalClasses > 0 ? ($attendedClasses / $totalClasses) * 100 : 100;
        $minAttendance = (float)\App\Models\SystemSetting::get('min_attendance', 75);
        
        abort_if($attendancePct < $minAttendance, 403, "পরীক্ষায় অংশগ্রহণের জন্য ন্যূনতম {$minAttendance}% উপস্থিতি প্রয়োজন। আপনার বর্তমান উপস্থিতি: " . round($attendancePct, 1) . "%।");

        // Enforce exam time window
        $now = now();
        if ($exam->start_at && $now->lt($exam->start_at)) {
            abort(403, 'পরীক্ষা এখনো শুরু হয়নি। শুরু হবে: ' . $exam->start_at->format('d M Y, h:i A') . '।');
        }
        if ($exam->end_at && $now->gt($exam->end_at)) {
            abort(403, 'পরীক্ষার সময় শেষ হয়ে গেছে।');
        }

        // Allow resuming in_progress attempts
        $attempt = ExamAttempt::where(['exam_id' => $exam->id, 'student_id' => $user->id])->first();

        if ($attempt) {
            if ($attempt->status !== 'in_progress') {
                abort(403, 'Already attempted.');
            }
        } else {
            $attempt = ExamAttempt::create([
                'exam_id'    => $exam->id,
                'student_id' => $user->id,
                'started_at' => now(),
                'status'     => 'in_progress',
            ]);
        }

        // Calculate remaining seconds
        $secondsRemaining = $exam->duration_minutes ? ($exam->duration_minutes * 60) - now()->diffInSeconds($attempt->started_at) : null;
        if ($secondsRemaining !== null && $secondsRemaining < 0) {
            $secondsRemaining = 0;
        }

        // If time is already up, auto-submit with 0 marks
        if ($secondsRemaining !== null && $secondsRemaining === 0) {
            $attempt->update([
                'submitted_at'   => $attempt->started_at->addMinutes($exam->duration_minutes),
                'status'         => 'evaluated',
                'obtained_marks' => 0,
                'percentage'     => 0,
                'grade'          => 'F',
                'gpa'            => 0.00,
            ]);

            // Sync semester results
            $semesterId = $exam->semester_id ?: $exam->batch->semester_id;
            if ($semesterId) {
                app(\App\Services\ExamService::class)->updateSemesterResult($user->id, $exam->batch_id, $semesterId);
            }

            return redirect()->route('student.results.index')->with('error', 'পরীক্ষার সময় শেষ হয়ে গেছে।');
        }

        $questions = Question::where('exam_id', $exam->id)
            ->where('status','approved')
            ->inRandomOrder()->get();

        return view('student.exams.take', compact('exam','attempt','questions', 'secondsRemaining'));
    }

    public function submit(Request $request, Exam $exam)
    {
        $user = auth()->user();
        $batchIds = $user->studentBatches()->where('status','active')->pluck('batch_id')->toArray();
        abort_if(!in_array($exam->batch_id, $batchIds), 403, 'You are not enrolled in the batch for this exam.');

        $attempt = ExamAttempt::where(['exam_id'=>$exam->id,'student_id'=>$user->id])->firstOrFail();
        abort_if($attempt->status !== 'in_progress', 403);

        // Enforce duration limit — auto-fail if submitted after time is up
        if ($exam->duration_minutes) {
            $deadline = $attempt->started_at->addMinutes($exam->duration_minutes);
            if (now()->gt($deadline->addSeconds(10))) { // 10-second grace period for network latency
                $attempt->update([
                    'submitted_at'   => $deadline,
                    'status'         => 'evaluated',
                    'obtained_marks' => 0,
                    'percentage'     => 0,
                    'grade'          => 'F',
                    'gpa'            => 0.00
                ]);

                $semesterId = $exam->semester_id ?: $exam->batch->semester_id;
                if ($semesterId) {
                    app(\App\Services\ExamService::class)->updateSemesterResult($user->id, $exam->batch_id, $semesterId);
                }

                return redirect()->route('student.results.index')->with('error', 'পরীক্ষার সময় শেষ হয়ে গেছে। স্বয়ংক্রিয়ভাবে জমা দেওয়া হয়েছে।');
            }
        }

        $answers = $request->input('answers', []);
        $correct = 0;

        foreach ($answers as $questionId => $selected) {
            $question = Question::find($questionId);
            if (!$question) continue;
            // Security check: Ensure question belongs to this exam
            if ((int)$question->exam_id !== (int)$exam->id) continue;

            $isCorrect = ($question->correct_answer === $selected);
            if ($isCorrect) $correct += $question->marks;

            ExamAnswer::create([
                'exam_attempt_id' => $attempt->id,
                'question_id'     => $questionId,
                'selected_answer' => $selected,
                'is_correct'      => $isCorrect,
            ]);
        }

        // Percentage out of the actual exam's total marks, not just answered questions
        $totalMarks = $exam->total_marks > 0 ? $exam->total_marks : 1;
        $percentage = round(($correct / $totalMarks) * 100, 2);
        if ($percentage > 100) $percentage = 100.00;

        $grade = $this->calcGrade($percentage);
        $gpa   = $this->calcGpa($grade);

        $attempt->update([
            'submitted_at'   => now(),
            'obtained_marks' => $correct,
            'percentage'     => $percentage,
            'grade'          => $grade,
            'gpa'            => $gpa,
            'status'         => 'evaluated',
        ]);

        // Sync semester results
        $semesterId = $exam->semester_id ?: $exam->batch->semester_id;
        if ($semesterId) {
            app(\App\Services\ExamService::class)->updateSemesterResult($user->id, $exam->batch_id, $semesterId);
        }

        return redirect()->route('student.results.index')->with('success', "Exam জমা হয়েছে! Grade: {$grade}");
    }

    /**
     * Calculate grade letter from percentage using Bangladeshi UGC standard.
     * Thresholds are configurable via System Settings (stored as mark percentages).
     *
     * Default BD UGC thresholds:
     *   A+ ≥ 80 | A ≥ 75 | A- ≥ 70 | B+ ≥ 65 | B ≥ 60 | B- ≥ 55
     *   C+ ≥ 50 | C  ≥ 45 | D  ≥ 40 | F < 40
     */
    private function calcGrade(float $pct): string
    {
        $aPlus  = (int) \App\Models\SystemSetting::get('gpa_a_plus',  80);
        $a      = (int) \App\Models\SystemSetting::get('gpa_a',       75);
        $aMinus = (int) \App\Models\SystemSetting::get('gpa_a_minus',  70);
        $bPlus  = (int) \App\Models\SystemSetting::get('gpa_b_plus',   65);
        $b      = (int) \App\Models\SystemSetting::get('gpa_b',        60);
        $bMinus = (int) \App\Models\SystemSetting::get('gpa_b_minus',  55);
        $cPlus  = (int) \App\Models\SystemSetting::get('gpa_c_plus',   50);
        $c      = (int) \App\Models\SystemSetting::get('gpa_c',        45);
        $d      = (int) \App\Models\SystemSetting::get('gpa_d',        40);

        return match (true) {
            $pct >= $aPlus  => 'A+',
            $pct >= $a      => 'A',
            $pct >= $aMinus => 'A-',
            $pct >= $bPlus  => 'B+',
            $pct >= $b      => 'B',
            $pct >= $bMinus => 'B-',
            $pct >= $cPlus  => 'C+',
            $pct >= $c      => 'C',
            $pct >= $d      => 'D',
            default         => 'F',
        };
    }

    /**
     * Map grade letter to GPA point (Bangladeshi UGC 4.0 scale).
     */
    private function calcGpa(string $grade): float
    {
        return match ($grade) {
            'A+'    => 4.00,
            'A'     => 3.75,
            'A-'    => 3.50,
            'B+'    => 3.25,
            'B'     => 3.00,
            'B-'    => 2.75,
            'C+'    => 2.50,
            'C'     => 2.25,
            'D'     => 2.00,
            default => 0.00,   // F
        };
    }
}
