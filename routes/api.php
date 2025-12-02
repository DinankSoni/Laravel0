<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']); // Create new user
Route::post('/login', [AuthController::class, 'login']);       // Authenticate user and get token

// Protected routes (require valid Bearer token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); // Revoke token

    // Task Resource Routes
    Route::get('/tasks', [TaskController::class, 'index']); // index: GET /api/tasks (List all tasks)
    Route::post('/tasks', [TaskController::class, 'store']); // store: POST /api/tasks (Create new task)
    Route::get('/tasks/{task}', [TaskController::class, 'show']); // show: GET /api/tasks/{id} (Get single task)
    Route::put('/tasks/{task}', [TaskController::class, 'update']); // update: PUT /api/tasks/{id} (Update task)
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']); // destroy: DELETE /api/tasks/{id} (Delete task)

    // Custom route for marking task as complete
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete']); // complete: PATCH /api/tasks/{id}/complete (Mark task as complete)
});
