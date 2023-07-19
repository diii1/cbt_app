<?php

use Illuminate\Support\Facades\Route;

// Excel Route Controller
use App\Http\Controllers\Excel\TeacherExcelController;

// General Route Controller
use App\Http\Controllers\General\SchoolProfileController;
use App\Http\Controllers\General\ChangePasswordController;
use App\Http\Controllers\General\ExportImportController;
use App\Http\Controllers\General\DashboardController;

// Master Route Controller
use App\Http\Controllers\Master\AdminController;
use App\Http\Controllers\Master\SubjectController;
use App\Http\Controllers\Master\ClassesController;
use App\Http\Controllers\Master\TeacherController;

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
    // route for api change password
    Route::get('api/user/change_password/{id}', [ChangePasswordController::class, 'edit'])->name('api.user.change_password.edit');
    Route::put('api/user/change_password/{id}', [ChangePasswordController::class, 'update'])->name('api.user.change_password.update');

    // route for api export import
    Route::get('api/excel/teacher/template', [TeacherExcelController::class, 'template'])->name('api.teacher.template');
    Route::get('api/excel/teacher', [TeacherExcelController::class, 'create'])->name('api.teacher.create');
    Route::get('api/excel/teacher/export', [TeacherExcelController::class, 'export'])->name('api.teacher.export');
    Route::post('api/excel/teacher/import', [TeacherExcelController::class, 'import'])->name('api.teacher.import');

    // route for dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // route for master data
    Route::resource('master/subjects', SubjectController::class);
    Route::resource('master/classes', ClassesController::class);
    Route::resource('pengguna/admins', AdminController::class);
    Route::resource('pengguna/teachers', TeacherController::class);
    Route::get('/pengguna/teachers/upload', [TeacherController::class, 'upload'])->name('teachers.upload');
});

require __DIR__.'/auth.php';
