<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
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
