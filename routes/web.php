<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\DisplayController;
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

// Halaman utama untuk menampilkan antrian
Route::get('/', [QueueController::class, 'index'])->name('home');

// Ambil nomor antrian
Route::post('/ambil-antrian/{category}', [QueueController::class, 'store'])->name('queue.store');

// Cetak nomor antrian
Route::post('/print-queue', [PrintController::class, 'printQueue'])->name('print.queue');

//Display
Route::get('/display', [DisplayController::class, 'index'])->name('display.index');
