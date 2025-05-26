<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\RiwayatPelaporanController;
use App\Http\Controllers\SOPController;
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
Route::group(['prefix' => 'admin', 'middleware' => ['authorize:ADMIN']], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/manageUser', [AdminController::class, 'user']);
});
Route::group(['prefix' => 'user', 'middleware' => ['authorize:MAHASISWA']], function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('dashboard.mahasiswa');
    // Routes untuk SOP
    Route::get('/sop/download/{filename}', [MahasiswaController::class, 'SOPdownload'])->name('download.sop');
});
