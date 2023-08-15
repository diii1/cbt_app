<?php

use Illuminate\Support\Facades\Route;

// Excel Route Controller
use App\Http\Controllers\Excel\TeacherExcelController;
use App\Http\Controllers\Excel\StudentExcelController;

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
Route::resource('school_profile', SchoolProfileController::class);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
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

    // route for api get teachers
    Route::get('api/teachers/{subject_id}/subject', [TeacherController::class, 'getBySubjectID'])->name('api.teachers.subjectID');

    // route for api update status exam
    Route::put('api/exams/{exam_id}/is_active', [ExamController::class, 'update_exam'])->name('api.exam.is_active');

    // route for api get table exam participant
    Route::get('api/exams/{exam_id}/participants', [ExamParticipantController::class, 'getTable'])->name('api.exam.participants_table');
    Route::get('api/exams/participant/{exam_id}/cards', [ExamParticipantController::class, 'print_cards'])->name('api.exam.participants_cards');

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
    Route::resource('exams', ExamController::class);

    // route for question
    Route::resource('questions', QuestionController::class);
    Route::get('questions/create/{exam_id}', [QuestionController::class, 'create'])->name('questions.create');
    Route::get('questions/list/{exam_id}', [QuestionController::class, 'question_list'])->name('questions.list');
});

require __DIR__.'/auth.php';
