<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminController, AduanController, AuthController, FasilitasController,
    FormPelaporanController, JurusanController, GedungController,
    KriteriaController, MahasiswaController, PerbaikanSarprasController,
    ProfilController, RiwayatMahasiswaController, RiwayatTeknisiController,
    RoleController, SarprasController, TeknisiController, WelcomeController, PeriodeController
};

// Auth & Welcome
Route::get('/', [WelcomeController::class, 'index']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postMasuk']);
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->middleware(['authorize:ADMIN'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    // Pengguna
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

    // Role
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

    // Jurusan
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

    // Fasilitas
    Route::prefix('fasilitas')->group(function () {
        Route::get('/', [FasilitasController::class, 'index'])->name('admin.fasilitas');
        Route::get('/create', [FasilitasController::class, 'create'])->name('admin.fasilitas.create');
        Route::post('/store', [FasilitasController::class, 'store'])->name('admin.fasilitas.store');
        Route::get('/import', [FasilitasController::class, 'import_ajax'])->name('admin.fasilitas.import_ajax');
        Route::get('/{id}/show_ajax', [FasilitasController::class, 'show_ajax'])->name('admin.fasilitas.show_ajax');
        Route::get('/{id}/edit_ajax', [FasilitasController::class, 'edit_ajax'])->name('admin.fasilitas.edit_ajax');
        Route::post('/{id}/edit_ajax', [FasilitasController::class, 'update_ajax'])->name('admin.fasilitas.update_ajax');
        Route::delete('/{id}/remove_ajax', [FasilitasController::class, 'remove_ajax'])->name('admin.fasilitas.delete_ajax');
        Route::get('/get-lantai/{id_gedung}', [FasilitasController::class, 'getLantai']);
        Route::get('/get-ruangan/{id_lantai}', [FasilitasController::class, 'getRuangan']);
    });

    // Aduan
    Route::prefix('aduan')->group(function () {
        Route::get('/', [AduanController::class, 'index'])->name('admin.aduan');
        Route::get('/{id}/show_ajax', [AduanController::class, 'show_ajax'])->name('admin.aduan.show_ajax');
        Route::get('/ekspor_pdf', [AduanController::class, 'ekspor_pdf'])->name('admin.aduan.ekspor_pdf');
        Route::get('/ekspor_excel', [AduanController::class, 'ekspor_excel'])->name('admin.aduan.ekspor_excel');
    });
});

// Mahasiswa, Dosen, Tendik
Route::prefix('user')->middleware(['authorize:MAHASISWA|DOSEN|TENDIK'])->group(function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('dashboard.mahasiswa');
});

// Profil
Route::prefix('profil')->middleware(['auth'])->group(function () {
    Route::get('/', [ProfilController::class, 'index'])->name('profil');
    Route::get('/edit_ajax', [ProfilController::class, 'edit_ajax'])->name('profil.edit_ajax');
    Route::put('/{id}/update_ajax', [ProfilController::class, 'update_ajax']);
});

// Form & Riwayat Mahasiswa
Route::prefix('form')->group(function () {
    Route::get('/', [FormPelaporanController::class, 'index'])->name('mahasiswa.form');
    Route::get('/create', [FormPelaporanController::class, 'create'])->name('mahasiswa.form.create_ajax');
    Route::post('/store', [FormPelaporanController::class, 'store'])->name('mahasiswa.form.store_ajax');
    Route::get('/{id}/show_ajax', [FormPelaporanController::class, 'show_ajax'])->name('mahasiswa.form.show_ajax');
    Route::get('/{id}/edit_ajax', [FormPelaporanController::class, 'edit_ajax'])->name('mahasiswa.form.edit_ajax');
    Route::post('/{id}/edit_ajax', [FormPelaporanController::class, 'update_ajax'])->name('mahasiswa.form.update_ajax');
});

Route::prefix('riwayat')->group(function () {
    Route::get('/', [RiwayatMahasiswaController::class, 'index'])->name('mahasiswa.riwayat');
    Route::get('/{id}/show_ajax', [RiwayatMahasiswaController::class, 'show_ajax'])->name('mahasiswa.riwayat.show_ajax');
    Route::get('/{id}/edit_ajax', [RiwayatMahasiswaController::class, 'edit_ajax'])->name('mahasiswa.riwayat.edit_ajax');
});

// Sarpras
Route::prefix('sarpras')->middleware(['authorize:SARPRAS'])->group(function () {
    Route::get('/', [SarprasController::class, 'index'])->name('sarpras.dashboard');
    Route::get('/sop/download/{filename}', [SarprasController::class, 'SOPDownload'])->name('download.sop');

    Route::prefix('form')->group(function () {
        Route::get('/', [FormPelaporanController::class, 'index'])->name('mahasiswa.form');
        Route::get('/create', [FormPelaporanController::class, 'create_ajax'])->name('mahasiswa.form.create_ajax');
        Route::get('/get-lantai', [FormPelaporanController::class, 'getLantai'])->name('mahasiswa.form.get_lantai');
        Route::get('/get-ruangan', [FormPelaporanController::class, 'getRuangan'])->name('mahasiswa.form.get_ruangan');
        Route::get('/get-fasilitas', [FormPelaporanController::class, 'getFasilitas'])->name('mahasiswa.form.get_fasilitas');
        Route::post('/store', [FormPelaporanController::class, 'store'])->name('mahasiswa.form.store_ajax');
        Route::get('/{id}/show_ajax', [FormPelaporanController::class, 'show_ajax'])->name('mahasiswa.form.show_ajax');
        Route::get('/{id}/edit_ajax', [FormPelaporanController::class, 'edit_ajax'])->name('mahasiswa.form.edit_ajax');
        Route::post('/{id}/edit_ajax', [FormPelaporanController::class, 'update_ajax'])->name('mahasiswa.form.update_ajax');
    });

    Route::prefix('kriteria')->group(function () {
        Route::post('/list', [KriteriaController::class, 'list']);
    });

    Route::prefix('perbaikan')->group(function () {
        Route::get('/', [PerbaikanSarprasController::class, 'index'])->name('sarpras.perbaikan');
        Route::get('/{id}/show_ajax', [PerbaikanSarprasController::class, 'show_ajax'])->name('sarpras.perbaikan.show_ajax');
    });
});

// Teknisi
Route::prefix('teknisi')->middleware(['authorize:TEKNISI'])->group(function () {
    Route::get('/', [TeknisiController::class, 'index'])->name('teknisi.dashboard');

    Route::prefix('riwayat')->group(function () {
        Route::get('/', [RiwayatTeknisiController::class, 'index'])->name('teknisi.riwayat');
        Route::get('/{id}/show_ajax', [RiwayatTeknisiController::class, 'show_ajax'])->name('teknisi.riwayat.show_ajax');
    });
});
