<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
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

Route::prefix('user')->group(function () {
    Route::get('', [UserController::class, 'index']);
    Route::get('{user}', [UserController::class, 'show']);
    Route::post('', [UserController::class, 'store']);
    Route::put('{user}', [UserController::class, 'update']);
    Route::delete('{user}', [UserController::class, 'destroy']);
    Route::post('add-to-event', [UserController::class, 'addToEvent']);
    Route::delete('{user}/{event}', [UserController::class, 'removeFromEvent']);
});

Route::prefix('event')->group(function () {
    Route::get('', [EventController::class, 'index']);
    Route::get('{event}', [EventController::class, 'show']);
    Route::post('', [EventController::class, 'store']);
    Route::put('{event}', [EventController::class, 'update']);
    Route::delete('{event}', [EventController::class, 'destroy']);
});
