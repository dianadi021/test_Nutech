<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Master\KategoriBarangController;
use App\Http\Controllers\Web\Logistik\ProdukController;

use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name("welcome");
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'master'], function () {
        Route::get('/kategori-produk', [KategoriBarangController::class, 'index'])->name('master.kategori.produk');
    });

    Route::group(['prefix' => 'logistik'], function () {
        Route::get('/produk', [ProdukController::class, 'index'])->name('logistik.barang.produk');
    });
});

require __DIR__.'/auth.php';
