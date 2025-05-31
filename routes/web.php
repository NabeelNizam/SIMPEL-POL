<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AduanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\FormPelaporanController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RiwayatMahasiswaController;
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
      Route::get('/{user}/confirm', [AdminController::class, 'confirm_ajax'])->name('admin.pengguna.confirm_ajax');
      Route::get('/{user}/show_ajax', [AdminController::class, 'show_ajax'])->name('admin.pengguna.show_ajax');
      Route::get('/{user}/edit_ajax', [AdminController::class, 'edit_ajax'])->name('admin.pengguna.edit_ajax');
      Route::put('/{user}/edit_ajax', [AdminController::class, 'update_ajax'])->name('admin.pengguna.update_ajax');
      Route::delete('/{user}/remove_ajax', [AdminController::class, 'remove_ajax'])->name('admin.pengguna.delete_ajax');
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
    Route::prefix('aduan')->group(function () {
        Route::get('/', [AduanController::class, 'index'])->name('admin.aduan');
        Route::get('/{id}/show_ajax', [AduanController::class, 'show_ajax'])->name('admin.aduan.show_ajax');
        Route::get('/ekspor_pdf', [AduanController::class, 'ekspor_pdf'])->name('admin.aduan.ekspor_pdf');
        Route::get('/ekspor_excel', [AduanController::class, 'ekspor_excel'])->name('admin.aduan.ekspor_excel');
    });
});
Route::group(['prefix' => 'user', 'middleware' => ['authorize:MAHASISWA|DOSEN|TENDIK']], function () {
    // Routes dashboard
    Route::get('/', [MahasiswaController::class, 'index'])->name('dashboard.mahasiswa');
});
Route::group(['prefix' => 'profil', 'middleware' => ['auth']], function () {
    Route::get('/', [ProfilController::class, 'index'])->name('profil');
    Route::get('/edit_ajax', [ProfilController::class, 'edit_ajax'])->name('profil.edit_ajax');
    Route::put('/{id}/update_ajax', [ProfilController::class, 'update_ajax']);
});
    Route::get('/sop/download/{filename}', [MahasiswaController::class, 'SOPdownload'])->name('download.sop');
    // routes form
    Route::prefix('form')->group(function () {
        Route::get('/', [FormPelaporanController::class, 'index'])->name('mahasiswa.form');
        Route::get('/create', [FormPelaporanController::class, 'create'])->name('mahasiswa.form.create_ajax');
        Route::post('/store', [FormPelaporanController::class, 'store'])->name('mahasiswa.form.store_ajax');
        Route::get('/{id}/show_ajax', [FormPelaporanController::class, 'show_ajax'])->name('mahasiswa.form.show_ajax');
        Route::get('/{id}/edit_ajax', [FormPelaporanController::class, 'edit_ajax'])->name('mahasiswa.form.edit_ajax');
        Route::post('/{id}/edit_ajax', [FormPelaporanController::class, 'update_ajax'])->name('mahasiswa.form.update_ajax');
    });
    // routes riwayat
    Route::prefix('riwayat')->group(function () {
        Route::get('/', [RiwayatMahasiswaController::class, 'index'])->name('mahasiswa.riwayat');
        Route::get('/{id}/show_ajax', [RiwayatMahasiswaController::class, 'show_ajax'])->name('mahasiswa.riwayat.show_ajax');
        Route::get('/{id}/edit_ajax', [RiwayatMahasiswaController::class, 'edit_ajax'])->name('mahasiswa.riwayat.edit_ajax');
    });
});