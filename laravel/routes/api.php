<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KategoriBarangController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('kategori-barang', KategoriBarangController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('users', UserController::class);
