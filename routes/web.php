<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [WelcomeController::class, 'index']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postMasuk']);
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::group(['prefix' => 'admin', 'middleware' => ['authorize:ADMIN']], function () {
    //dashboard
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
   // Route pengguna
    Route::prefix('pengguna')->group(function () {
    Route::get('/', [AdminController::class, 'pengguna'])->name('admin.pengguna');
    Route::get('/create', [AdminController::class, 'create_ajax'])->name('admin.pengguna.create_ajax');
    Route::post('/store', [AdminController::class, 'store_ajax'])->name('admin.pengguna.store_ajax');
    Route::get('/import', [AdminController::class, 'import_ajax'])->name('admin.pengguna.import_ajax');
    Route::get('/confirm', [AdminController::class, 'confirm_ajax'])->name('admin.pengguna.confirm_ajax');
    Route::get('/{id}/show_ajax', [AdminController::class, 'show_ajax'])->name('admin.pengguna.show_ajax');
    Route::get('/{id}/edit_ajax', [AdminController::class, 'edit_ajax'])->name('admin.pengguna.edit_ajax');
    Route::post('/{id}/edit_ajax', [AdminController::class, 'updated_ajax'])->name('admin.pengguna.update_ajax');
    Route::delete('/{id}/remove_ajax', [AdminController::class, 'remove_ajax'])->name('admin.pengguna.delete_ajax');
    });

    // Route role
    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.role');
        Route::get('/create', [RoleController::class, 'create_ajax'])->name('admin.role.create_ajax');
        Route::post('/store', [RoleController::class, 'store_ajax'])->name('admin.role.store_ajax');
        Route::get('/import', [RoleController::class, 'import_ajax'])->name('admin.role.import_ajax');
        Route::get('/{id}/show_ajax', [RoleController::class, 'show_ajax'])->name('admin.role.show_ajax');
        Route::get('/{id}/edit_ajax', [RoleController::class, 'edit_ajax'])->name('admin.role.edit_ajax');
        Route::post('/{id}/edit_ajax', [RoleController::class, 'updated_ajax'])->name('admin.role.update_ajax');
        Route::delete('/{id}/remove_ajax', [RoleController::class, 'remove_ajax'])->name('admin.role.delete_ajax');
    });
    Route::prefix('jurusan')->group(function () {
    Route::get('/', [JurusanController::class, 'index'])->name('admin.jurusan');
    Route::get('/create', [JurusanController::class, 'create_ajax'])->name('admin.jurusan.create_ajax');
    Route::post('/store', [JurusanController::class, 'store_ajax'])->name('admin.jurusan.store_ajax');
    Route::get('/import', [JurusanController::class, 'import_ajax'])->name('admin.jurusan.import_ajax');
    Route::get('/{id}/show_ajax', [JurusanController::class, 'show_ajax'])->name('admin.jurusan.show_ajax');
    Route::get('/{id}/edit_ajax', [JurusanController::class, 'edit_ajax'])->name('admin.jurusan.edit_ajax');
    Route::post('/{id}/edit_ajax', [JurusanController::class, 'update_ajax'])->name('admin.jurusan.update_ajax');
    Route::delete('/{id}/remove_ajax', [JurusanController::class, 'remove_ajax'])->name('admin.jurusan.delete_ajax');
});
Route::prefix('fasilitas')->group(function () {
    Route::get('/', [FasilitasController::class, 'index'])->name('admin.fasilitas');
    Route::get('/create', [FasilitasController::class, 'create_ajax'])->name('admin.fasilitas.create_ajax');
    Route::post('/store', [FasilitasController::class, 'store_ajax'])->name('admin.fasilitas.store_ajax');
    Route::get('/import', [FasilitasController::class, 'import_ajax'])->name('admin.fasilitas.import_ajax');
    Route::get('/{id}/show_ajax', [FasilitasController::class, 'show_ajax'])->name('admin.fasilitas.show_ajax');
    Route::get('/{id}/edit_ajax', [FasilitasController::class, 'edit_ajax'])->name('admin.fasilitas.edit_ajax');
    Route::post('/{id}/edit_ajax', [FasilitasController::class, 'update_ajax'])->name('admin.fasilitas.update_ajax');
    Route::delete('/{id}/remove_ajax', [FasilitasController::class, 'remove_ajax'])->name('admin.fasilitas.delete_ajax');
});


});
Route::group(['prefix' => 'user', 'middleware' => ['authorize:MAHASISWA']], function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('dashboard.mahasiswa');
});
Route::middleware(['authorize:SARPRAS'])->group(function () {
    Route::prefix('sarpras')->group(function () {
        Route::get('/bobot', [SarprasController::class, 'bobot']);
    });

    Route::prefix('kriteria')->group(function () {
        Route::post('/list', [KriteriaController::class, 'list']);
    });
});
