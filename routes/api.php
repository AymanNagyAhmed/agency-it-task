<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix'=>'admin','middleware'=>'is_admin'],function () {
        Route::get('users-list', [UserController::class,'index'])->name('users-list');
        Route::get('user/{id}', [UserController::class,'show'])->name('show-user-byid');
        Route::put('user/{id}', [UserController::class,'update'])->name('update-user');
        Route::delete('user/{id}', [UserController::class,'destroy'])->name('destroy-user');

    });
    Route::group(['prefix'=>'user'],function () {
        Route::get('/', [AuthController::class,'show'])->name('show-user');

    });
});

Route::post('register', [AuthController::class,'register'])->name('register');

Route::post('login',[AuthController::class,'login'])->name('login');
