<?php

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

Route::group([
    'prefix' => 'auth',
    'as' => 'api.auth.'
], function () {
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
});
Route::group(['middleware' => 'auth.jwt', 'as' => 'api.'], function () {
    Route::post('auth.logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('auth.logout');
    Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
    Route::apiResource('languages', \App\Http\Controllers\LanguageController::class);
    Route::apiResource('books', \App\Http\Controllers\BookController::class);
    Route::apiResource('borrowedBooks', \App\Http\Controllers\BorrowedBookController::class);
    Route::post('borrowedBooks/reserve', [\App\Http\Controllers\BorrowedBookController::class,'reserveBook']);
    Route::post('borrowedBooks/according', [\App\Http\Controllers\BorrowedBookController::class,'accordingReservation']);
    Route::post('borrowedBooks/returning', [\App\Http\Controllers\BorrowedBookController::class,'returningBook']);
    Route::post('borrowedBooks/canceling', [\App\Http\Controllers\BorrowedBookController::class,'cancelingReservationBook']);
});
