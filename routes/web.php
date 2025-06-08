<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminController,
    AduanController,
    AuthController,
    CopelandTestingController,
    FasilitasController,
    FormPelaporanController,
    JurusanController,
    GedungController,
    KategoriController,
    KriteriaController,
    LokasiController,
    MahasiswaController,
    PengaduanController,
    PengaduanSarprasController,
    PerbaikanController,
    PerbaikanSarprasController,
    ProfilController,
    RiwayatMahasiswaController,
    RiwayatTeknisiController,
    RoleController,
    SarprasController,
    TeknisiController,
    WelcomeController,
    PeriodeController,
    PrometheeController,
    SarprasPenugasanController
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
        Route::post('/import', [AdminController::class, 'import_file'])->name('admin.pengguna.import_file');
        Route::get('/{user}/confirm', [AdminController::class, 'confirm_ajax'])->name('admin.pengguna.confirm_ajax');
        Route::get('/{user}/show_ajax', [AdminController::class, 'show_ajax'])->name('admin.pengguna.show_ajax');
        Route::get('/{user}/edit_ajax', [AdminController::class, 'edit_ajax'])->name('admin.pengguna.edit_ajax');
        Route::put('/{user}/edit_ajax', [AdminController::class, 'update_ajax'])->name('admin.pengguna.update_ajax');
        Route::delete('/{user}/remove_ajax', [AdminController::class, 'remove_ajax'])->name('admin.pengguna.delete_ajax');
        Route::get('/export_excel', [AdminController::class, 'export_excel'])->name('admin.pengguna.export_excel');
        Route::get('/export_pdf', [AdminController::class, 'export_pdf'])->name('admin.pengguna.export_pdf');
    });

    // Role
    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.role');
        Route::get('/create', [RoleController::class, 'create_ajax'])->name('admin.role.create_ajax');
        Route::post('/store', [RoleController::class, 'store_ajax'])->name('admin.role.store_ajax');
        Route::get('/import', [RoleController::class, 'import_ajax'])->name('admin.role.import_ajax');
        Route::get('/import_file', [RoleController::class, 'import_file'])->name('admin.role.import_file');
        Route::get('/{role}/show_ajax', [RoleController::class, 'show_ajax'])->name('admin.role.show_ajax');
        Route::get('/{role}/edit_ajax', [RoleController::class, 'edit_ajax'])->name('admin.role.edit_ajax');
        Route::put('/{role}/update_ajax', [RoleController::class, 'update_ajax'])->name('admin.role.update_ajax');
        Route::get('/{role}/confirm_ajax', [RoleController::class, 'confirm_ajax'])->name('admin.role.confirm_ajax');
        Route::delete('/{role}/remove_ajax', [RoleController::class, 'destroy_ajax'])->name('admin.role.destroy_ajax');
        Route::get('/export_excel', [RoleController::class, 'export_excel'])->name('admin.role.export_excel');
        Route::get('/export_pdf', [RoleController::class, 'export_pdf'])->name('admin.role.export_pdf');
    });

    // Jurusan
    Route::prefix('jurusan')->group(function () {
        Route::get('/', [JurusanController::class, 'index'])->name('admin.jurusan');
        Route::get('/create', [JurusanController::class, 'create'])->name('admin.jurusan.create');
        Route::post('/store', [JurusanController::class, 'store'])->name('admin.jurusan.store');
        Route::get('/{jurusan}/confirm', [JurusanController::class, 'confirm'])->name('admin.jurusan.confirm');
        Route::get('/{jurusan}/show', [JurusanController::class, 'show'])->name('admin.jurusan.show');
        Route::get('/{jurusan}/edit', [JurusanController::class, 'edit'])->name('admin.jurusan.edit');
        Route::put('/{jurusan}/update', [JurusanController::class, 'update'])->name('admin.jurusan.update');
        Route::delete('/{jurusan}/destroy', [JurusanController::class, 'destroy'])->name('admin.jurusan.destroy');
        Route::get('/export_pdf', [JurusanController::class, 'export_pdf'])->name('admin.jurusan.export_pdf');
        Route::get('/export_excel', [JurusanController::class, 'export_excel'])->name('admin.jurusan.export_excel');
    });

    // Fasilitas
    Route::prefix('fasilitas')->group(function () {
        Route::get('/', [FasilitasController::class, 'index'])->name('admin.fasilitas');
        Route::get('/create', [FasilitasController::class, 'create'])->name('admin.fasilitas.create');
        Route::post('/store', [FasilitasController::class, 'store'])->name('admin.fasilitas.store');
        Route::get('/import', [FasilitasController::class, 'import'])->name('admin.fasilitas.import');
        Route::post('/import_file', [FasilitasController::class, 'import_file'])->name('admin.fasilitas.import_file');
        Route::get('/{fasilitas}/confirm', [FasilitasController::class, 'confirm'])->name('admin.fasilitas.confirm');
        Route::get('/{fasilitas}/show', [FasilitasController::class, 'show'])->name('admin.fasilitas.show');
        Route::get('/{fasilitas}/edit', [FasilitasController::class, 'edit'])->name('admin.fasilitas.edit');
        Route::put('/{fasilitas}/update', [FasilitasController::class, 'update'])->name('admin.fasilitas.update');
        Route::delete('/{fasilitas}/destroy', [FasilitasController::class, 'destroy'])->name('admin.fasilitas.destroy');
        Route::get('/get-lantai/{id_gedung}', [FasilitasController::class, 'getLantai']);
        Route::get('/get-ruangan/{id_lantai}', [FasilitasController::class, 'getRuangan']);
        Route::get('/export_pdf', [FasilitasController::class, 'export_pdf'])->name('admin.fasilitas.export_pdf');
        Route::get('/export_excel', [FasilitasController::class, 'export_excel'])->name('admin.fasilitas.export_excel');
    });

    // Kategori Fasilitas
    Route::prefix('kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('admin.kategori');
        Route::get('/create', [KategoriController::class, 'create'])->name('admin.kategori.create');
        Route::post('/store', [KategoriController::class, 'store'])->name('admin.kategori.store');
        Route::get('/import', [KategoriController::class, 'import'])->name('admin.kategori.import');
        Route::post('/import_file', [KategoriController::class, 'import_file'])->name('admin.kategori.import_file');
        Route::get('/{kategori}/confirm', [KategoriController::class, 'confirm'])->name('admin.kategori.confirm');
        Route::get('/{kategori}/show', [KategoriController::class, 'show'])->name('admin.kategori.show');
        Route::get('/{kategori}/edit', [KategoriController::class, 'edit'])->name('admin.kategori.edit');
        Route::put('/{kategori}/update', [KategoriController::class, 'update'])->name('admin.kategori.update');
        Route::delete('/{kategori}/destroy', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');
        Route::get('/export_pdf', [KategoriController::class, 'export_pdf'])->name('admin.kategori.export_pdf');
        Route::get('/export_excel', [KategoriController::class, 'export_excel'])->name('admin.kategori.export_excel');
    });

    // Aduan
    Route::prefix('aduan')->group(function () {
        Route::get('/', [AduanController::class, 'index'])->name('admin.aduan');
        Route::get('/{id}/show_ajax', [AduanController::class, 'show_ajax'])->name('admin.aduan.show_ajax');
        Route::get('/{id}/comment_ajax', [AduanController::class, 'comment_ajax'])->name('admin.aduan.comment_ajax');
        Route::get('/ekspor_pdf', [AduanController::class, 'ekspor_pdf'])->name('admin.aduan.ekspor_pdf');
        Route::get('/ekspor_excel', [AduanController::class, 'ekspor_excel'])->name('admin.aduan.ekspor_excel');
    });
    Route::prefix('periode')->group(function () {
        Route::get('/', [PeriodeController::class, 'index'])->name('admin.periode');
        Route::get('/create', [PeriodeController::class, 'create_ajax'])->name('admin.periode.create_ajax');
        Route::post('/store', [PeriodeController::class, 'store_ajax'])->name('admin.periode.store_ajax');
        Route::get('/{periode}/show_ajax', [PeriodeController::class, 'show_ajax'])->name('admin.periode.show_ajax');
        Route::get('/{periode}/edit_ajax', [PeriodeController::class, 'edit_ajax'])->name('admin.periode.edit_ajax');
        Route::put('/{periode}/edit_ajax', [PeriodeController::class, 'update_ajax'])->name('admin.periode.update_ajax');
        Route::get('/{periode}/confirm_ajax', [PeriodeController::class, 'confirm_ajax'])->name('admin.periode.confirm_ajax');
        Route::delete('/{periode}/remove_ajax', [PeriodeController::class, 'remove_ajax'])->name('admin.periode.delete_ajax');
    });
});

// Mahasiswa, Dosen, Tendik
Route::prefix('pelapor')->middleware(['authorize:MAHASISWA|DOSEN|TENDIK'])->group(function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('dashboard.mahasiswa');
    Route::get('/sop/download/{filename}', [MahasiswaController::class, 'SOPDownload'])->name('download.sopmhs');

    // Profil
    Route::prefix('profil')->middleware(['auth'])->group(function () {
        Route::get('/', [ProfilController::class, 'index'])->name('profil');
        Route::get('/edit_ajax', [ProfilController::class, 'edit_ajax'])->name('profil.edit_ajax');
        Route::put('/{id}/update_ajax', [ProfilController::class, 'update_ajax']);
    });

    // Form & Riwayat Mahasiswa
    Route::prefix('form')->group(function () {
        Route::get('/', [FormPelaporanController::class, 'index'])->name('mahasiswa.form');
        Route::get('/create', [FormPelaporanController::class, 'create'])->name('mahasiswa.form.create');
        Route::post('/store', [FormPelaporanController::class, 'store'])->name('mahasiswa.form.store');
        Route::get('/{id}/show_ajax', [FormPelaporanController::class, 'show_ajax'])->name('mahasiswa.form.show_ajax');
        Route::get('/{aduan}/edit', [FormPelaporanController::class, 'edit'])->name('mahasiswa.form.edit');
        Route::put('/{aduan}/update', [FormPelaporanController::class, 'update'])->name('mahasiswa.form.update');
        Route::get('/get-lantai/{id_gedung}', [FormPelaporanController::class, 'getLantai'])->name('mahasiswa.form.get_lantai');
        Route::get('/get-ruangan/{id_lantai}', [FormPelaporanController::class, 'getRuangan'])->name('mahasiswa.form.get_ruangan');
        Route::get('/get-fasilitas/{id_ruangan}', [FormPelaporanController::class, 'getFasilitas'])->name('mahasiswa.form.get_fasilitas');
    });

    Route::prefix('riwayat')->group(function () {
        Route::get('/', [RiwayatMahasiswaController::class, 'index'])->name('mahasiswa.riwayat');
        Route::get('/{id}/show_ajax', [RiwayatMahasiswaController::class, 'show_ajax'])->name('mahasiswa.riwayat.show_ajax');
        Route::get('/{aduan}/edit', [RiwayatMahasiswaController::class, 'edit'])->name('mahasiswa.riwayat.edit');
        Route::post('/{aduan}/store', [RiwayatMahasiswaController::class, 'storeUlasan'])->name('mahasiswa.riwayat.store_ulasan');
    });
});

// Sarpras
Route::prefix('sarpras')->middleware(['authorize:SARPRAS'])->group(function () {

    Route::get('/', [SarprasController::class, 'index'])->name('sarpras.dashboard');
    Route::get('/sop/download/{filename}', [SarprasController::class, 'SOPDownload'])->name('download.sop');

    Route::prefix('penugasan')->group(function () {
        Route::get('/', [SarprasPenugasanController::class, 'index'])->name('sarpras.penugasan');

        Route::get('/calculate-promethee', [SarprasPenugasanController::class, 'calculatePromethee'])->name('coba-hitung');
        // Route::get('/{id}/show_ajax', [SarprasPenugasanController::class, 'show_ajax'])->name('mahasiswa.riwayat.show_ajax');
        // Route::get('/{id}/edit_ajax', [SarprasPenugasanController::class, 'edit_ajax'])->name('mahasiswa.riwayat.edit_ajax');
    });

    Route::prefix('bobot')->group(function () {
        Route::get('/', [KriteriaController::class, 'index'])->name('sarpras.bobot');
        Route::get('/edit', [KriteriaController::class, 'edit'])->name('sarpras.bobot.edit');
        Route::put('/update', [KriteriaController::class, 'update'])->name('sarpras.bobot.update');
        Route::get('/export_pdf', [KriteriaController::class, 'export_pdf'])->name('sarpras.bobot.export_pdf');
        Route::get('/export_excel', [KriteriaController::class, 'export_excel'])->name('sarpras.bobot.export_excel');
    });

    Route::prefix('kriteria')->group(function () {
        Route::post('/list', [KriteriaController::class, 'list']);
    });

    Route::prefix('perbaikan')->group(function () {
        Route::get('/', [PerbaikanSarprasController::class, 'index'])->name('sarpras.perbaikan');
        Route::get('/{id}/show_ajax', [PerbaikanSarprasController::class, 'show_ajax'])->name('sarpras.perbaikan.show');
        Route::get('/{id}/approve', [PerbaikanSarprasController::class, 'show_ajax'])->name('sarpras.perbaikan.approve');
    });
});

Route::middleware(['authorize:SARPRAS'])->group(function () {
    Route::get('/pengaduan', [PengaduanSarprasController::class, 'index'])->name('sarpras.pengaduan');
    Route::get('/pengaduan/{id}/detail_pengaduan', [PengaduanSarprasController::class, 'show_pengaduan'])->name('sarpras.pengaduan.show');
    Route::get('/pengaduan/{id}/penugasan_teknisi', [PengaduanSarprasController::class, 'penugasan_teknisi'])->name('sarpras.pengaduan.edit');
    Route::put('/pengaduan/{id}/confirm_penugasan', [PengaduanSarprasController::class, 'confirm_penugasan'])->name('sarpras.pengaduan.update');
    Route::get('/penugasan', [FasilitasController::class, 'penugasan']);
    Route::get('/perbaikan', [PerbaikanSarprasController::class, 'perbaikan']);
    Route::get('/perbaikan', [PerbaikanSarprasController::class, 'riwayat']);
});

// Teknisi
Route::prefix('teknisi')->middleware(['authorize:TEKNISI'])->group(function () {
    Route::get('/', [TeknisiController::class, 'index'])->name('teknisi.dashboard');

    Route::prefix('riwayat')->group(function () {
        Route::get('/', [RiwayatTeknisiController::class, 'index'])->name('teknisi.riwayat');
        Route::get('/{id}/show_ajax', [RiwayatTeknisiController::class, 'show_ajax'])->name('teknisi.riwayat.show_ajax');
        Route::get('/export-excel', [RiwayatTeknisiController::class, 'export_excel'])->name('teknisi.perbaikan.export_excel');
        Route::get('/export-pdf', [RiwayatTeknisiController::class, 'export_pdf'])->name('teknisi.perbaikan.export_pdf');
    });
});

use App\Http\Helpers\CopelandAggregator;
use App\Http\Helpers\AlternativeDTO;

Route::get('/test-gdss', [CopelandTestingController::class, 'copelandTest'])->name('test.gdss');
Route::get('/hitung', [PrometheeController::class, 'calculatePromethee'])->name('sarpras.hitung'); // TES PROMETHEE
Route::get('/tesMahasiswa', [PrometheeController::class, 'tesHitungMahasiswa'])->name('sarpras.tesMahasiswa');
Route::get('/tesDosen', [PrometheeController::class, 'tesHitungDosen'])->name('sarpras.tesDosen');
Route::get('/tesTendik', [PrometheeController::class, 'tesHitungTendik'])->name('sarpras.tesTendik');
