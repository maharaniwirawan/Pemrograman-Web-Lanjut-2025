<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users/data', [UserController::class, 'getData']);

Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
Route::get('/barang/data', [BarangController::class, 'data'])->name('barang.data');
Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');