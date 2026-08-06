<?php

use App\Http\Controllers\Api\AppInfoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\HelpController;
use App\Http\Controllers\Api\MapDataController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReportCommentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReportLikeController;
use App\Http\Controllers\Api\SavedLocationController;
use App\Http\Controllers\Api\ShelterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-reset-token', [AuthController::class, 'verifyResetToken']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/help', [HelpController::class, 'index']);
Route::get('/app-info', [AppInfoController::class, 'index']);

// Public endpoints (or optional auth)
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/map-data', [MapDataController::class, 'index']);
Route::get('/shelters', [ShelterController::class, 'index']);
Route::get('/reports/{id}', [ReportController::class, 'show']);
Route::get('/reports/{id}/comments', [ReportCommentController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/user/profile/photo', [AuthController::class, 'deleteProfilePhoto']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard & Reports API
    Route::post('/reports', [ReportController::class, 'store']);
    Route::put('/reports/{id}', [ReportController::class, 'update']);
    Route::get('/my-reports', [ReportController::class, 'myReports']);

    // Saved Locations API
    Route::get('/saved-locations', [SavedLocationController::class, 'index']);
    Route::post('/saved-locations', [SavedLocationController::class, 'store']);
    Route::put('/saved-locations/{id}', [SavedLocationController::class, 'update']);
    Route::delete('/saved-locations/{id}', [SavedLocationController::class, 'destroy']);

    // Likes & Comments
    Route::post('/reports/{id}/like', [ReportLikeController::class, 'toggleLike']);
    Route::post('/reports/{id}/comments', [ReportCommentController::class, 'store']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
