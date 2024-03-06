<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//panggil ProductController sebagai object
use App\Http\Controllers\ProductController;
//panggil UserController sebagai object
use App\Http\Controllers\UserController;

//buat route untuk menambahkan data produk
Route::post('/login',[UserController::class,'login']);

Route::middleware(['jwt-auth'])->group(function() {
    //buat route untuk menambahkan data produk
Route::post('/product',[ProductController::class,'store']);

//route untuk menampilkan data keseuruhan
Route::get('/product',[ProductController::class,'showAll']);

//route untuk menampilkan data produk berdasarkan ID
Route::get('/product/{id}',[ProductController::class,'showById']);

//route untuk menampilkan data produk berdasarkan nama
Route::get('/product/search/product_name={product_name}',[ProductController::class,'showByName']);

//route untuk mengubah data produk berdasarkan ID produk
Route::put('/product/{id}',[ProductController::class,'update']);

//route untuk menghapus data produk berdasarkan ID produk
Route::delete('/product/{id}',[ProductController::class,'delete']);

});
