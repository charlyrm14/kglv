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
    SwimmingLevelController,
    UserScheduleController,
    IAController,
    FileController,
    UserAttendanceController,
    ReportingController,
    PasswordController
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
        Route::get('{user_id}', 'appInfo')->middleware('jwt.verify');
    });

    Route::prefix('users/')->controller(UserController::class)->group(function () {
        Route::get('', 'index');
        Route::post('', 'create');
        Route::get('{user_id}', 'show');
        Route::get('search/{email}', 'searchByEmail');
        Route::delete('{user_id}', 'delete');
    });

    Route::prefix('password/')->controller(PasswordController::class)->group(function () {
        Route::post('email', 'generateToken');
        Route::post('reset', 'changePassword');
        Route::get('validate-token/{token}', 'validateToken');
    });

    Route::prefix('contents/')->group(function() {

        Route::get('', [ContentController::class, 'index'])->middleware('jwt.verify');
        Route::get('{slug}/detail', [ContentController::class, 'show']);
        Route::delete('{slug}', [ContentController::class, 'delete']);
        Route::patch('{slug}/status', [ContentController::class, 'updateStatus']);

        Route::prefix('events/')->controller(EventController::class)->group(function() {
            Route::post('', 'create');
        });

        Route::prefix('notice/')->controller(NoticeController::class)->group(function() {
            Route::post('', 'create');
        });
    });


    Route::prefix('swimming-levels/')->controller(SwimmingLevelController::class)->group(function() {
        Route::get('', 'index');
        Route::get('user', 'byUser')->middleware('jwt.verify');
        Route::post('assign-to-user', 'assignToUser');
    });

    Route::prefix('schedules/')->controller(UserScheduleController::class)->group(function() {
        Route::get('', 'schedulesByUser')->middleware('jwt.verify');
        Route::post('', 'assignSchedulesToUser');
    });

    Route::prefix('ia/')->controller(IAController::class)->group(function() {
        Route::get('chat/history', 'conversationByUser')->middleware('jwt.verify');
        Route::post('chat', 'chatIA');
    });

    Route::prefix('file/')->controller(FileController::class)->group(function() {
        Route::post('upload', 'uploadFile');
        Route::post('delete', 'deleteFile');
    });

    Route::prefix('attendances/')->controller(UserAttendanceController::class)->group(function() {
        Route::get('user/', 'getUserAttendance')->middleware('jwt.verify');
        Route::post('user/', 'assignUserAttendance');
    });

    Route::prefix('reports/')->controller(ReportingController::class)->group(function() {
        Route::post('user-attendance', 'userAttendance');
    });

});

