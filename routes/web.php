<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\General\SchoolProfileController;
use App\Http\Controllers\General\ChangePasswordController;
use App\Http\Controllers\General\DashboardController;
use App\Http\Controllers\Master\AdminController;

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

Route::resource('school_profile', SchoolProfileController::class);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // route for helper api
    Route::get('api/user/change_password/{id}', [ChangePasswordController::class, 'edit'])->name('api.user.change_password.edit');
    Route::put('api/user/change_password/{id}', [ChangePasswordController::class, 'update'])->name('api.user.change_password.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('master/admins', AdminController::class);
});

require __DIR__.'/auth.php';
