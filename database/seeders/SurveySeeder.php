<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Survey;
use App\Models\User;
use App\Models\SurveyResponse;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('admin')->first() ?: User::first();
        if (!$admin) return;

        $survey = Survey::firstOrCreate(
            ['slug' => 'iom-feedback-survey'],
            [
                'created_by'  => $admin->id,
                'title'       => 'Islamic Online Madrasah Feedback Survey',
                'description' => 'IOM-এ আপনার শিক্ষণ অভিজ্ঞতা কেমন তা আমাদের জানান। আপনার উত্তরের ওপর ভিত্তি করে পরবর্তী প্রশ্নগুলো পরিবর্তিত হবে।',
                'is_active'   => true,
            ]
        );

        // Clear existing questions to avoid duplicates
        $survey->questions()->delete();

        // 1. Start question: Enrollment
        $survey->questions()->create([
            'question_key'              => 'q1_enrollment',
            'label'                     => 'Are you currently enrolled in a course at IOM?',
            'type'                      => 'radio',
            'options'                   => [
                ['value' => 'Yes', 'next_question_key' => 'q2_course'],
                ['value' => 'No', 'next_question_key' => 'q4_interest']
            ],
            'is_start'                  => true,
            'required'                  => true,
            'order'                     => 1,
        ]);

        // 2. Course selection (Only if enrolled)
        $survey->questions()->create([
            'question_key'              => 'q2_course',
            'label'                     => 'Which course are you studying?',
            'type'                      => 'select',
            'options'                   => [
                ['value' => 'Alim Course', 'next_question_key' => 'q3_alim_feedback'],
                ['value' => 'Arabic Language Certification', 'next_question_key' => 'q3_arabic_feedback']
            ],
            'required'                  => true,
            'order'                     => 2,
        ]);

        // 3. Alim Feedback
        $survey->questions()->create([
            'question_key'              => 'q3_alim_feedback',
            'label'                     => 'How would you rate the Fiqh curriculum in the Alim Course?',
            'type'                      => 'radio',
            'options'                   => [
                ['value' => 'Excellent', 'next_question_key' => 'q_suggestions'],
                ['value' => 'Good', 'next_question_key' => 'q_suggestions'],
                ['value' => 'Needs Improvement', 'next_question_key' => 'q_suggestions']
            ],
            'default_next_question_key' => 'q_suggestions',
            'required'                  => true,
            'order'                     => 3,
        ]);

        // 3b. Arabic Feedback
        $survey->questions()->create([
            'question_key'              => 'q3_arabic_feedback',
            'label'                     => 'Do you find the Arabic grammar classes easy to follow?',
            'type'                      => 'radio',
            'options'                   => [
                ['value' => 'Yes, very easy', 'next_question_key' => 'q_suggestions'],
                ['value' => 'Sometimes challenging', 'next_question_key' => 'q_suggestions'],
                ['value' => 'Very difficult', 'next_question_key' => 'q_suggestions']
            ],
            'default_next_question_key' => 'q_suggestions',
            'required'                  => true,
            'order'                     => 4,
        ]);

        // 4. Non-enrolled interest
        $survey->questions()->create([
            'question_key'              => 'q4_interest',
            'label'                     => 'Are you interested in learning Islamic Fiqh or Arabic in the future?',
            'type'                      => 'radio',
            'options'                   => [
                ['value' => 'Yes, definitely', 'next_question_key' => 'q5_source'],
                ['value' => 'Maybe later', 'next_question_key' => 'q5_source'],
                ['value' => 'No', 'next_question_key' => 'end']
            ],
            'default_next_question_key' => 'q5_source',
            'required'                  => true,
            'order'                     => 5,
        ]);

        // 5. Non-enrolled source
        $survey->questions()->create([
            'question_key'              => 'q5_source',
            'label'                     => 'How did you hear about Islamic Online Madrasah?',
            'type'                      => 'select',
            'options'                   => [
                ['value' => 'Facebook/Social Media', 'next_question_key' => 'end'],
                ['value' => 'Friends/Family', 'next_question_key' => 'end'],
                ['value' => 'Web Search', 'next_question_key' => 'end']
            ],
            'default_next_question_key' => 'end',
            'required'                  => true,
            'order'                     => 6,
        ]);

        // 6. Suggestions (Shared path for enrolled)
        $survey->questions()->create([
            'question_key'              => 'q_suggestions',
            'label'                     => 'Please share any suggestions or comments to help us improve:',
            'type'                      => 'textarea',
            'default_next_question_key' => 'end',
            'required'                  => false,
            'order'                     => 7,
        ]);

        // Mock response 1 (Enrolled Student 1 path)
        $student1 = User::role('student')->first();
        if ($student1) {
            SurveyResponse::firstOrCreate(
                ['survey_id' => $survey->id, 'user_id' => $student1->id],
                [
                    'answers' => [
                        'q1_enrollment'    => 'Yes',
                        'q2_course'        => 'Alim Course',
                        'q3_alim_feedback' => 'Excellent',
                        'q_suggestions'    => 'The course materials are extremely clear and helpful!',
                    ]
                ]
            );
        }

        // Mock response 2 (Guest Path - not interested)
        SurveyResponse::firstOrCreate(
            ['survey_id' => $survey->id, 'respondent_email' => 'visitor1@gmail.com'],
            [
                'respondent_name'  => 'Imtiaz Ahmed',
                'respondent_email' => 'visitor1@gmail.com',
                'answers' => [
                    'q1_enrollment' => 'No',
                    'q4_interest'   => 'No',
                ]
            ]
        );

        // Mock response 3 (Guest Path - interested)
        SurveyResponse::firstOrCreate(
            ['survey_id' => $survey->id, 'respondent_email' => 'visitor2@gmail.com'],
            [
                'respondent_name'  => 'Rahat Kabir',
                'respondent_email' => 'visitor2@gmail.com',
                'answers' => [
                    'q1_enrollment' => 'No',
                    'q4_interest'   => 'Yes, definitely',
                    'q5_source'     => 'Facebook/Social Media',
                ]
            ]
        );
    }
}
