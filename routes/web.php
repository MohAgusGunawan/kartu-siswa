<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MigrateController;

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

/*
|--------------------------------------------------------------------------
| Halaman Form
|--------------------------------------------------------------------------
*/
Route::get('/', [FormController::class, 'index'])->name('form.index');
/*
|--------------------------------------------------------------------------
| Halaman API Siswa
|--------------------------------------------------------------------------
*/
Route::get('/data-siswa', function () {
    return view('data-siswa');
});
Route::get('/migrate-data', function () {
    return view('migrate-data');
});
Route::post('/migrate-siswa-to-slims', [MigrateController::class, 'migrateSiswaToSlims']);
Route::get('/migrate-photos', [MigrateController::class, 'migratePhotos']);
/*
|--------------------------------------------------------------------------
| Halaman Login
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
/*
|--------------------------------------------------------------------------
| Halaman Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware('auth:pengguna')->group(function () {
    Route::resource('dashboard', DashboardController::class);
    Route::put('/route/update/{id}', [DashboardController::class, 'update'])->name('route.update');
});
/*
|--------------------------------------------------------------------------
| CRUD
|--------------------------------------------------------------------------
*/
Route::resource('form', FormController::class);
/*
|--------------------------------------------------------------------------
| Download Laporan
|--------------------------------------------------------------------------
*/
Route::get('/form/download/1', [FormController::class, 'downloadReport'])->name('form.downloadReport');
Route::get('/download/class-pdf', [FormController::class, 'downloadClassPdf'])->name('form.downloadClassPdf');
Route::get('/download/card-pdf', [FormController::class, 'downloadCardPdf'])->name('form.downloadCardPdf');
