<?php

use App\Models\Achivements;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\FoldersController;
use App\Http\Controllers\MenuViewController;
use App\Http\Controllers\NewsViewController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AchivementsController;
use App\Http\Controllers\NewsVisitController;
use App\Http\Controllers\PeopleController;

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
    $achievements = Achivements::all();
    return view('home', compact('achievements'));
})->name('home');

// Route untuk halaman Community
Route::get('/community', function () {
    $communities = \App\Models\Community::all();
    return view('community', compact('communities'));
})->name('community');

// Route untuk halaman people
Route::get('/people', function () {
    $people = \App\Models\People::all();
    return view('our-people', compact('people'));
})->name('people');

// Route untuk menampilkan berita visitor
Route::prefix('newsvisit')
     ->name('newsvisit.')   
     ->group(function () {
        Route::get('/',         [NewsVisitController::class, 'index'])
             ->name('index');  

        Route::get('/{id}',     [NewsVisitController::class, 'detail'])
             ->name('detail'); 
            });



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
    Route::post('/admin/users/import', [UserController::class, 'import'])->name('admin.users.import');

    // folder management
    Route::get('/admin/folders', [FoldersController::class, 'index'])->name('admin.folders.index');
    Route::get('/admin/folders/{id}', [FoldersController::class, 'show'])->name('admin.folders.show');
    Route::post('/admin/folders', [FoldersController::class, 'store'])->name('admin.folders.store');
    Route::put('/admin/folders/{id}', [FoldersController::class, 'update'])->name('admin.folders.update');
    Route::delete('/admin/folders/{id}', [FoldersController::class, 'destroy'])->name('admin.folders.destroy');

    // google form
    Route::get('/admin/google-form', [App\Http\Controllers\GformController::class, 'editGoogleForm'])->name('admin.google-form.edit');
    Route::post('/admin/google-form', [App\Http\Controllers\GformController::class, 'updateGoogleForm'])->name('admin.google-form.update');
    Route::post('/admin/google-form/{id}', [App\Http\Controllers\GformController::class, 'updateSpecificGoogleForm'])->name('admin.google-form.updateSpecific');
    Route::delete('/admin/google-form/{id}', [App\Http\Controllers\GformController::class, 'deleteGoogleForm'])->name('admin.google-form.delete');

    // menu management
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::resource('menus', MenuController::class)->except(['create', 'edit', 'update']);

        // Tambahkan route untuk store dan destroy gambar
        Route::post('menus/{id}/images', [MenuController::class, 'storeImage'])->name('menus.images.store');
        Route::delete('menus/{id}/images/{imageName}', [MenuController::class, 'destroyImage'])->name('menus.images.destroy');
        Route::put('menus/{id}', [MenuController::class, 'update'])->name('menus.update');
        Route::get('menus/{id}', [MenuController::class, 'show'])->name('admin.menus.show');
        Route::get('menus/{id}/view', [MenuController::class, 'view'])->name('admin.menus.view');
    });

    Route::prefix('admin/menus/{menu}')->name('admin.menus.')->group(function () {
        Route::get('sub', [MenuController::class, 'sub'])->name('sub'); // Route untuk halaman submenu
        Route::post('sub', [MenuController::class, 'storeSubmenu'])->name('submenus.store'); // Tambah submenu
        Route::delete('sub/{submenu}', [MenuController::class, 'destroySubmenu'])->name('submenus.destroy'); // Hapus submenu
        Route::get('sub/{submenu}/show', [MenuController::class, 'show'])->name('show'); // Halaman gambar
        Route::put('sub/{submenu}', [MenuController::class, 'updateSubmenu'])->name('submenus.update'); // Update submenu
    });

    Route::prefix('admin/menus/{menu}/sub/{submenu}')->name('admin.menus.sub.')->group(function () {
        Route::post('images', [MenuController::class, 'storeImage'])->name('images.store'); // Simpan gambar submenu
        Route::delete('images/{image}', [MenuController::class, 'destroyImage'])->name('images.destroy'); // Hapus gambar submenu
        Route::put('images/{image}', [MenuController::class, 'updateImage'])->name('images.update'); // Edit gambar
        Route::post('images', [MenuController::class, 'storeImage'])->name('admin.menus.sub.images.store'); // Simpan gambar submenu
    });

    Route::post('admin/menus/{menu}/sub/{submenu}/images', [MenuController::class, 'storeImage'])->name('admin.menus.sub.images.store');
    Route::put('admin/menus/{menu}/sub/{submenu}/images/{image}', [MenuController::class, 'updateImage'])->name('admin.menus.sub.images.update');
    Route::get('admin/menus/{menu}/sub/{submenu}/show', [MenuController::class, 'show'])->name('admin.menus.show');

    // news management
    Route::prefix('admin/news')->name('admin.news.')->middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('index'); // List berita
        Route::get('/create-or-edit/{id?}', [NewsController::class, 'createOrEdit'])->name('createOrEdit'); // Form tambah/edit berita
        Route::post('/store-or-update', [NewsController::class, 'storeOrUpdate'])->name('storeOrUpdate'); // Simpan atau update berita
        Route::delete('/{id}', [NewsController::class, 'destroy'])->name('destroy'); // Hapus berita
    });

    // achievement management
    Route::prefix('admin/achievement')->name('admin.achievement.')->middleware('auth')->group(function () {
        Route::get('/', [AchivementsController::class, 'index'])->name('index');
        Route::get('/create', [AchivementsController::class, 'create'])->name('create');
        Route::post('/store', [AchivementsController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AchivementsController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AchivementsController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [AchivementsController::class, 'destroy'])->name('destroy');
    });

    // Community management routes
    Route::prefix('admin/community')->name('admin.community.')->middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [CommunityController::class, 'index'])->name('index');
        Route::post('/store', [CommunityController::class, 'store'])->name('store');
        Route::put('/update/{id}', [CommunityController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [CommunityController::class, 'destroy'])->name('destroy');
    });

    // Route for managing People
    Route::prefix('admin/people')->name('admin.people.')->middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [PeopleController::class, 'index'])->name('index');
        Route::post('/store', [PeopleController::class, 'store'])->name('store');
        Route::put('/update/{id}', [PeopleController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [PeopleController::class, 'destroy'])->name('destroy');
    });
});

// file management for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/user/files', [FilesController::class, 'index'])->name('user.files.index');
    Route::get('/user/files/{id_folder}', [FilesController::class, 'show'])->name('user.files.show');
    Route::get('/user/files/manage/{id_folder}', [FilesController::class, 'manage'])->name('user.files.manage');
    Route::post('/user/files', [FilesController::class, 'store'])->name('user.files.store');
    Route::put('/user/files/{id}', [FilesController::class, 'update'])->name('user.files.update');
    Route::delete('/user/files/{id}', [FilesController::class, 'destroy'])->name('user.files.destroy');
    Route::get('/user/files/download/{id}', [FilesController::class, 'download'])->name('user.files.download');

    // Google Form for users
    Route::get('/user/google-forms', [App\Http\Controllers\GformController::class, 'userIndex'])->name('user.gform.index');

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Route untuk menampilkan submenu (dapat diakses oleh user dan admin)
Route::get('menus/{menu}/view', [MenuViewController::class, 'view'])->name('menus.view');

// Route untuk menampilkan submenu (khusus user)
Route::get('menus/{menu}/view', [MenuViewController::class, 'view'])->name('menus.view');

// Route untuk menampilkan gambar submenu (khusus user)
Route::get('menus/{menu}/sub/{submenu}/show', [MenuViewController::class, 'show'])->name('menus.sub.show');

// Route untuk menampilkan berita (khusus user)
Route::prefix('news')->name('user.newsview.')->group(function () {
    Route::get('/', [NewsViewController::class, 'index'])->name('index'); // Halaman daftar berita
    Route::get('/{id}', [NewsViewController::class, 'detail'])->name('detail'); // Halaman detail berita
});
