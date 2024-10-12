<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;

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
