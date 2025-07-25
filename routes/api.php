<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    InfoController,
    UserController,
    UserProfileController,
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

Route::prefix('v1/')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::prefix('auth/')->controller(AuthController::class)->group(function () {
        Route::get('user', 'getUserInfo');
        Route::post('logout', 'logout');
    })->middleware('jwt.verify');

    Route::prefix('info/')->controller(InfoController::class)->group(function () {
        Route::get('', 'appInfo')->middleware('jwt.verify');
    });

    Route::prefix('users/')->controller(UserController::class)->group(function () {
        Route::get('', 'index');
        Route::post('', 'create');
        Route::get('{user_id}', 'show');
        Route::get('search/{email}', 'searchByEmail');
        Route::get('by-role/{role}', 'usersByRole')->middleware('jwt.verify');
        Route::get('birthday/today', 'birthdayUsers')->middleware('jwt.verify');
        Route::put('', 'update')->middleware('jwt.verify');
        Route::put('upload-image-profile', 'uploadImageProfile')->middleware('jwt.verify');
        Route::delete('{user_id}', 'delete')->middleware('jwt.verify');
        Route::get('list/team', 'usersTeam');
    });

    Route::prefix('users-profile/')->controller(UserProfileController::class)->group(function () {
        Route::get('{id}', 'userProfileInfo');
        Route::post('', 'assignProfileInfo');
        Route::put('{id}', 'updateProfileInfo');
    });


    Route::prefix('password/')->controller(PasswordController::class)->group(function () {
        Route::post('email', 'generateToken');
        Route::post('reset', 'changePassword');
        Route::get('validate-token/{token}', 'validateToken');
    });

    Route::prefix('contents/')->group(function() {

        Route::get('', [ContentController::class, 'index'])->middleware('jwt.verify');
        Route::get('tips', [ContentController::class, 'getTipsContent']);
        Route::get('{slug}/detail', [ContentController::class, 'show']);
        Route::delete('{slug}', [ContentController::class, 'delete']);
        Route::patch('{slug}/status', [ContentController::class, 'updateStatus']);

        Route::prefix('events/')->controller(EventController::class)->group(function() {
            Route::post('', 'create');
            Route::put('{slug}', 'update');
        });

        Route::prefix('notice/')->controller(NoticeController::class)->group(function() {
            Route::post('', 'create');
            Route::put('{slug}', 'update');
        });
    });


    Route::prefix('swimming-levels/')->controller(SwimmingLevelController::class)->group(function() {
        Route::get('', 'index');
        Route::get('user', 'byUser')->middleware('jwt.verify');
        Route::post('assign-to-user', 'assignToUser');
        Route::put('{level_id}', 'updateLevel');
    });

    Route::prefix('schedules/')->controller(UserScheduleController::class)->group(function() {
        Route::get('', 'schedulesByUser')->middleware('jwt.verify');
        Route::post('', 'assignSchedulesToUser');
    });

    Route::prefix('ia/')->controller(IAController::class)->group(function() {
        Route::get('chat/history', 'conversationByUser')->middleware('jwt.verify');
        Route::post('chat', 'chatIA')->middleware('jwt.verify');;
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

