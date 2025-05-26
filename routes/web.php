<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
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
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/managePengguna', [AdminController::class, 'pengguna'])->name('admin.pengguna');
    Route::post('/list', [AdminController::class, 'list']);
    Route::get('/create', [AdminController::class, 'create_ajax'])->name('admin.create_ajax');
    Route::post('/store', [AdminController::class, 'store_ajax'])->name('admin.store_ajax');
    // Tambahkan di group route admin-mu
    Route::get('/{id}/show_ajax', [AdminController::class, 'show_ajax'])->name('admin.show_ajax');
    Route::get('/{id}/edit_ajax', [AdminController::class, 'edit_ajax'])->name('admin.edit_ajax');
    Route::delete('/{id}/remove_ajax', [AdminController::class, 'remove_ajax'])->name('admin.delete_ajax');
});
Route::group(['prefix' => 'user', 'middleware' => ['authorize:MAHASISWA']], function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('dashboard.mahasiswa');
});