<?php

use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use PhpParser\Node\Stmt\Return_;

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

Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', \App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('/logout', \App\Http\Controllers\Api\LogoutController::class)->name('logout');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'levels'], function(){
    Route::get('/', [LevelController::class, 'index']);
    Route::post('/', [LevelController::class, 'store']);
    Route::get('/{level}', [LevelController::class, 'show']);
    Route::put('/{level}', [LevelController::class, 'update']);
    Route::delete('/{level}', [LevelController::class, 'destroy']);
}); 

Route::group(['prefix'=>'users'], function(){
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
}); 
Route::group(['prefix'=>'kategoris'], function(){
    Route::get('/', [KategoriController::class, 'index']);
    Route::post('/', [KategoriController::class, 'store']);
    Route::get('/{kategori}', [KategoriController::class, 'show']);
    Route::put('/{kategori}', [KategoriController::class, 'update']);
    Route::delete('/{kategori}', [KategoriController::class, 'destroy']);
});

Route::group(['prefix'=>'barangs'], function(){
    Route::get('/', [BarangController::class, 'index']);
    Route::post('/', [BarangController::class, 'store']);
    Route::get('/{barang}', [BarangController::class, 'show']);
    Route::put('/{barang}', [BarangController::class, 'update']);
    Route::delete('/{barang}', [BarangController::class, 'destroy']);
});

//route utk API mobile
Route::post('all_data', [APIController::class,'index']);
Route::post('create_data', [APIController::class,'store']);
Route::post('show_data', [APIController::class,'show']);
Route::post('edit_data', [APIController::class,'edit']);
Route::post('delete_data', [APIController::class,'destroy']);
