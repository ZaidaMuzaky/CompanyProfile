<?php

use App\Models\Achivements;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\FoldersController;
use App\Http\Controllers\UserGisController;
use App\Http\Controllers\AdminGisController;
use App\Http\Controllers\MenuFileController;
use App\Http\Controllers\MenuViewController;
use App\Http\Controllers\NewsViewController;
use App\Http\Controllers\AuditViewController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormImageController;
use App\Http\Controllers\MenuBrandController;
use App\Http\Controllers\MenuFileControlller;
use App\Http\Controllers\NewsVisitController;
use App\Http\Controllers\PartsViewController;
use App\Http\Controllers\ParetoViewController;
use App\Http\Controllers\AchivementsController;
use App\Http\Controllers\AuditUploadController;
use App\Http\Controllers\UserBacklogController;
use App\Http\Controllers\AdminApprovalController;
use App\Http\Controllers\BacklogHeaderController;
use App\Http\Controllers\ParetoProblemController;
use App\Http\Controllers\ParetoSectionController;
use App\Http\Controllers\PartUnscheduleController;
use App\Http\Controllers\UserInspectionController;
use App\Http\Controllers\UserStatusVIewController;
use App\Http\Controllers\AdminFormStatusController;
use App\Http\Controllers\AdminInspectionController;
use App\Http\Controllers\PartUnscheduleViewController;

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
// Pareto Problem management routes
Route::prefix('admin/pareto')->name('admin.pareto.')->group(function () {
    Route::get('/', [ParetoProblemController::class, 'index'])->name('index');
    Route::post('/store', [ParetoProblemController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ParetoProblemController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [ParetoProblemController::class, 'update'])->name('update');
    Route::delete('/{id}/destroy', [ParetoProblemController::class, 'destroy'])->name('destroy');

    Route::get('{id}/show', [ParetoProblemController::class, 'show'])->name('show');
});
// parto problem section management routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Define the route for menu sections
    Route::get('menuSections/{mainMenu}', [ParetoSectionController::class, 'index'])->name('menuSections.index');
    Route::post('menuSections', [ParetoSectionController::class, 'store'])->name('menuSections.store');
    Route::put('menuSections/{id}', [ParetoSectionController::class, 'update'])->name('menuSections.update');
    Route::delete('menuSections/{id}', [ParetoSectionController::class, 'destroy'])->name('menuSections.destroy');
    Route::get('menuSections/{id}/show', [ParetoSectionController::class, 'show'])->name('menuSections.show');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/menuSections/{menu_section_id}/brands', [MenuBrandController::class, 'index'])->name('menuBrands.index');
    Route::post('/menuBrands', [MenuBrandController::class, 'store'])->name('menuBrands.store');
    Route::put('/menuBrands/{id}', [MenuBrandController::class, 'update'])->name('menuBrands.update');
    Route::delete('/menuBrands/{id}', [MenuBrandController::class, 'destroy'])->name('menuBrands.destroy');

    Route::get('/admin/pareto/main/{id}', [MenuBrandController::class, 'main'])->name('pareto.main');
});

// menu file management routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Route untuk manajemen file dalam konteks menuBrand
    Route::resource('menuFiles', MenuFileController::class)->except(['show', 'create', 'edit']);

    // Route untuk menyimpan file
    Route::post('menuFiles/{menuBrand}', [MenuFileController::class, 'store'])->name('menuFiles.store');
});

// backlog header management routes
    Route::get('/admin/backlog/backlog-header', [BacklogHeaderController::class, 'edit'])->name('admin.backlog.backlog-header');
    Route::post('/admin/backlog/backlog-header', [BacklogHeaderController::class, 'updateImage'])->name('admin.backlog.backlog-header.update');

// backlog form all users
    Route::get('/admin/backlog/form-status', [AdminFormStatusController::class, 'index'])->name('admin.backlog.form-status');
    Route::delete('/admin/backlog/form-status/{id}', [AdminFormStatusController::class, 'destroy'])->name('admin.backlog.form-status.destroy');

// approval form
    Route::get('/approvals', [AdminApprovalController::class, 'index'])->name('admin.approvals');
    Route::post('/approvals/{id}/approve', [AdminApprovalController::class, 'approveForm'])->name('admin.approvals.approve');


    // display Inspection data admin
    Route::get('admin/inspection', [AdminInspectionController::class, 'show'])->name('admin.inspection.form-show');
    Route::delete('admin/inspection/{id}', [AdminInspectionController::class, 'destroy'])->name('admin.inspection.destroy');
    // inspection form approval
    Route::get('/inspection', [AdminInspectionController::class, 'index'])->name('admin.inspection.index');
    Route::post('/inspection/{id}/approve', [AdminInspectionController::class, 'approveForm'])->name('admin.inspection.approve');

// audit management
Route::prefix('admin/audit')->name('admin.audit.')->group(function () {
    Route::get('/', [AuditController::class, 'index'])->name('index');
    Route::post('/', [AuditController::class, 'store'])->name('store');
    Route::get('/{id}', [AuditController::class, 'show'])->name('show');
    Route::put('/{id}/update', [AuditController::class, 'update'])->name('update');
    Route::delete('/{id}', [AuditController::class, 'destroy'])->name('destroy');
// audit upload
Route::get('/{audit_id}/uploads', [AuditUploadController::class, 'index'])->name('upload.index');
    Route::post('/audit/upload', [AuditUploadController::class, 'store'])->name('upload.store');
    Route::put('/audit/upload/{id}/update', [AuditUploadController::class, 'update'])->name('upload.update');
    Route::delete('/audit/upload/{id}', [AuditUploadController::class, 'destroy'])->name('upload.destroy');

});

// GIS
// Admin CN Unit
Route::prefix('admin/cn-units')->name('admin.cn-units.')->group(function () {
    Route::get('/', [AdminGisController::class, 'index'])->name('index');
    Route::post('/', [AdminGisController::class, 'store'])->name('store');
    Route::put('/{id}', [AdminGisController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminGisController::class, 'destroy'])->name('destroy');

    Route::get('/{id}/links', [AdminGisController::class, 'addLink'])->name('addLink');
    Route::post('/{id}/links', [AdminGisController::class, 'storeLink'])->name('storeLink');
    Route::delete('/links/{id}', [AdminGisController::class, 'deleteLink'])->name('deleteLink');
    Route::put('/links/{id}', [AdminGisController::class, 'updateLink'])->name('updateLink');

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
// Route untuk melihat submenu dari menu tertentu
Route::get('menus/{menuId}/view', [MenuViewController::class, 'view'])->name('menus.view');

// Route untuk melihat file dan deskripsi dari submenu tertentu
Route::get('menus/{menuId}/submenus/{submenuId}/show', [MenuViewController::class, 'show'])->name('menus.sub.show');





// Route untuk menampilkan gambar submenu (khusus user)
Route::get('menus/{menu}/sub/{submenu}/show', [MenuViewController::class, 'show'])->name('menus.sub.show');

// Route untuk menampilkan berita (khusus user)
Route::prefix('news')->name('user.newsview.')->group(function () {
    Route::get('/', [NewsViewController::class, 'index'])->name('index'); // Halaman daftar berita
    Route::get('/{id}', [NewsViewController::class, 'detail'])->name('detail'); // Halaman detail berita
});

// upload image route
Route::post('/upload-image', [FormImageController::class, 'upload']);



//  pareto view route
Route::get('/user/pareto/{menuBrand}', [ParetoViewController::class, 'index'])->name('user.pareto.index');
Route::get('pareto/{menuBrand}', [ParetoViewController::class, 'index'])->name('user.pareto.index');

// backlog form route 
Route::get('/user/backlog/form', [UserBacklogController::class, 'form'])->name('user.backlog.form');
Route::post('/user/backlog/form', [UserBacklogController::class, 'store']);
Route::get('/user/backlog/show', [UserBacklogController::class, 'show'])->name('user.backlog.show');
Route::get('/user/backlog/show', [UserBacklogController::class, 'status'])->name('user.backlog.show');
Route::get('/backlog/{id}/edit', [UserBacklogController::class, 'edit'])->name('user.backlog.edit');
Route::put('/backlog/{id}/resubmit', [UserBacklogController::class, 'resubmit'])->name('user.backlog.resubmit');
Route::delete('user/backlog/{id}', [UserBacklogController::class, 'destroy'])->name('user.backlog.destroy');

// backlog all form status route
Route::get('/user/backlog', [UserStatusVIewController::class, 'index'])->name('user.backlog.index');
// view audit
Route::get('/audit/{id}', [AuditViewController::class, 'view'])->name('audit.view');

// case status
Route::put('/admin/approvals/update-case/{id}', [AdminApprovalController::class, 'updateCase'])->name('admin.approvals.updateCase');
// action inspection backlog
Route::post('action-inspection/{id}', [AdminApprovalController::class, 'updateActionInspection'])->name('update.action.inspection');
Route::get('/inspection/{id}/edit', [AdminApprovalController::class, 'edit'])->name('inspection.edit');

// inspection form
Route::get('/user/inspection/form', [UserInspectionController::class, 'form'])->name('user.inspection.form');
Route::post('/user/inspection/form', [UserInspectionController::class, 'store']);
// inspection show
Route::get('/user/inspection/show', [UserInspectionController::class, 'status'])->name('user.inspection.show');
Route::delete('user/inspection/{id}', [UserInspectionController::class, 'destroy'])->name('user.inspection.destroy');
// update case status
Route::put('/admin/inspection/update-case/{id}', [UserInspectionController::class, 'updateCase'])->name('user.inspection.updateCase');
Route::post('actionReview-inspection/{id}', [UserInspectionController::class, 'updateActionInspection'])->name('user.action.inspection');
// inspection all form status route
Route::get('/user/inspection', [UserInspectionController::class, 'index'])->name('user.inspection.index');
// edit inspection form
Route::get('/inspection/{id}/edit', [UserInspectionController::class, 'edit'])->name('user.inspection.edit');
Route::put('/inspection/{id}/resubmit', [UserInspectionController::class, 'resubmit'])->name('user.inspection.resubmit');

// gis management for users
Route::prefix('user/cn-units')->name('user.cn-units.')->group(function () {
    Route::get('/', [UserGisController::class, 'index'])->name('index');
    Route::get('/{id}/links', [UserGisController::class, 'showLinks'])->name('links');
});

});








Route::get('storage/{file}', function ($file) {
    return response()->file(storage_path('app/public/evidence/' . $file));
});
