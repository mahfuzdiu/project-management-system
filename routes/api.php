<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\LogMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//unauthenticated routes
Route::group([],function () {
   Route::post('/login', [AuthController::class, 'login']);
   Route::post('/register', [AuthController::class, 'register']);
});

//authenticated routes
//Route::apiResource('projects', ProjectController::class)->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'log'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [UserController::class, 'getUser']);

    Route::resource('projects', ProjectController::class);

    Route::resource('tasks', TaskController::class);
    Route::get('/projects/{project_id}/tasks', [TaskController::class, 'getProjectTasks']);
    Route::post('/projects/{project_id}/tasks', [TaskController::class, 'createProjectTask']);

    Route::get('/tasks/{task_id}/comments', [CommentController::class, 'getTaskComments']);
    Route::post('/tasks/{task_id}/comments', [CommentController::class, 'createTaskComment']);
});


