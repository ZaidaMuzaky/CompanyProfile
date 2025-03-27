<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\FoldersController;
use App\Http\Controllers\DashboardController;

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

Route::get('/home', function () {
    return view('home');
})->name('home');
// login page
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// user management
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/{id}/update', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');


    // folder management
    Route::get('/admin/folders', [FoldersController::class, 'index'])->name('admin.folders.index');
    Route::get('/admin/folders/{id}', [FoldersController::class, 'show'])->name('admin.folders.show');
    Route::post('/admin/folders', [FoldersController::class, 'store'])->name('admin.folders.store');
    Route::put('/admin/folders/{id}', [FoldersController::class, 'update'])->name('admin.folders.update');
    Route::delete('/admin/folders/{id}', [FoldersController::class, 'destroy'])->name('admin.folders.destroy');

    // google form
    Route::get('/admin/google-form', [App\Http\Controllers\GformController::class, 'editGoogleForm'])->name('admin.google-form.edit');
    Route::post('/admin/google-form', [App\Http\Controllers\GformController::class, 'updateGoogleForm'])->name('admin.google-form.update');
});

// file management for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/user/files', [FilesController::class, 'index'])->name('user.files.index');
    Route::get('/user/files/{id_folder}', [FilesController::class, 'show'])->name('user.files.show');
    Route::get('/user/files/manage/{id_folder}', [FilesController::class, 'manage'])->name('user.files.manage');
    Route::post('/user/files', [FilesController::class, 'store'])->name('user.files.store');
    Route::put('/user/files/{id}/update', [FilesController::class, 'update'])->name('user.files.update');
    Route::delete('/user/files/{id}', [FilesController::class, 'destroy'])->name('user.files.destroy');
    Route::get('/user/files/download/{id}', [FilesController::class, 'download'])->name('user.files.download');

    //  dasboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
