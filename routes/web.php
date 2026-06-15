<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// ─── Public Routes ───────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// Admission (public)
Route::get('/admission', [\App\Http\Controllers\Admin\AdmissionController::class, 'publicForm'])->name('admission.form');
Route::post('/admission', [\App\Http\Controllers\Admin\AdmissionController::class, 'publicStore'])->name('admission.store');

// Support (public)
Route::get('/support', [\App\Http\Controllers\Student\SupportController::class, 'publicForm'])->name('support.public');
Route::post('/support', [\App\Http\Controllers\Student\SupportController::class, 'publicStore'])->name('support.public.store');

// ─── Auth ─────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Admin Routes ─────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Academic
    Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class);
    Route::resource('semesters', \App\Http\Controllers\Admin\SemesterController::class);
    Route::resource('batches', \App\Http\Controllers\Admin\BatchController::class);
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('modules', \App\Http\Controllers\Admin\ModuleController::class);
    Route::resource('classes', \App\Http\Controllers\Admin\ClassLessonController::class);
    Route::post('batches/{batch}/assign-subject', [\App\Http\Controllers\Admin\BatchController::class, 'assignSubject'])->name('batches.assign-subject');
    Route::post('students/{student}/promote', [\App\Http\Controllers\Admin\StudentController::class, 'promote'])->name('students.promote');

    // Admission & Students
    Route::resource('admissions', \App\Http\Controllers\Admin\AdmissionController::class);
    Route::post('admissions/{admission}/approve', [\App\Http\Controllers\Admin\AdmissionController::class, 'approve'])->name('admissions.approve');
    Route::post('admissions/{admission}/reject', [\App\Http\Controllers\Admin\AdmissionController::class, 'reject'])->name('admissions.reject');
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);

    // Teachers
    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
    Route::resource('routines', \App\Http\Controllers\Admin\RoutineController::class);

    // Attendance
    Route::get('attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/report', [\App\Http\Controllers\Admin\AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('attendance/export/{format}', [\App\Http\Controllers\Admin\AttendanceController::class, 'export'])->name('attendance.export');

    // Exam
    Route::resource('exams', \App\Http\Controllers\Admin\ExamController::class);
    Route::get('exams/{exam}/questions/assign', [\App\Http\Controllers\Admin\ExamController::class, 'assignQuestionsForm'])->name('exams.questions.assign');
    Route::post('exams/{exam}/questions/assign', [\App\Http\Controllers\Admin\ExamController::class, 'assignQuestionsStore'])->name('exams.questions.assign.store');
    Route::resource('questions', \App\Http\Controllers\Admin\QuestionController::class);
    Route::post('questions/{question}/approve', [\App\Http\Controllers\Admin\QuestionController::class, 'approve'])->name('questions.approve');
    Route::get('results', [\App\Http\Controllers\Admin\ResultController::class, 'index'])->name('results.index');
    Route::post('results/{result}/publish', [\App\Http\Controllers\Admin\ResultController::class, 'publish'])->name('results.publish');
    Route::get('certificates', [\App\Http\Controllers\Admin\CertificateController::class, 'index'])->name('certificates.index');
    Route::post('certificates/{student}/generate', [\App\Http\Controllers\Admin\CertificateController::class, 'generate'])->name('certificates.generate');
    Route::get('results/{student}/transcript', [\App\Http\Controllers\Admin\ResultController::class, 'transcript'])->name('results.transcript');

    // Finance
    Route::resource('fee-structures', \App\Http\Controllers\Admin\FeeStructureController::class);
    Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class);
    Route::resource('scholarships', \App\Http\Controllers\Admin\ScholarshipController::class);
    Route::post('scholarships/{scholarship}/approve', [\App\Http\Controllers\Admin\ScholarshipController::class, 'approve'])->name('scholarships.approve');
    Route::get('finance/report', [\App\Http\Controllers\Admin\PaymentController::class, 'report'])->name('finance.report');
    Route::get('finance/export/{format}', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('finance.export');

    // Notices
    Route::resource('notices', \App\Http\Controllers\Admin\NoticeController::class);

    // Support
    Route::get('support', [\App\Http\Controllers\Admin\SupportController::class, 'index'])->name('support.index');
    Route::put('support/{ticket}/reply', [\App\Http\Controllers\Admin\SupportController::class, 'reply'])->name('support.reply');

    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Reports (dedicated ReportController)
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/students', [\App\Http\Controllers\Admin\ReportController::class, 'students'])->name('reports.students');
    Route::get('reports/students/export/{format}', [\App\Http\Controllers\Admin\ReportController::class, 'exportStudents'])->name('reports.students.export');
    Route::get('reports/attendance', [\App\Http\Controllers\Admin\ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('reports/attendance/export/{format}', [\App\Http\Controllers\Admin\ReportController::class, 'exportAttendance'])->name('reports.attendance.export');
    Route::get('reports/examination', [\App\Http\Controllers\Admin\ReportController::class, 'examination'])->name('reports.examination');
    Route::get('reports/examination/export/{format}', [\App\Http\Controllers\Admin\ReportController::class, 'exportExamination'])->name('reports.examination.export');
    Route::get('reports/financial', [\App\Http\Controllers\Admin\ReportController::class, 'financial'])->name('reports.financial');
    Route::get('reports/financial/export/{format}', [\App\Http\Controllers\Admin\ReportController::class, 'exportFinancial'])->name('reports.financial.export');
    Route::get('reports/analytics', [\App\Http\Controllers\Admin\ReportController::class, 'analytics'])->name('reports.analytics');

    // Quiz (Admin)
    Route::resource('quiz', \App\Http\Controllers\Admin\QuizController::class);
    Route::post('quiz/{quiz}/questions', [\App\Http\Controllers\Admin\QuizController::class, 'storeQuestion'])->name('quiz.questions.store');
    Route::delete('quiz/{quiz}/questions/{questionId}', [\App\Http\Controllers\Admin\QuizController::class, 'destroyQuestion'])->name('quiz.questions.destroy');
    Route::get('quiz/{quiz}/leaderboard', [\App\Http\Controllers\Admin\QuizController::class, 'leaderboard'])->name('quiz.leaderboard');

    // Form Builder (Admin)
    Route::resource('forms', \App\Http\Controllers\Admin\FormBuilderController::class);
    Route::get('forms/{form}/responses', [\App\Http\Controllers\Admin\FormBuilderController::class, 'responses'])->name('forms.responses');
    Route::post('forms/{form}/toggle', [\App\Http\Controllers\Admin\FormBuilderController::class, 'toggle'])->name('forms.toggle');

    // Surveys (Admin)
    Route::resource('surveys', \App\Http\Controllers\Admin\SurveyController::class);
    Route::get('surveys/{survey}/responses', [\App\Http\Controllers\Admin\SurveyController::class, 'responses'])->name('surveys.responses');
    Route::post('surveys/{survey}/toggle', [\App\Http\Controllers\Admin\SurveyController::class, 'toggle'])->name('surveys.toggle');

    // Readmission & Transfers (Admin)
    Route::get('readmissions', [\App\Http\Controllers\Admin\ReadmissionController::class, 'index'])->name('readmissions.index');
    Route::get('readmissions/create', [\App\Http\Controllers\Admin\ReadmissionController::class, 'create'])->name('readmissions.create');
    Route::post('readmissions', [\App\Http\Controllers\Admin\ReadmissionController::class, 'store'])->name('readmissions.store');
    Route::post('readmissions/{readmission}/approve', [\App\Http\Controllers\Admin\ReadmissionController::class, 'approve'])->name('readmissions.approve');
    Route::post('readmissions/{readmission}/reject', [\App\Http\Controllers\Admin\ReadmissionController::class, 'reject'])->name('readmissions.reject');
    Route::get('transfers', [\App\Http\Controllers\Admin\ReadmissionController::class, 'transfers'])->name('transfers.index');
    Route::post('transfers', [\App\Http\Controllers\Admin\ReadmissionController::class, 'storeTransfer'])->name('transfers.store');
});

// ─── Teacher Routes ────────────────────────────────────────────────
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('classes', \App\Http\Controllers\Teacher\ClassController::class);
    Route::get('attendance/{batch}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('attendance.store');
    Route::resource('exams', \App\Http\Controllers\Teacher\ExamController::class);
    Route::get('exams/{exam}/questions/assign', [\App\Http\Controllers\Teacher\ExamController::class, 'assignQuestionsForm'])->name('exams.questions.assign');
    Route::post('exams/{exam}/questions/assign', [\App\Http\Controllers\Teacher\ExamController::class, 'assignQuestionsStore'])->name('exams.questions.assign.store');
    Route::resource('questions', \App\Http\Controllers\Teacher\QuestionController::class);
    Route::post('live-class', [\App\Http\Controllers\Teacher\LiveClassController::class, 'store'])->name('live-class.store');
    Route::get('live-class/create', [\App\Http\Controllers\Teacher\LiveClassController::class, 'create'])->name('live-class.create');
    Route::get('live-class', [\App\Http\Controllers\Teacher\LiveClassController::class, 'index'])->name('live-class.index');
    Route::delete('live-class/{lesson}', [\App\Http\Controllers\Teacher\LiveClassController::class, 'destroy'])->name('live-class.destroy');
    Route::resource('notices', \App\Http\Controllers\Teacher\NoticeController::class);
    // Teacher Quiz Management (QZ-01)
    
    // ─── Quiz Room CRUD ─────────────────────────────────────────────
    Route::get('quiz', [\App\Http\Controllers\Teacher\QuizController::class, 'index'])->name('quiz.index');
    Route::get('quiz/create', [\App\Http\Controllers\Teacher\QuizController::class, 'create'])->name('quiz.create');
    Route::post('quiz', [\App\Http\Controllers\Teacher\QuizController::class, 'store'])->name('quiz.store');
    Route::get('quiz/{quiz}', [\App\Http\Controllers\Teacher\QuizController::class, 'show'])->name('quiz.show');
    Route::get('quiz/{quiz}/edit', [\App\Http\Controllers\Teacher\QuizController::class, 'edit'])->name('quiz.edit');
    Route::put('quiz/{quiz}', [\App\Http\Controllers\Teacher\QuizController::class, 'update'])->name('quiz.update');
    Route::delete('quiz/{quiz}', [\App\Http\Controllers\Teacher\QuizController::class, 'destroy'])->name('quiz.destroy');

    // ─── Quiz Questions Management ──────────────────────────────────
    Route::post('quiz/{quiz}/questions', [\App\Http\Controllers\Teacher\QuizController::class, 'storeQuestion'])->name('quiz.questions.store');
    Route::delete('quiz/{quiz}/questions/{questionId}', [\App\Http\Controllers\Teacher\QuizController::class, 'destroyQuestion'])->name('quiz.questions.destroy');


});

// ─── Student Routes ────────────────────────────────────────────────
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('learning', [\App\Http\Controllers\Student\LearningController::class, 'index'])->name('learning.index');
    Route::post('learning/{lesson}/complete', [\App\Http\Controllers\Student\LearningController::class, 'markComplete'])->name('learning.complete');
    Route::get('exams', [\App\Http\Controllers\Student\ExamController::class, 'index'])->name('exams.index');
    Route::post('exams/{exam}/start', [\App\Http\Controllers\Student\ExamController::class, 'start'])->name('exams.start');
    Route::post('exams/{exam}/submit', [\App\Http\Controllers\Student\ExamController::class, 'submit'])->name('exams.submit');
    Route::get('results', [\App\Http\Controllers\Student\ResultController::class, 'index'])->name('results.index');
    Route::get('attendance', [\App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('payments', [\App\Http\Controllers\Student\PaymentController::class, 'index'])->name('payments.index');
    Route::get('notices', [\App\Http\Controllers\Student\NoticeController::class, 'index'])->name('notices.index');
    Route::resource('support', \App\Http\Controllers\Student\SupportController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('profile', [\App\Http\Controllers\Student\ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [\App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');

    // Quiz (Student)
    Route::get('quiz', [\App\Http\Controllers\Student\QuizController::class, 'index'])->name('quiz.index');
    Route::post('quiz/join', [\App\Http\Controllers\Student\QuizController::class, 'join'])->name('quiz.join');
    Route::get('quiz/{quiz}/take', [\App\Http\Controllers\Student\QuizController::class, 'take'])->name('quiz.take');
    Route::post('quiz/{quiz}/submit', [\App\Http\Controllers\Student\QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('quiz/{quiz}/result', [\App\Http\Controllers\Student\QuizController::class, 'result'])->name('quiz.result');

    // Readmission (Student)
    Route::get('readmission', [\App\Http\Controllers\Student\ReadmissionController::class, 'index'])->name('readmission.index');
    Route::get('readmission/apply', [\App\Http\Controllers\Student\ReadmissionController::class, 'create'])->name('readmission.create');
    Route::post('readmission', [\App\Http\Controllers\Student\ReadmissionController::class, 'store'])->name('readmission.store');
});

// ─── Public Form Submission ────────────────────────────────────────
Route::get('/forms/{slug}', [\App\Http\Controllers\PublicFormController::class, 'show'])->name('forms.public.show');
Route::post('/forms/{slug}', [\App\Http\Controllers\PublicFormController::class, 'submit'])->name('forms.public.submit');

// Surveys (public)
Route::get('/surveys/{slug}', [\App\Http\Controllers\PublicSurveyController::class, 'show'])->name('surveys.public.show');
Route::post('/surveys/{slug}', [\App\Http\Controllers\PublicSurveyController::class, 'submit'])->name('surveys.public.submit');

// ─── Admin Audit Trail ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('audit', [\App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit.index');
    // Academic Calendar
    Route::resource('academic-calendars', \App\Http\Controllers\Admin\AcademicCalendarController::class);
});

// ─── Notifications ─────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.read-all');
});
