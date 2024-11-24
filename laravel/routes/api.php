<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KategoriBarangController;
use App\Http\Controllers\Api\ProdukController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('categories-item', KategoriBarangController::class);
Route::apiResource('products', ProdukController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('users', UserController::class);
