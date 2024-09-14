<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', WelcomeController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', \App\Http\Livewire\Dashboard::class)->name('dashboard');
//     Route::get('/transaksi', \App\Http\Livewire\Transaksi::class);
// });

Route::middleware(['auth', 'role:kasir|manager|produksi'])->group(function () {
    Route::get('/dashboard', \App\Http\Livewire\Dashboard::class);
    Route::get('/progres', \App\Http\Livewire\Progres::class);
});

Route::middleware(['auth', 'role:kasir|manager'])->group(function () {
    Route::get('/konsumen', \App\Http\Livewire\Konsumen::class);
});

Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/layanan', \App\Http\Livewire\Layanan::class);
    Route::get('/pembayaran', \App\Http\Livewire\Pembayaran::class);
    Route::get('/transaksi', \App\Http\Livewire\Transaksi::class);
});

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/laporan', \App\Http\Livewire\Konsumen::class);
});

Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/pelanggan/list', \App\Http\Livewire\Pelanggan::class);
});
