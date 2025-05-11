<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    UserController
};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/')->group(function () {

    Route::prefix('auth/')->controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::get('user', 'getUserInfo');
        Route::post('logout', 'logout');
    });

    Route::prefix('users/')->controller(UserController::class)->group(function () {
        Route::post('', 'create');
    });

});

