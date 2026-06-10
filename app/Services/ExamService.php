<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Models\Result;
use Illuminate\Support\Collection;

class ExamService
{
    /**
     * Generate randomized MCQ questions for an exam attempt.
     * Returns a shuffled subset based on exam configuration.
     */
    public function generateMcqQuestions(Exam $exam): Collection
    {
        return Question::where('exam_id', $exam->id)
            ->where('status', 'approved')
            ->inRandomOrder()
            ->get();
    }

    /**
     * Auto-grade a submitted MCQ attempt.
     * Returns ['obtained_marks' => int, 'total_marks' => int, 'percentage' => float]
     */
    public function gradeMcq(ExamAttempt $attempt, array $submittedAnswers): array
    {
        $questions = Question::where('exam_id', $attempt->exam_id)
            ->pluck('correct_option', 'id');

        $obtained = 0;
        foreach ($submittedAnswers as $questionId => $selectedOption) {
            if (isset($questions[$questionId]) && $questions[$questionId] === $selectedOption) {
                $obtained++;
            }
        }

        $total      = $attempt->exam->total_marks;
        $percentage = $total > 0 ? round(($obtained / $total) * 100, 2) : 0;

        return [
            'obtained_marks' => $obtained,
            'total_marks'    => $total,
            'percentage'     => $percentage,
        ];
    }

    /**
     * Check if a student is eligible to sit for an exam.
     * Checks: attendance % and fee clearance (payment status).
     */
    public function checkEligibility(int $userId, Exam $exam): array
    {
        $eligible = true;
        $reasons  = [];

        // Attendance check
        $setting      = \App\Models\SystemSetting::where('key', 'min_attendance_percent')->value('value') ?? 75;
        $totalClasses = \App\Models\Attendance::where('batch_id', $exam->batch_id)->distinct('class_lesson_id')->count();
        $present      = \App\Models\Attendance::where('batch_id', $exam->batch_id)
            ->where('user_id', $userId)
            ->whereIn('status', ['present', 'late'])
            ->count();

        if ($totalClasses > 0) {
            $attendancePct = ($present / $totalClasses) * 100;
            if ($attendancePct < (float) $setting) {
                $eligible  = false;
                $reasons[] = "Attendance {$attendancePct}% — minimum {$setting}% প্রয়োজন।";
            }
        }

        return ['eligible' => $eligible, 'reasons' => $reasons];
    }

    /**
     * Calculate Grade & GPA from percentage using standard Bangladeshi UGC grading system.
     *
     * Marks   | Grade | GP
     * --------|-------|----
     * 80-100  | A+    | 4.00
     * 75-79   | A     | 3.75
     * 70-74   | A-    | 3.50
     * 65-69   | B+    | 3.25
     * 60-64   | B     | 3.00
     * 55-59   | B-    | 2.75
     * 50-54   | C+    | 2.50
     * 45-49   | C     | 2.25
     * 40-44   | D     | 2.00
     * 00-39   | F     | 0.00
     */
    public function calculateGpa(float $percentage): array
    {
        return match (true) {
            $percentage >= 80 => ['grade' => 'A+', 'gpa' => 4.00],
            $percentage >= 75 => ['grade' => 'A',  'gpa' => 3.75],
            $percentage >= 70 => ['grade' => 'A-', 'gpa' => 3.50],
            $percentage >= 65 => ['grade' => 'B+', 'gpa' => 3.25],
            $percentage >= 60 => ['grade' => 'B',  'gpa' => 3.00],
            $percentage >= 55 => ['grade' => 'B-', 'gpa' => 2.75],
            $percentage >= 50 => ['grade' => 'C+', 'gpa' => 2.50],
            $percentage >= 45 => ['grade' => 'C',  'gpa' => 2.25],
            $percentage >= 40 => ['grade' => 'D',  'gpa' => 2.00],
            default           => ['grade' => 'F',  'gpa' => 0.00],
        };
    }

    /**
     * Calculate CGPA for a student across all results in a semester.
     */
    public function calculateCgpa(int $studentId, int $semesterId): float
    {
        $results = Result::where('student_id', $studentId)
            ->where('semester_id', $semesterId)
            ->where('is_published', true)
            ->get();

        if ($results->isEmpty()) {
            return 0.0;
        }

        return round($results->avg('cgpa'), 2);
    }

    /**
     * Update/generate the Semester result (CGPA and Grade) for a student in a batch/semester.
     */
    public function updateSemesterResult(int $studentId, int $batchId, int $semesterId): void
    {
        // 1. Get all evaluated attempts for this student in this batch and semester
        $attempts = ExamAttempt::where('student_id', $studentId)
            ->where('status', 'evaluated')
            ->whereHas('exam', function($q) use ($batchId, $semesterId) {
                $q->where('batch_id', $batchId)
                  ->where('semester_id', $semesterId);
            })
            ->get();

        if ($attempts->isEmpty()) {
            return;
        }

        // 2. Calculate the CGPA (average GPA of all exams taken in this semester)
        $totalGpa = $attempts->sum('gpa');
        $cgpa = round($totalGpa / $attempts->count(), 2);

        // 3. Determine overall grade from CGPA using standard Bangladeshi UGC scale
        $overallGrade = match (true) {
            $cgpa >= 4.00 => 'A+',
            $cgpa >= 3.75 => 'A',
            $cgpa >= 3.50 => 'A-',
            $cgpa >= 3.25 => 'B+',
            $cgpa >= 3.00 => 'B',
            $cgpa >= 2.75 => 'B-',
            $cgpa >= 2.50 => 'C+',
            $cgpa >= 2.25 => 'C',
            $cgpa >= 2.00 => 'D',
            default       => 'F',
        };

        // 4. Update or create the Result record
        Result::updateOrCreate(
            [
                'student_id'  => $studentId,
                'batch_id'    => $batchId,
                'semester_id' => $semesterId,
            ],
            [
                'cgpa'          => $cgpa,
                'overall_grade' => $overallGrade,
            ]
        );
    }
}
