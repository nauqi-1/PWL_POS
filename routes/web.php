<?php
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\WelcomeController;

use Illuminate\Support\Facades\Route;

Route::get('/level', [LevelController::class, 'index']);

Route::get('/test', function() {
    return 'Hey';
});


Route::get('/kategori', [KategoriController::class, 'index']);

Route::get('/', [WelcomeController::class,'index']);

Route::group(['prefix' => 'user'], function() {
    Route::get('/', [UserController::class, 'index']); //Menampilkan laman awal user
    Route::post('/list', [UserController::class, 'list']); //menampilkan data user dalam bentuk json untuk datatables.
    Route::get('/create', [UserController::class, 'create']); //Membuat data baru
    Route::post('/', [UserController::class, 'store']); //Menyimpan data yang telah dibuat
    Route::get('/{id}', [UserController::class, 'show']); //menampilkan detail data user
    Route::get('/{id}/edit', [UserController::class, 'edit']); //Edit data user tertentu
    Route::put('/{id}', [UserController::class, 'update']); //Menyimpan perubahan data user 
    Route::delete('/{id}', [UserController::class, 'destroy']); //Menghapus data user
} );