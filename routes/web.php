<?php

use App\Http\Controllers\AuthController;
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
Route::get('/masuk', [AuthController::class, 'masuk'])->name('masuk');
Route::post('/masuk', [AuthController::class, 'postMasuk'])->name('post_masuk');
Route::get('/keluar', [AuthController::class, 'keluar'])->name('keluar');
