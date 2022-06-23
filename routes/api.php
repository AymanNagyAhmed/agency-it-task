<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ReviewController;
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
    Route::group(['prefix' => 'admin', 'middleware' => 'is_admin'], function () {
        Route::get('users-list', [UserController::class, 'index'])->name('users-list');
        Route::resource("user", UserController::class);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [AuthController::class, 'show'])->name('show-user');
    });

    Route::group(['prefix' => 'reviews'], function () {
        Route::get("/", [ReviewController::class, "index"]);
        Route::get("/{review}", [ReviewController::class, "show"]);
    });
    Route::group(['prefix' => 'reviews', "middleware" => "is_admin"], function () {
        Route::post("/", [ReviewController::class, "store"]);
        Route::patch("/{review}", [ReviewController::class, "update"]);
        Route::put("/{review}", [ReviewController::class, "update"]);
        Route::delete("/{review}", [ReviewController::class, "destroy"]);
    });

    Route::group(['prefix' => 'feedbacks'], function () {
        Route::get("/", [FeedbackController::class, "index"]);
        Route::patch("/{feedback}", [FeedbackController::class, "update"]);
    });
    Route::group(['prefix' => 'feedbacks', "middleware" => "is_admin"], function () {
        Route::post("/", [FeedbackController::class, "store"]);
        Route::get("/{feedback}", [FeedbackController::class, "show"]);
        Route::put("/{feedback}", [FeedbackController::class, "update"]);
        Route::delete("/{feedback}", [FeedbackController::class, "destroy"]);
    });
});

Route::post('register', [AuthController::class, 'register'])->name('register');

Route::post('login', [AuthController::class, 'login'])->name('login');
