<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    InfoController,
    UserController,
    ContentController,
    EventController,
    NoticeController,
    SwimmingCategoryController,
    UserClassController,
    IAController,
    FileController,
    UserAssistanceController
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

    Route::prefix('info/')->controller(InfoController::class)->group(function () {
        Route::get('{user_id}', 'appInfo');
    });

    Route::prefix('users/')->controller(UserController::class)->group(function () {
        Route::get('', 'index');
        Route::post('', 'create');
        Route::get('search/{email}', 'searchByEmail');
        Route::delete('{user_id}', 'delete');
    });

    Route::prefix('contents/')->group(function() {

        Route::get('', [ContentController::class, 'index'])->middleware('jwt.verify');
        Route::get('{slug}/detail', [ContentController::class, 'show']);
        Route::delete('{slug}', [ContentController::class, 'delete']);

        Route::prefix('events/')->controller(EventController::class)->group(function() {
            Route::post('', 'create');
        });

        Route::prefix('notice/')->controller(NoticeController::class)->group(function() {
            Route::post('', 'create');
        });
    });


    Route::prefix('swimming-categories/')->controller(SwimmingCategoryController::class)->group(function() {
        Route::get('', 'index');
        Route::get('by-user/{user_id}', 'byUser');
        Route::post('assign-to-user', 'assignToUser');
    });

    Route::prefix('classes/')->controller(UserClassController::class)->group(function() {
        Route::get('{user_id}', 'classesByUser');
        Route::post('', 'assignClassesToUser');
    });

    Route::prefix('ia/')->controller(IAController::class)->group(function() {
        Route::get('chat/history/{user_id}', 'conversationByUser');
        Route::post('chat', 'chatIA');
    });

    Route::prefix('file/')->controller(FileController::class)->group(function() {
        Route::post('upload', 'uploadFile');
        Route::post('delete', 'deleteFile');
    });

    Route::prefix('assistances/')->controller(UserAssistanceController::class)->group(function() {
        Route::get('user/', 'getUserAssistance')->middleware('jwt.verify');
        Route::post('user/', 'assignsUserAssistance');
    });

});

