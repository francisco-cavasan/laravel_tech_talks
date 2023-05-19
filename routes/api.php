<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\UsersController;
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
    Route::get('', [UsersController::class, 'index']);
    Route::get('{user}', [UsersController::class, 'show']);
    Route::post('', [UsersController::class, 'store']);
    Route::put('{user}', [UsersController::class, 'update']);
    Route::delete('{user}', [UsersController::class, 'destroy']);
    Route::post('add-to-event', [UsersController::class, 'addToEvent']);
    Route::delete('{user}/{event}', [UsersController::class, 'removeFromEvent']);
});

Route::prefix('event')->group(function () {
    Route::get('', [EventsController::class, 'index']);
    Route::get('{event}', [EventsController::class, 'show']);
    Route::post('', [EventsController::class, 'store']);
    Route::put('{event}', [EventsController::class, 'update']);
    Route::delete('{event}', [EventsController::class, 'destroy']);
});
