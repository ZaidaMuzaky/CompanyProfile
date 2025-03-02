<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FoldersController;

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
    return view('home');
});
// login page
Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');
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
    Route::get('/admin/folders', [FoldersController::class, 'index'])->name('admin.folders');
    Route::post('/admin/folders', [FoldersController::class, 'store'])->name('admin.folders.store');
    Route::put('/admin/folders/{id}/update', [FoldersController::class, 'update'])->name('admin.folders.update');
    Route::delete('/admin/folders/{id}', [FoldersController::class, 'destroy'])->name('admin.folders.destroy');
});
