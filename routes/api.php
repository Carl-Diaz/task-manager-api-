<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

// Rutas públicas (sin autenticación)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren autenticación Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Proyectos
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    Route::post('/projects/{id}/archive', [ProjectController::class, 'archive']);
    Route::post('/projects/{id}/unarchive', [ProjectController::class, 'unarchive']);
    
    // Tareas
    Route::get('/projects/{projectId}/tasks', [TaskController::class, 'index']);
    Route::post('/projects/{projectId}/tasks', [TaskController::class, 'store']);
    Route::get('/projects/{projectId}/tasks/{id}', [TaskController::class, 'show']);
    Route::put('/projects/{projectId}/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/projects/{projectId}/tasks/{id}', [TaskController::class, 'destroy']);
    Route::post('/projects/{projectId}/tasks/{id}/complete', [TaskController::class, 'complete']);
    Route::post('/projects/{projectId}/tasks/{id}/uncomplete', [TaskController::class, 'uncomplete']);
});