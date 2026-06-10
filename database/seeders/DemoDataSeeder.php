<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Module;
use App\Models\ClassLesson;
use App\Models\StudentProfile;
use App\Models\StudentBatch;
use App\Models\TeacherProfile;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Question;
use App\Models\FeeStructure;
use App\Models\Payment;
use App\Models\Notice;
use App\Models\SupportTicket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Roles exist
        $roles = ['admin', 'teacher', 'student', 'staff'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // 2. Create Teachers
        $teacher1User = User::firstOrCreate(
            ['email' => 'teacher1@iom.edu'],
            [
                'name' => 'Mufti Abdur Rahman',
                'password' => Hash::make('teacher12345'),
            ]
        );
        $teacher1User->assignRole('teacher');

        TeacherProfile::firstOrCreate(
            ['user_id' => $teacher1User->id],
            [
                'teacher_id' => 'T1001',
                'phone' => '01711111111',
                'qualification' => 'Mufti, Kamil (Hadith)',
                'specialization' => 'Fiqh & Hadith',
                'status' => 'active',
            ]
        );

        $teacher2User = User::firstOrCreate(
            ['email' => 'teacher2@iom.edu'],
            [
                'name' => 'Dr. Abu Bakar Mohammad',
                'password' => Hash::make('teacher12345'),
            ]
        );
        $teacher2User->assignRole('teacher');

        TeacherProfile::firstOrCreate(
            ['user_id' => $teacher2User->id],
            [
                'teacher_id' => 'T1002',
                'phone' => '01722222222',
                'qualification' => 'PhD in Islamic Studies, Medina University',
                'specialization' => 'Tafsir & Arabic Language',
                'status' => 'active',
            ]
        );

        // 3. Create Students
        $student1User = User::firstOrCreate(
            ['email' => 'student1@iom.edu'],
            [
                'name' => 'Md. Abdullah',
                'password' => Hash::make('student12345'),
            ]
        );
        $student1User->assignRole('student');

        StudentProfile::firstOrCreate(
            ['user_id' => $student1User->id],
            [
                'student_id' => 'S2026001',
                'phone' => '01811111111',
                'date_of_birth' => '2002-05-12',
                'gender' => 'male',
                'address' => 'Mirpur, Dhaka',
                'guardian_name' => 'Md. Abdus Samad',
                'guardian_phone' => '01822222222',
                'guardian_relation' => 'Father',
                'status' => 'active',
            ]
        );

        $student2User = User::firstOrCreate(
            ['email' => 'student2@iom.edu'],
            [
                'name' => 'Fatema Khatun',
                'password' => Hash::make('student12345'),
            ]
        );
        $student2User->assignRole('student');

        StudentProfile::firstOrCreate(
            ['user_id' => $student2User->id],
            [
                'student_id' => 'S2026002',
                'phone' => '01911111111',
                'date_of_birth' => '2004-10-20',
                'gender' => 'female',
                'address' => 'Uttara, Dhaka',
                'guardian_name' => 'Amina Begum',
                'guardian_phone' => '01922222222',
                'guardian_relation' => 'Mother',
                'status' => 'active',
            ]
        );

        // 4. Create Courses
        $courseAlim = Course::firstOrCreate(
            ['name' => 'Alim Course'],
            [
                'name_bn' => 'আলিম কোর্স',
                'type' => 'long_term',
                'duration_years' => 3,
                'description' => 'Complete academic curriculum for Alim level studies covering Fiqh, Quran, Hadith, and Arabic Grammar.',
                'status' => 'active',
            ]
        );

        $courseArabic = Course::firstOrCreate(
            ['name' => 'Arabic Language Certification'],
            [
                'name_bn' => 'আরবি ভাষা শিক্ষা সার্টিফিকেট কোর্স',
                'type' => 'short_term',
                'duration_years' => 1,
                'description' => 'Learn Basic to Advanced Arabic Speaking, Listening, Writing, and Quranic Arabic Grammar.',
                'status' => 'active',
            ]
        );

        // 5. Create Semesters
        $semesterAlim1 = Semester::firstOrCreate(
            ['name' => 'Alim Semester 1', 'course_id' => $courseAlim->id],
            [
                'name_bn' => 'আলিম ১ম সেমিস্টার',
                'order' => 1,
                'start_date' => '2026-01-01',
                'end_date' => '2026-06-30',
                'status' => 'active',
            ]
        );

        $semesterArabic1 = Semester::firstOrCreate(
            ['name' => 'Arabic Semester 1', 'course_id' => $courseArabic->id],
            [
                'name_bn' => 'আরবি ১ম সেমিস্টার',
                'order' => 1,
                'start_date' => '2026-01-01',
                'end_date' => '2026-06-30',
                'status' => 'active',
            ]
        );

        // 6. Create Batches
        $batchAlim = Batch::firstOrCreate(
            ['name' => 'Alim Batch 01', 'course_id' => $courseAlim->id],
            [
                'name_bn' => 'আলিম ব্যাচ ০১',
                'semester_id' => $semesterAlim1->id,
                'capacity' => 30,
                'start_date' => '2026-01-10',
                'end_date' => '2026-06-25',
                'status' => 'active',
            ]
        );

        $batchArabic = Batch::firstOrCreate(
            ['name' => 'Arabic Batch 01', 'course_id' => $courseArabic->id],
            [
                'name_bn' => 'আরবি ব্যাচ ০১',
                'semester_id' => $semesterArabic1->id,
                'capacity' => 25,
                'start_date' => '2026-01-15',
                'end_date' => '2026-06-20',
                'status' => 'active',
            ]
        );

        // Enroll students in batches
        StudentBatch::firstOrCreate(
            ['user_id' => $student1User->id, 'batch_id' => $batchAlim->id],
            ['enrolled_at' => now(), 'status' => 'active']
        );

        StudentBatch::firstOrCreate(
            ['user_id' => $student2User->id, 'batch_id' => $batchArabic->id],
            ['enrolled_at' => now(), 'status' => 'active']
        );

        // 7. Create Subjects
        $subjectFiqh = Subject::firstOrCreate(
            ['name' => 'Fiqhul Islam', 'course_id' => $courseAlim->id],
            [
                'name_bn' => 'ইসলামি ফিকহ',
                'code' => 'AL-101',
                'credit_hours' => 3,
                'description' => 'Principles and details of Islamic Jurisprudence.',
                'status' => 'active',
            ]
        );

        $subjectTafsir = Subject::firstOrCreate(
            ['name' => 'Tafsirul Quran', 'course_id' => $courseAlim->id],
            [
                'name_bn' => 'তাফসীরুল কুরআন',
                'code' => 'AL-102',
                'credit_hours' => 3,
                'description' => 'Detailed translation and explanation of Quranic Surahs.',
                'status' => 'active',
            ]
        );

        $subjectArabicGrammar = Subject::firstOrCreate(
            ['name' => 'Quranic Arabic Grammar', 'course_id' => $courseArabic->id],
            [
                'name_bn' => 'কুরআনিক আরবি ব্যাকরণ',
                'code' => 'AR-101',
                'credit_hours' => 4,
                'description' => 'Basic grammar rules of Quranic Arabic.',
                'status' => 'active',
            ]
        );

        // Assign subjects to batches (pivot table batch_subjects)
        $batchAlim->subjects()->syncWithoutDetaching([
            $subjectFiqh->id => ['teacher_id' => $teacher1User->id],
            $subjectTafsir->id => ['teacher_id' => $teacher2User->id],
        ]);

        $batchArabic->subjects()->syncWithoutDetaching([
            $subjectArabicGrammar->id => ['teacher_id' => $teacher2User->id],
        ]);

        // 8. Create Modules
        $moduleFiqh1 = Module::firstOrCreate(
            ['name' => 'Taharah (Purity)', 'subject_id' => $subjectFiqh->id],
            [
                'name_bn' => 'পবিত্রতা অধ্যায়',
                'order' => 1,
                'description' => 'Rules of Wudu, Ghusl, and purification methods.',
            ]
        );

        $moduleFiqh2 = Module::firstOrCreate(
            ['name' => 'Salah (Prayer)', 'subject_id' => $subjectFiqh->id],
            [
                'name_bn' => 'সালাত অধ্যায়',
                'order' => 2,
                'description' => 'Rules and procedures of Islamic Prayer.',
            ]
        );

        $moduleArabic1 = Module::firstOrCreate(
            ['name' => 'Arabic Nouns & Pronouns', 'subject_id' => $subjectArabicGrammar->id],
            [
                'name_bn' => 'আরবি বিশেষ্য ও সর্বনাম',
                'order' => 1,
                'description' => 'Identification and rules of nouns.',
            ]
        );

        // 9. Create ClassLessons (Classes)
        // Video Lesson
        ClassLesson::firstOrCreate(
            ['title' => 'Wudu Rules and Sunnahs', 'batch_id' => $batchAlim->id, 'module_id' => $moduleFiqh1->id],
            [
                'title_bn' => 'ওযুর নিয়ম ও সুন্নাতসমূহ',
                'type' => 'video',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'order' => 1,
                'is_published' => true,
            ]
        );

        // Live Lesson
        ClassLesson::firstOrCreate(
            ['title' => 'Ghusl Obligations (Live Class)', 'batch_id' => $batchAlim->id, 'module_id' => $moduleFiqh1->id],
            [
                'title_bn' => 'গোসলের ফরজসমূহ (লাইভ ক্লাস)',
                'type' => 'live',
                'meet_link' => 'https://meet.google.com/abc-defg-hij',
                'scheduled_at' => '2026-05-22 18:00:00',
                'order' => 2,
                'is_published' => true,
            ]
        );

        // PDF/Note Lesson
        ClassLesson::firstOrCreate(
            ['title' => 'Taharah Notes Handout', 'batch_id' => $batchAlim->id, 'module_id' => $moduleFiqh1->id],
            [
                'title_bn' => 'পবিত্রতা নোট শীট',
                'type' => 'pdf',
                'order' => 3,
                'is_published' => true,
            ]
        );

        // 10. Fee Structures
        $feeAdmission = FeeStructure::firstOrCreate(
            ['course_id' => $courseAlim->id, 'type' => 'admission'],
            [
                'title' => 'Alim Admission Fee',
                'amount' => 1500.00,
                'description' => 'One-time admission fee for the 3-year Alim course.',
                'status' => 'active',
            ]
        );

        $feeMonthly = FeeStructure::firstOrCreate(
            ['course_id' => $courseAlim->id, 'type' => 'monthly'],
            [
                'title' => 'Alim Monthly Tuition',
                'amount' => 500.00,
                'description' => 'Monthly tuition fee for the Alim course.',
                'status' => 'active',
            ]
        );

        // 11. Sample Payment
        Payment::firstOrCreate(
            ['student_id' => $student1User->id, 'fee_structure_id' => $feeAdmission->id],
            [
                'invoice_number' => 'INV-2026001',
                'amount' => 1500.00,
                'discount' => 200.00,
                'paid_amount' => 1300.00,
                'payment_method' => 'manual',
                'transaction_id' => 'TRX99887766',
                'status' => 'paid',
                'paid_at' => now()->subDays(5),
                'created_at' => now()->subDays(5),
            ]
        );

        // 12. Notice Board Items
        $adminUser = User::where('email', 'admin@iom.edu')->first();
        $adminId = $adminUser ? $adminUser->id : $teacher1User->id;

        Notice::firstOrCreate(
            ['title' => 'Ramadan Class Schedule Adjustment'],
            [
                'created_by' => $adminId,
                'body' => 'During Ramadan, class timings will be adjusted. Class starts at 3:00 PM instead of 5:00 PM.',
                'scope' => 'universal',
                'published_at' => now(),
            ]
        );

        Notice::firstOrCreate(
            ['title' => 'Alim Batch 01 Midterm Examination Schedule', 'batch_id' => $batchAlim->id],
            [
                'created_by' => $adminId,
                'body' => 'The Midterm exams for Alim Semester 1 will begin from June 1st, 2026. Get your routines from the Dashboard.',
                'scope' => 'batch',
                'published_at' => now(),
            ]
        );

        // 13. Support Ticket
        SupportTicket::firstOrCreate(
            ['user_id' => $student1User->id, 'title' => 'Unable to access Ghusl Live Meet Link'],
            [
                'category' => 'technical',
                'description' => 'The Meet button on the learning dashboard displays an invalid URL error when clicked.',
                'status' => 'open',
            ]
        );

        // 14. Sample Exam and Question Bank
        $examFiqh = Exam::firstOrCreate(
            ['title' => 'Fiqh Taharah Midterm', 'batch_id' => $batchAlim->id, 'subject_id' => $subjectFiqh->id],
            [
                'semester_id' => $semesterAlim1->id,
                'type' => 'mcq',
                'duration_minutes' => 30,
                'total_marks' => 10,
                'pass_marks' => 4,
                'status' => 'published',
                'start_at' => '2026-05-20 00:00:00',
                'end_at' => '2026-06-20 00:00:00',
            ]
        );

        // Questions for the MCQ Exam
        Question::firstOrCreate(
            ['exam_id' => $examFiqh->id, 'question_text' => 'What is the literal meaning of Taharah?'],
            [
                'subject_id' => $subjectFiqh->id,
                'created_by' => $teacher1User->id,
                'option_a' => 'Purity',
                'option_b' => 'Cleanliness',
                'option_c' => 'Worship',
                'option_d' => 'None of these',
                'correct_answer' => 'a',
                'marks' => 5,
                'status' => 'approved',
            ]
        );

        Question::firstOrCreate(
            ['exam_id' => $examFiqh->id, 'question_text' => 'How many obligatory (Farz) acts are there in Wudu?'],
            [
                'subject_id' => $subjectFiqh->id,
                'created_by' => $teacher1User->id,
                'option_a' => '3',
                'option_b' => '4',
                'option_c' => '5',
                'option_d' => '6',
                'correct_answer' => 'b',
                'marks' => 5,
                'status' => 'approved',
            ]
        );

        // 15. Attendance Record
        $lesson = ClassLesson::where('batch_id', $batchAlim->id)->first();
        if ($lesson) {
            Attendance::firstOrCreate(
                ['class_lesson_id' => $lesson->id, 'student_id' => $student1User->id],
                [
                    'batch_id' => $batchAlim->id,
                    'date' => '2026-05-20',
                    'status' => 'present',
                    'marked_by' => $teacher1User->id,
                ]
            );
        }

        // 16. Mock Exam Attempt & Result
        $attempt = \App\Models\ExamAttempt::firstOrCreate(
            ['exam_id' => $examFiqh->id, 'student_id' => $student1User->id],
            [
                'started_at' => now()->subDays(2),
                'submitted_at' => now()->subDays(2)->addMinutes(15),
                'obtained_marks' => 10,
                'percentage' => 100.00,
                'grade' => 'A+',
                'gpa' => 4.00,
                'status' => 'evaluated',
            ]
        );

        $q1 = \App\Models\Question::where('exam_id', $examFiqh->id)->first();
        if ($q1) {
            \App\Models\ExamAnswer::firstOrCreate(
                ['exam_attempt_id' => $attempt->id, 'question_id' => $q1->id],
                [
                    'selected_answer' => 'a',
                    'is_correct' => true,
                ]
            );
        }

        \App\Models\Result::firstOrCreate(
            ['student_id' => $student1User->id, 'semester_id' => $semesterAlim1->id, 'batch_id' => $batchAlim->id],
            [
                'cgpa' => 4.00,
                'overall_grade' => 'A+',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ]
        );
    }
}
