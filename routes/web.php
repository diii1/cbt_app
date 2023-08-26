<?php

use Illuminate\Support\Facades\Route;

// Excel Route Controller
use App\Http\Controllers\Excel\TeacherExcelController;
use App\Http\Controllers\Excel\StudentExcelController;
use App\Http\Controllers\Excel\ResultExcelController;

// General Route Controller
use App\Http\Controllers\General\SchoolProfileController;
use App\Http\Controllers\General\ChangePasswordController;
use App\Http\Controllers\General\ExportImportController;
use App\Http\Controllers\General\DashboardController;

// General Route for upload image from tiny mce
use App\Http\Controllers\General\ImageTinyMceController;

// Master Route Controller
use App\Http\Controllers\Master\AdminController;
use App\Http\Controllers\Master\SubjectController;
use App\Http\Controllers\Master\ClassesController;
use App\Http\Controllers\Master\TeacherController;
use App\Http\Controllers\Master\StudentController;

// Exam Route Controller
use App\Http\Controllers\Exam\SessionController;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\Exam\ExamParticipantController;
use App\Http\Controllers\Exam\QuestionController;
use App\Http\Controllers\Exam\ResultController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// route for school profile
Route::get('school_profile', [SchoolProfileController::class, 'index'])->name('school_profile.index');
Route::get('school_profile/create', [SchoolProfileController::class, 'create'])->name('school_profile.create');
Route::post('school_profile', [SchoolProfileController::class, 'store'])->name('school_profile.store');
// Route::resource('school_profile', SchoolProfileController::class);

// route for clock
Route::get('/clock', function () {
    return view('clock');
});

Route::middleware(['auth'])->group(function () {
    // route for api get Exam Table
    Route::get('api/exams/{teacher_id}/table', [DashboardController::class, 'examTable'])->name('api.dashboard.exam.table');

    // route for api upload image from tiny mce
    Route::post('api/tinymce/upload', [ImageTinyMceController::class, 'imageUpload'])->name('api.tinymce.upload');

    // route for api change password
    Route::get('api/user/change_password/{id}', [ChangePasswordController::class, 'edit'])->name('api.user.change_password.edit');
    Route::put('api/user/change_password/{id}', [ChangePasswordController::class, 'update'])->name('api.user.change_password.update');

    // route for api export import teacher
    Route::get('api/excel/teacher/template', [TeacherExcelController::class, 'template'])->name('api.teacher.template');
    Route::get('api/excel/teacher', [TeacherExcelController::class, 'create'])->name('api.teacher.create');
    Route::get('api/excel/teacher/export', [TeacherExcelController::class, 'export'])->name('api.teacher.export');
    Route::post('api/excel/teacher/import', [TeacherExcelController::class, 'import'])->name('api.teacher.import');

    // route for api export import student
    Route::get('api/excel/student/template', [StudentExcelController::class, 'template'])->name('api.student.template');
    Route::get('api/excel/student', [StudentExcelController::class, 'create'])->name('api.student.create');
    Route::get('api/excel/student/export', [StudentExcelController::class, 'export'])->name('api.student.export');
    Route::post('api/excel/student/import', [StudentExcelController::class, 'import'])->name('api.student.import');

    // route for api export result
    Route::get('api/excel/results/{exam_id}/export', [ResultExcelController::class, 'export'])->name('api.result.export');

    // route for api get teachers
    Route::get('api/teachers/{subject_id}/subject', [TeacherController::class, 'getBySubjectID'])->name('api.teachers.subjectID');

    // route for api update status exam
    Route::put('api/exams/{exam_id}/is_active', [ExamController::class, 'update_exam'])->name('api.exam.is_active');

    // route for validate exam token
    Route::get('api/exams/{exam_code}/validate_token', [ExamController::class, 'validate_token'])->name('api.exam.validate_token');
    Route::post('api/exams/validate_exam', [ExamController::class, 'validate_exam'])->name('api.exam.validate_exam');
    Route::post('api/exams/start', [ExamController::class, 'start_exam'])->name('api.exam.start.store');

    // route for api set answer
    Route::post('api/exams/set_answer', [ExamController::class, 'set_answer'])->name('api.exam.set_answer');
    Route::post('api/exams/set_doubtful', [ExamController::class, 'set_doubtful_answer'])->name('api.exam.set_doubtful_answer');

    // route for api finish exam
    Route::post('api/exams/finish', [ExamController::class, 'finish'])->name('api.exam.finish');
    Route::post('api/exams/force_finish', [ExamController::class, 'force_finish'])->name('api.exam.force_finish');

    // route for api get table exam participant
    Route::get('api/exams/{exam_id}/participants', [ExamParticipantController::class, 'getTable'])->name('api.exam.participants_table');
    Route::get('api/exams/participant/{exam_id}/cards', [ExamParticipantController::class, 'print_cards'])->name('api.exam.participants_cards');

    // route for setting school profile
    Route::get('school_profile/show', [SchoolProfileController::class, 'show'])->name('school_profile.show');
    Route::get('school_profile/{profile_id}/edit', [SchoolProfileController::class, 'edit'])->name('school_profile.edit');
    Route::put('school_profile/update/{profile_id}', [SchoolProfileController::class, 'update'])->name('school_profile.update');

    // route for dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // route for master data
    Route::resource('master/subjects', SubjectController::class);
    Route::resource('master/classes', ClassesController::class);

    // route for master user data
    Route::resource('pengguna/admins', AdminController::class);
    Route::resource('pengguna/teachers', TeacherController::class);
    Route::resource('pengguna/students', StudentController::class);

    // route for exam session
    Route::resource('exams/sessions', SessionController::class);
    Route::resource('exams/participants', ExamParticipantController::class);
    Route::get('exams/participants/list/{exam_id}', [ExamParticipantController::class, 'participant_list'])->name('participants.list');
    Route::get('exams/start/{exam_code}', [ExamController::class, 'start'])->name('api.exam.start');
    Route::get('exams/question/{exam_code}/{index_question}', [ExamController::class, 'get_question'])->name('api.exam.get_question');
    Route::get('exams/finished/{exam_id}', [ExamController::class, 'finished'])->name('exam.finished');
    Route::resource('exams', ExamController::class);

    // route for question
    Route::resource('questions', QuestionController::class);
    Route::get('questions/create/{exam_id}', [QuestionController::class, 'create'])->name('questions.create');
    Route::get('questions/list/{exam_id}', [QuestionController::class, 'question_list'])->name('questions.list');

    // route for result
    Route::get('results', [ResultController::class, 'index'])->name('results.index');
    Route::get('results/list/{exam_id}', [ResultController::class, 'list'])->name('results.list');
});

require __DIR__.'/auth.php';
