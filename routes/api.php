<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\AddProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/stores', [StoreController::class, 'index']);
Route::post('/stores', [StoreController::class, 'store']);
Route::get('/stores/{id}', [StoreController::class, 'show']);
Route::put('/stores/{id}', [StoreController::class, 'update']);
Route::delete('/stores/{id}', [StoreController::class, 'destroy']);



Route::get('/products', [AddProductController::class, 'index']); // GET semua produk
Route::post('/products', [AddProductController::class, 'store']); // POST produk baru
Route::get('/products/{id}', [AddProductController::class, 'show']); // GET produk by ID
Route::put('/products/{id}', [AddProductController::class, 'update']); // PUT update produk
Route::delete('/products/{id}', [AddProductController::class, 'destroy']); // DELETE produk
