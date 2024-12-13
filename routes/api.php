<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ReservationController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// get all clients
Route::get('/client', [AuthController::class, 'clients']);

// get all professionals
Route::get('/professional', [AuthController::class, 'professionals']);

// save a location
Route::post('/locations', [LocationController::class, 'store']);

// view feedbacks
Route::get('/feedbacks/{service_id}', [FeedbackController::class, 'show']);



// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Client routes
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::get('/show/client', [ClientController::class, 'show']);
    Route::put('/client', [ClientController::class, 'update']);
    Route::delete('/client', [ClientController::class, 'destroy']);
    // services view
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{id}', [ServiceController::class, 'show']);
    // favorites by clients
    Route::get('/favorites', [FavorisController::class, 'index']);
    Route::get('/favorites/{id}', [FavorisController::class, 'show']);
    Route::post('/favorites', [FavorisController::class, 'store']);
    Route::delete('/favorites/{id}', [FavorisController::class, 'destroy']);
    // feedbackes
    Route::post('/feedbacks', [FeedbackController::class, 'store']);
    Route::put('/feedbacks/{id}', [FeedbackController::class, 'update']);
    Route::delete('/feedbacks/{id}', [FeedbackController::class, 'destroy']);

    // reservations
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
});

// Professional routes
Route::middleware(['auth:sanctum', 'role:professional'])->group(function () {
    Route::get('/show/professional', [ProfessionalController::class, 'show']);
    Route::put('/professional', [ProfessionalController::class, 'update']);
    Route::delete('/professional', [ProfessionalController::class, 'destroy']);
    // show services
    Route::get('/MyServices', [ServiceController::class, 'showMyServices']);
    // services create
    Route::post('/services', [ServiceController::class, 'store']);
    // services update
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    // services delete
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
    //reservations
    Route::put('/reservations/{id}/status', [ReservationController::class, 'updateStatus']);
});

// Admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::get('/admin/users/{id}', [AdminController::class, 'show']);
    Route::put('/admin/users/{id}', [AdminController::class, 'update']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
    Route::get('/admins', [AdminController::class, 'admins']);
});
// any user
Route::middleware(['auth:sanctum'])->group(function () {
    // reservations
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
});
