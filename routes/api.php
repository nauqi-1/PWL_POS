<?php

use App\Http\Controllers\Api\LevelController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//route utk API mobile
Route::post('all_data', [APIController::class,'index']);
Route::post('create_data', [APIController::class,'store']);
Route::post('show_data', [APIController::class,'show']);
Route::post('edit_data', [APIController::class,'edit']);
Route::post('delete_data', [APIController::class,'destroy']);
