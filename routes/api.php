<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HospitalController;
use App\Http\Controllers\Api\SpecialtyController;
use App\Http\Controllers\Api\SearchController;

// Public Endpoints (No authentication required)
Route::get('/hospitals', [HospitalController::class, 'index']);
Route::get('/hospitals/{slug}', [HospitalController::class, 'show']);
Route::get('/hospitals/{hospital}/available-slots', [\App\Http\Controllers\Api\SlotController::class, 'index']);
Route::get('/specialties', [SpecialtyController::class, 'index']);
Route::get('/search', [SearchController::class, 'index']);

// Authentication Endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Endpoints (Requires valid Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/user/avatar', [AuthController::class, 'updateAvatar']);
    
    // Medical Record
    Route::get('/user/medical-record', [\App\Http\Controllers\Api\MedicalRecordController::class, 'show']);
    Route::post('/user/medical-record', [\App\Http\Controllers\Api\MedicalRecordController::class, 'update']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Save FCM Token
    Route::post('/user/fcm-token', function (\Illuminate\Http\Request $request) {
        $request->validate(['fcm_token' => 'required|string']);
        $request->user()->update(['fcm_token' => $request->fcm_token]);
        return response()->json(['message' => 'FCM Token saved successfully.']);
    });
    
    // Patient Appointments
    Route::get('/appointments', [\App\Http\Controllers\Api\AppointmentController::class, 'index']);
    Route::post('/appointments', [\App\Http\Controllers\Api\AppointmentController::class, 'store']);
    Route::post('/appointments/{id}/cancel', [\App\Http\Controllers\Api\AppointmentController::class, 'cancel']);

    // Reviews
    Route::post('/hospitals/{hospital}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
});
