<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReportLikeController;
use App\Http\Controllers\Api\ReportCommentController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-reset-token', [AuthController::class, 'verifyResetToken']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/user/profile/photo', [AuthController::class, 'deleteProfilePhoto']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard & Reports API
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/reports', [\App\Http\Controllers\Api\ReportController::class, 'store']);
    Route::get('/my-reports', [\App\Http\Controllers\Api\ReportController::class, 'myReports']);

    // Saved Locations API
    Route::get('/saved-locations', [\App\Http\Controllers\Api\SavedLocationController::class, 'index']);
    Route::post('/saved-locations', [\App\Http\Controllers\Api\SavedLocationController::class, 'store']);
    Route::put('/saved-locations/{id}', [\App\Http\Controllers\Api\SavedLocationController::class, 'update']);
    Route::delete('/saved-locations/{id}', [\App\Http\Controllers\Api\SavedLocationController::class, 'destroy']);
    
    // Likes & Comments
    Route::post('/reports/{id}/like', [ReportLikeController::class, 'toggleLike']);
    Route::get('/reports/{id}/comments', [ReportCommentController::class, 'index']);
    Route::post('/reports/{id}/comments', [ReportCommentController::class, 'store']);
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
