<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    UserController,
    EventController
};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::prefix('auth/')->controller(AuthController::class)->group(function () {
        Route::get('user', 'getUserInfo');
        Route::post('logout', 'logout');
    })->middleware('jwt.verify');

    Route::prefix('users/')->controller(UserController::class)->group(function () {
        Route::post('', 'create');
    });

    Route::prefix('events/')->controller(EventController::class)->group(function() {
        Route::get('', 'index');
        Route::post('', 'create');
        Route::get('{slug}/detail', 'show');
    });
});

