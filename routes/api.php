<?php

//use Illuminate\Http\Request;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
}); */

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [LoginController::class, 'login']);
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::get('messages/{sender}', [MessageController::class, 'index']);
        Route::post('messages/create/{sender}', [MessageController::class, 'store']);
        Route::get('user', [UserController::class, 'index']);
        Route::put('set_online', [UserController::class, 'setOnline']);
        Route::post('typing/{sender}', [MessageController::class, 'typing']);
        Route::post('check_numbers', [UserController::class, 'checkNumbers']);
    });
});