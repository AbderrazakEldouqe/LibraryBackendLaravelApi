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

    // Admin
    Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
    Route::apiResource('languages', \App\Http\Controllers\LanguageController::class);
    Route::apiResource('books', \App\Http\Controllers\BookController::class);
    Route::get('usersByRole/{roleId}', [\App\Http\Controllers\UserController::class, 'index']);

    // Admin And Bilbiothecaire
    Route::apiResource('borrowedBooks', \App\Http\Controllers\BorrowedBookController::class)->except(['store', 'show', 'update', 'destroy']);
    Route::put('borrowedBooks/according/{id}', [\App\Http\Controllers\BorrowedBookController::class, 'accordingReservation']);
    Route::put('borrowedBooks/returning/{id}', [\App\Http\Controllers\BorrowedBookController::class, 'returningBook']);
    Route::put('borrowedBooks/canceling/{id}', [\App\Http\Controllers\BorrowedBookController::class, 'cancelingReservationBook']);
    Route::get('borrowedBooks/delay', [\App\Http\Controllers\BorrowedBookController::class, 'delayedBorrowedBooks']);

    // Admin And User
    Route::apiResource('users', \App\Http\Controllers\UserController::class)->except(['index']);;

    // Adherent
    Route::get('adherentReservations/onGoingReservation', [\App\Http\Controllers\AdherentReservationController::class, 'onGoingReservationByUser']);
    Route::post('adherentReservations/reserve', [\App\Http\Controllers\AdherentReservationController::class, 'reserveBook']);
    Route::get('adherentReservations/unreturnedBooks', [\App\Http\Controllers\AdherentReservationController::class, 'unreturnedBooksByUser']);
    Route::get('adherentReservations/returnedBooks', [\App\Http\Controllers\AdherentReservationController::class, 'returnedBooksByUser']);
    Route::get('adherentReservations/canceledBooks', [\App\Http\Controllers\AdherentReservationController::class, 'canceledBooksByUser']);
});
