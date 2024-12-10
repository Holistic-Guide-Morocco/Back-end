<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\AdminController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/client', [AuthController::class, 'clients']);
Route::get('/professional', [AuthController::class, 'professionals']);


// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Client routes
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::get('/show/client', [ClientController::class, 'show']);
    Route::put('/client', [ClientController::class, 'update']);
    Route::delete('/client', [ClientController::class, 'destroy']);
});

// Professional routes
Route::middleware(['auth:sanctum', 'role:professional'])->group(function () {
    Route::get('/show/professional', [ProfessionalController::class, 'show']);
    Route::put('/professional', [ProfessionalController::class, 'update']);
    Route::delete('/professional', [ProfessionalController::class, 'destroy']);
});

// Admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::get('/admin/users/{id}', [AdminController::class, 'show']);
    Route::put('/admin/users/{id}', [AdminController::class, 'update']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
    Route::get('/admins', [AdminController::class, 'admins']);
});
